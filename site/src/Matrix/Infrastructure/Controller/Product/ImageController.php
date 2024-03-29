<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use App\Matrix\Infrastructure\Form\ProductImageAddForm;
use App\Store\Application\Command\Product\Image\Add\File;
use Gumlet\ImageResizeException;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/products/{product_id}/images', name: 'matrix.admin.product.image')]
class ImageController extends AbstractController
{
    public const THUMBNAILS = [
        [200, 300],
        [900, 1200],
    ];

    #[Route(path: '/', name: '.index')]
    public function index(
        Request $request,
        ProductRepositoryInterface $products,
        FileUploader $uploader,
        Flusher $flusher,
        ThumbnailService $thumbnails,
    ): Response {
        $productId = (int)$request->attributes->get('product_id');
        $product = $products->get($productId);

        $form = $this->createForm(ProductImageAddForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = [];
            foreach ($form->get('files')->getData() as $file) {
                $uploaded = $uploader->upload($file);

                $files[] = new File(
                    $uploaded->getPath(),
                    $uploaded->getName(),
                    $uploaded->getSize()
                );
            }

            foreach ($files as $file) {
                $product->addImage(
                    path: $file->getPath(),
                    fileName: $file->getName(),
                    size: $file->getSize()
                );
            }
            $flusher->flush();

            foreach ($product->getImages() as $image) {
                $extension = explode('.', $image->getFileName())[1];
                if ($extension === 'png') {
                    $oldName = $image->getFileName();
                    $file = $thumbnails->convertImagePngToJpeg(
                        path: $image->getPath(),
                        name: $image->getFileName(),
                    );

                    $image->setFileName($file->getName());

                    $uploader->remove(path: $image->getPath(), name: $oldName);
                }
            }
            $flusher->flush();

            foreach ($product->getImages() as $image) {
                if (!$image->isOptimize()) {
                    try {
                        $file = $thumbnails->createThumbnail(
                            path: $image->getPath(),
                            inputName: $image->getFileName(),
                            outputPath: $image->getPath(),
                            width: 300,
                            height: 400,
                        );

                        $image->optimize();
                    } catch (ImageResizeException $e) {
                        $this->addFlash('warning', $e->getMessage());
                    } catch (FilesystemException $e) {
                        $this->addFlash('warning', $e->getMessage());
                    }
                }
            }
            $flusher->flush();
        }
        return $this->render(
            'matrix/admin/product/image/index.html.twig',
            [
                'product'=> $product,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{sort}/down', name: '.down')]
    public function down(Request $request, ProductRepositoryInterface $products, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $sortNumber = (int)$request->attributes->get('sort');

        $product = $products->get($productId);
        $countImages = $product->getImages()->count();
        if ($sortNumber === ($countImages-1)) {
            return $this->redirectToRoute('matrix.admin.product.image.index', ['product_id'=> $productId]);
        }

        $product->imageDown($sortNumber);
        $flusher->flush();

        return $this->redirectToRoute('matrix.admin.product.image.index', ['product_id'=> $productId]);
    }

    #[Route(path: '/{sort}/up', name: '.up')]
    public function up(Request $request, ProductRepositoryInterface $products, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $sortNumber = (int)$request->attributes->get('sort');

        if ($sortNumber === 0) {
            return $this->redirectToRoute('matrix.admin.product.image.index', ['product_id'=> $productId]);
        }

        $product = $products->get($productId);

        $product->imageUp($sortNumber);
        $flusher->flush();

        return $this->redirectToRoute('matrix.admin.product.image.index', ['product_id'=> $productId]);
    }

    #[Route(path: '/{image_id}/remove', name: '.remove')]
    public function remove(Request $request, ProductRepositoryInterface $products, FileUploader $uploader, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $imageId = (int)$request->attributes->get('image_id');

        $product = $products->get($productId);

        $image = $product->getImage($imageId);

        $uploader->remove(
            path: $image->getPath(),
            name: $image->getFileName()
        );

        $product->removeImage($imageId);
        $flusher->flush();

        return $this->redirectToRoute('matrix.admin.product.image.index', ['product_id'=> $productId]);
    }
}
