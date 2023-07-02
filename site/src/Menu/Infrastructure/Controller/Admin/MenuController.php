<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Controller\Admin;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Menu\Domain\Entity\Menu;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use App\Menu\Infrastructure\Form\MenuEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/menu/menu', name: 'menu.admin.menu')]
class MenuController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(MenuRepositoryInterface $menus): Response
    {
        return $this->render(
            'menu/admin/menu/index.html.twig',
            [
                'pagination' => $menus->list(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, MenuRepositoryInterface $menus, Flusher $flusher): Response
    {
        $form = $this->createForm(MenuEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $menu = new Menu(
                name: $data['name'],
                href: $data['href']
            );

            $menus->save($menu, true);
            $menu->setRoot($menu->getId());
            $flusher->flush();

            return $this->redirectToRoute('menu.admin.menu.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render(
            'menu/admin/menu/new.html.twig',
            [
                'pagination' => [],
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Menu $menu, Request $request, Flusher $flusher, MenuRepositoryInterface $menus): Response
    {
        $form = $this->createForm(
            MenuEditForm::class,
            [
                'name' => $menu->getName(),
                'href' => $menu->getHref(),
            ]
        );

        $form->handleRequest($request);

        $breadcrumbs = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $menu->setName($data['name']);
            $menu->setHref($data['href']);

            $flusher->flush();

            return $this->redirectToRoute('menu.admin.menu.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'menu/admin/menu/edit.html.twig',
            [
                'breadcrumbs' => $this->breadcrumbs($menu),
                'menu' => $menu,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/add-item', name: '.add_item')]
    public function addItem(int $id, Menu $menu, Request $request, MenuRepositoryInterface $menus): Response
    {
        $form = $this->createForm(
            MenuEditForm::class,
            [
                'name' => $menu->getName(),
                'href' => $menu->getHref(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $count = \count($menu->getChildren());
            $newItem = new Menu(name: $data['name'], href: $data['href']);
            $newItem->setParent($menu);
            $newItem->setSort($count++);

            $menus->save($newItem, true);

            return $this->redirectToRoute('menu.admin.menu.edit', ['id'=> $menu->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render(
            'menu/admin/menu/new.html.twig',
            [
                /*
                'breadcrumbs' => $this->breadcrumbs($menu),
                'menu' => $menu,
                */
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/remove', name: '.remove')]
    public function remove(int $id, Menu $menu, Request $request, MenuRepositoryInterface $menus): Response
    {
        $menus->remove($menu, true);

        return $this->redirectToRoute('menu.admin.menu.index');
    }

    #[Route(path: '/{id}/remove-item/{item_id}', name: '.remove_item')]
    public function removeItem(int $id, int $item_id, Menu $menu, Request $request, MenuRepositoryInterface $menus): Response
    {
        $item = $menus->get($item_id);
        $menus->remove($item, true);

        return $this->redirectToRoute('menu.admin.menu.edit', ['id'=> $menu->getId()]);
    }

    private function breadcrumbs(Menu $menu, ?array $bread = null): array
    {
        $bread[] = $menu;

        if ($menu->getParent() !== null) {
            return $this->breadcrumbs($menu->getParent(), $bread);
        }

        return array_reverse($bread);
    }
}
