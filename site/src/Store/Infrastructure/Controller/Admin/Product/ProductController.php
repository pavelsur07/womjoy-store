<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Placeholder\PlaceholderService;
use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductPrice;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Form\Product\ProductEditForm;
use App\Store\Infrastructure\Form\Product\ProductSeoEditForm;
use App\Store\Infrastructure\Repository\Category\CategoryForChoice;
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
            'pagination' => $productRepository->getAll(
                page: $request->query->getInt('page', 1),
                size: $request->query->getInt('size', self::PER_PAGE),
            ),
        ]);
    }

    #[Route('/new', name: '.new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, Flusher $flusher, CategoryRepositoryInterface $categories): Response
    {
        $product = new Product(new ProductPrice());
        $form = $this->createForm(ProductEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product = new Product(new ProductPrice($data['price']));
            $product->setName($data['name']);
            $product->setDescription($data['description']);

            $mainCategory = $categories->get((int)$data['mainCategory']->getValue());
            $product->setMainCategory($mainCategory);

            $productRepository->save($product, true);

            return $this->redirectToRoute('store.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Product $product,
        ProductRepository $productRepository,
        Flusher $flusher,
        CategoryRepositoryInterface $categories,
    ): Response {
        $form = $this->createForm(
            ProductEditForm::class,
            [
                'article'=> $product->getArticle(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'mainCategory' => $product->getMainCategory() ? new CategoryForChoice(
                    label: $product->getMainCategory()->getName(),
                    value: (string)$product->getMainCategory()->getId()
                ) : null,
                'price' => $product->getPrice()->getPrice(),
                'listPrice' => $product->getPrice()->getListPrice(),
                'isPreSale' => $product->isPreSale(),
                'popularity' => $product->getPopularity(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $product->setName($data['name']);
            $product->setArticle($data['article']);
            $product->setDescription($data['description']);
            $product->getPrice()->setPrice($data['price']);
            $product->getPrice()->setListPrice($data['listPrice']);
            if ($data['mainCategory'] !== null) {
                $product->setMainCategory($categories->get((int)$data['mainCategory']->getValue()));
            }
            $product->setCategoriesIds();

            $product->setPopularity($data['popularity']);

            if ($product->isPreSale()) {
                if (!$data['isPreSale']) {
                    $product->isPreSaleDisable();
                }
            } else {
                if ($data['isPreSale']) {
                    $product->isPreSaleActive();
                }
            }

            $flusher->flush();

            return $this->redirectToRoute('store.admin.product.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('store/admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/seo', name: '.seo', methods: ['GET', 'POST'])]
    public function seo(Request $request, Product $product, ProductRepository $productRepository, Flusher $flusher, SlugifyService $slug): Response
    {
        $category = $product->getMainCategory();
        $slug = $product->getMainCategory()->getPrefixSlugProduct() . '-article-' . $product->getId();

        $form = $this->createForm(
            ProductSeoEditForm::class,
            [
                'seoTitle' => $category->getTitleProductTemplate() ?
                    PlaceholderService::replacePlaceholders(
                        $category->getTitleProductTemplate(),
                        $product->getPlaceholders()
                    ) :
                    $product->getSeoMetadata()->getSeoTitle(),
                'seoDescription' => $category->getDescriptionProductTemplate() ?
                    PlaceholderService::replacePlaceholders(
                        $category->getDescriptionProductTemplate(),
                        $product->getPlaceholders()
                    ) :
                    $product->getSeoMetadata()->getSeoDescription(),
                // 'slug' => $product->getSlug(),
                'slug' => $slug,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $product->getSeoMetadata()->setSeoTitle($data['seoTitle']);
            $product->getSeoMetadata()->setSeoDescription($data['seoDescription']);

            if ($data['slug'] !== null) {
                $product->setSlug($slug);
            }

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
