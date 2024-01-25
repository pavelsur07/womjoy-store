<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Yml;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Yml\Item;
use App\Store\Domain\Entity\Yml\Yml;
use App\Store\Infrastructure\Form\Yml\YmlEditForm;
use App\Store\Infrastructure\Form\Yml\YmlNewForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use App\Store\Infrastructure\Repository\YmlRepository;
use App\Store\Infrastructure\Service\YandexMarket\YandexMarketGenerator;
use Exception;
use League\Flysystem\FilesystemException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/yml', name: 'store.admin.yml')]
class YmlController extends AbstractController
{
    public const PER_PAGE = 10;

    #[Route(path: '/', name: '.index')]
    public function index(YmlRepository $ymls): Response
    {
        return $this->render(
            'admin/store/yml/index.html.twig',
            [
                'pagination' => $ymls->list(),
            ]
        );
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/{id}/generate', name: '.generate')]
    public function generate(int $id, Yml $yml, Request $request, YandexMarketGenerator $generator): Response
    {
        $categories = [];
        $products =[];
        /** @var Item $item */
        foreach ($yml->getItems() as $item) {
            $categories[$item->getProduct()->getMainCategory()->getId()] = $item->getProduct()->getMainCategory();
            $products[] = $item->getProduct();
        }

        $generator->setProperty(
            company: 'Company',
            name: 'Womjoy',
            url: 'https://womjoy.ru'
        );

        $generator->generate($categories, $products, $yml->getFileName());

        $this->addFlash('success', 'Success generate!');

        return $this->redirectToRoute(
            'store.admin.yml.edit',
            [
                'id' => $id,
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, YmlRepository $ymls): Response
    {
        $form = $this->createForm(YmlNewForm::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $yml = new Yml(
                name: $data['name'],
                fileName: Uuid::uuid4()->toString(),
                path: ''
            );

            $ymls->save($yml, true);
            return $this->redirectToRoute('store.admin.yml.index');
        }

        return $this->render(
            'admin/store/yml/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Yml $yml, Request $request, ProductRepository $products): Response
    {
        $pagination = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );

        $form = $this->createForm(
            YmlEditForm::class,
            [
                'name' => $yml->getName(),
                'fileName' => $yml->getFileName(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render(
            'admin/store/yml/edit.html.twig',
            [
                'form' => $form->createView(),
                'pagination' => $pagination,
                'yml' => $yml,
            ]
        );
    }

    #[Route(path: '/{id}/add/{add_id}/product', name: '.add')]
    public function add(int $id, Yml $yml, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $productAdd = $products->get((int)$request->get('add_id'));
        try {
            $yml->add($productAdd);
            $flusher->flush();
            $this->addFlash('success', 'Success added product  - ' . $productAdd->getName() . '.');
        } catch (Exception $e) {
            $this->addFlash('danger', 'Error added product. ' . $e->getMessage() . $productAdd->getName() . '.');
        }

        return $this->redirectToRoute('store.admin.yml.edit', ['id' => $id]);
    }

    #[Route(path: '/{id}/remove/{remove_id}/product', name: '.remove')]
    public function remove(int $id, Yml $yml, Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $productRemove = $products->get((int)$request->get('remove_id'));
        try {
            $yml->remove($productRemove->getId());
            $flusher->flush();
            $this->addFlash('success', 'Success remove product  - ' . $productRemove->getName() . '.');
        } catch (Exception $e) {
            $this->addFlash('danger', 'Error remove product. ' . $e->getMessage() . $productRemove->getName() . '.');
        }

        return $this->redirectToRoute('store.admin.yml.edit', ['id' => $id]);
    }

    #[Route(path: '/active/{id}', name: '.active')]
    public function active(int $id, Yml $yml, Request $request): void {}

    #[Route(path: '/disable/{id}', name: '.disable')]
    public function disable(int $id, Yml $yml, Request $request): void {}
}
