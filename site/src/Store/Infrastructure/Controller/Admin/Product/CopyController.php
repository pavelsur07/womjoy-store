<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Slugify\SlugifyService;
use App\Store\Domain\Entity\Home\AssignCategory;
use App\Store\Domain\Entity\Product\AttributeAssignment;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product', name: 'store.admin.product')]
class CopyController extends AbstractController
{
    #[Route('/{id}/copy-product', name: '.copy')]
    public function copy(Request $request, Product $product, ProductRepository $productRepository, Flusher $flusher, SlugifyService $slug): Response
    {
        $newProduct = new Product(price: $product->getPrice());
        $newProduct->setName($product->getName());
        $newProduct->setDescription($product->getDescription());
        $newProduct->setMainCategory($product->getMainCategory());

        /** @var AttributeAssignment $attribute */
        foreach ($product->getAttributes() as $attribute) {
            $newProduct->assignAttribute(
                attribute: $attribute->getAttribute(),
                variant: $attribute->getVariant(),
            );
        }
        $productRepository->save($newProduct);
        $flusher->flush();

        /** @var AssignCategory $category */
        foreach ($product->getCategories() as $category) {
            $newProduct->assignCategory(category: $category->getCategory());
        }

        $newProduct->regenerateSeoMetadataByTemplate();
        $slug = $newProduct->getMainCategory()->getPrefixSlugProduct() . '-article-' . $newProduct->getId();
        $newProduct->setSlug($slug);

        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.edit', ['id'=> $newProduct->getId()], Response::HTTP_SEE_OTHER);
    }
}
