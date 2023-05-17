<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Infrastructure\Form\Product\ProductEditForm;
use App\Store\Infrastructure\Form\Product\ProductSeoEditForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product')]
class ProductController extends AbstractController
{
    public const PER_PAGE= 15;

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        return $this->render('store/admin/product/index.html.twig', [
            'pagination' => $productRepository->list(
                page: $request->query->getInt('page', 1),
                size: $request->query->getInt('size', self::PER_PAGE),
            ),
        ]);
    }

    #[Route('/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, Flusher $flusher): Response
    {
        $product = new Product(new ProductPrice());
        $form = $this->createForm(ProductEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product = new Product(new ProductPrice($data['price']));
            $product->setName($data['name']);
            $product->setDescription($data['description']);

            $productRepository->save($product, true);

            return $this->redirectToRoute('store.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository, Flusher $flusher): Response
    {
        $form = $this->createForm(
            ProductEditForm::class,
            [
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice()->getPrice(),
                'listPrice' => $product->getPrice()->getListPrice(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product->setName($data['name']);
            $product->setDescription($data['description']);
            $product->getPrice()->setPrice($data['price']);
            $product->getPrice()->setListPrice($data['listPrice']);
            $flusher->flush();

            return $this->redirectToRoute('store.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('store/admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/seo', name: '.seo', methods: ['GET', 'POST'])]
    public function seo(Request $request, Product $product, ProductRepository $productRepository, Flusher $flusher): Response
    {
        $form = $this->createForm(
            ProductSeoEditForm::class,
            [
                'seoTitle' => $product->getSeoTitle(),
                'seoDescription' => $product->getSeoDescription(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product->setSeoTitle($data['seoTitle']);
            $product->setSeoDescription($data['seoDescription']);
            $flusher->flush();

            $this->addFlash('success', 'Success seo changed.');

            return $this->redirectToRoute('store.admin.product.seo', ['id'=> $product->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('store/admin/product/seo.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/active', name: '.active', methods: ['GET', 'POST'])]
    public function active(Request $request, Product $product, ProductRepository $productRepository, Flusher $flusher): Response
    {
        $product->active();
        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.edit', ['id' => $product->getId()]);
    }

    #[Route('/{id}', name: '.delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('store.admin.product.index', [], Response::HTTP_SEE_OTHER);
    }
}
