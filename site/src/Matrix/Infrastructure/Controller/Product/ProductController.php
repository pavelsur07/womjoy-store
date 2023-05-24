<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Entity\Product\Product;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use App\Matrix\Infrastructure\Form\Product\ProductCreatedForm;
use App\Matrix\Infrastructure\Form\Product\ProductEditForm;
use App\Matrix\Infrastructure\Form\Product\ProductFilterListForm;
use App\Matrix\Infrastructure\Repository\Product\ProductFilter;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public const PER_PAGE = 15;

    #[Route(path: '/admin/matrix/products/', name: 'matrix.admin.product.index')]
    public function index(Request $request, ProductRepositoryInterface $products): Response
    {
        $filter = new ProductFilter();
        $form = $this->createForm(ProductFilterListForm::class, $filter);
        $form->handleRequest($request);

        $pagination = $products->index(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
            filter: $filter,
        );

        return $this->render(
            'matrix/admin/product/index.html.twig',
            [
                'pagination' => $pagination,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/admin/matrix/products/create', name: 'matrix.admin.product.create')]
    public function create(Request $request, ProductRepositoryInterface $products): Response
    {
        $form = $this->createForm(ProductCreatedForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $product = $products->findByArticle(mb_strtolower(trim($data['article'])));

                if ($product !== null) {
                    throw new DomainException('Duplicate key value violates unique constraint article.');
                }

                $product = new Product(
                    createdAt: DateTimeImmutable::createFromMutable($data['createdAt']),
                    article: mb_strtolower(trim($data['article'])),
                    name: $data['name'],
                    subject: $data['subject'],
                    model: $data['model'],
                    color: $data['color'],
                );

                $products->save($product, true);
            } catch (DomainException|UniqueConstraintViolationException $e) {
                $this->addFlash('danger', $e->getMessage());
            }

            return $this->redirectToRoute('matrix.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('matrix/admin/product/create.html.twig', ['form'=> $form->createView()]);
    }

    #[Route(path: '/admin/matrix/products/{id}/remove', name: 'matrix.admin.product.remove')]
    public function remove(int $id, Request $request, ProductRepositoryInterface $products): Response
    {
        $product = $products->get($id);

        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $products->remove($product, true);
        }

        return $this->redirectToRoute('matrix.admin.product.index');
    }

    #[Route(path: '/admin/matrix/products/{id}/edit', name: 'matrix.admin.product.edit')]
    public function edit(int $id, Request $request, ProductRepositoryInterface $products, Flusher $flusher): Response
    {
        $product = $products->get($id);

        $form = $this->createForm(
            ProductEditForm::class,
            [
                'name'=> $product->getName(),
                'status' => $product->getStatus()->value(),
                'path' => $product->getPathExternalImage()??' ',
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product->changeName(trim($data['name']));
            $product->getStatus()->setStatus($data['status']);
            $product->setPathExternalImage($data['path']);

            $flusher->flush();
            $this->addFlash('danger', 'Supper success.');
        }

        return $this->render(
            'matrix/admin/product/edit.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
            ]
        );
    }
}
