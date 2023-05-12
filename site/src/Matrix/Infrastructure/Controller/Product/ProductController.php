<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Entity\Product\Product;
use App\Matrix\Domain\Repository\ProductRepositoryInterface;
use App\Matrix\Infrastructure\Form\ProductCreatedForm;
use App\Matrix\Infrastructure\Form\ProductEditForm;
use DateTimeImmutable;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route(path: '/admin/matrix/products/', name: 'matrix.admin.product.index')]
    public function index(ProductRepositoryInterface $products): Response
    {
        return $this->render(
            'admin/matrix/product/index.html.twig',
            [
                'pagination' => $products->list(),
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
        return $this->render('admin/matrix/product/create.html.twig', ['form'=> $form->createView()]);
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
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product->changeName(trim($data['name']));
            $product->getStatus()->setStatus($data['status']);

            $flusher->flush();
        }

        return $this->render(
            'admin/matrix/product/edit.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
            ]
        );
    }
}
