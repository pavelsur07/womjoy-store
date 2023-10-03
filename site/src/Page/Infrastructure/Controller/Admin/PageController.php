<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Controller\Admin;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Page\Domain\Entity\Page;
use App\Page\Domain\Exception\PageException;
use App\Page\Infrastructure\Form\PageEditForm;
use App\Page\Infrastructure\Form\PageNewForm;
use App\Page\Infrastructure\Form\PageSeoEditForm;
use App\Page\Infrastructure\Repository\PageRepository;
use DateTimeImmutable;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/pages', name: 'page.admin.page')]
class PageController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(PageRepository $pages): Response
    {
        return $this->render(
            'admin/page/page/index.html.twig',
            [
                'pagination' => $pages->getAll(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, PageRepository $pages): Response
    {
        $form = $this->createForm(PageNewForm::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $page  = new Page(name: $data['name'], createdAt: new DateTimeImmutable());
            $pages->save($page, true);

            $this->addFlash('success', 'Success new page created.');
            return $this->redirectToRoute('page.admin.page.index');
        }

        return $this->render(
            'admin/page/page/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Request $request, PageRepository $pages, Flusher $flusher): Response
    {
        $page = $pages->get($id);
        $form = $this->createForm(
            PageEditForm::class,
            [
                'name' => $page->getName(),
                'value' => $page->getValue(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $page->changeName($data['name']);
            $page->changeValue($data['value']);
            $flusher->flush();

            $this->addFlash('success', 'Success new page created.');
            return $this->redirectToRoute('page.admin.page.edit', ['id'=>$id]);
        }

        return $this->render(
            'admin/page/page/edit.html.twig',
            [
                'form' => $form->createView(),
                'page' => $page,
            ]
        );
    }

    #[Route(path: '/{id}/seo/edit', name: '.seo.edit')]
    public function seoEdit(int $id, Request $request, PageRepository $pages, Flusher $flusher, SlugifyService $slugify): Response
    {
        $page = $pages->get($id);
        $form = $this->createForm(
            PageSeoEditForm::class,
            [
                'h1' => $page->getH1(),
                'description' => $page->getSeoDescription(),
                'title' => $page->getSeoTitle(),
                'slug' => $page->getSlug(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $page->setH1($data['h1']);
                $page->setSeoTitle($data['title']);
                $page->setSeoDescription($data['description']);
                $page->setSlug($slugify->generate($data['slug']));
                $flusher->flush();

                $this->addFlash('success', 'Success new page created.');
            } catch (PageException|DomainException $e) {
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('page.admin.page.seo.edit', ['id'=> $id]);
        }

        return $this->render(
            'admin/page/page/seo/edit.html.twig',
            [
                'form' => $form->createView(),
                'page' => $page,
            ]
        );
    }
}
