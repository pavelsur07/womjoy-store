<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Product;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Service\Thumbnail\ThumbnailService;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Application\Command\Product\Image\Add\File;
use App\Store\Infrastructure\Form\Product\ProductImageAddForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Gumlet\ImageResizeException;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{product_id}/image', name: 'store.admin.product.image')]
class ImageController extends AbstractController
{
    public const CACHE_PATCH = '/cache';
    public const THUMBNAILS = [
        [900, 1200],
        [300, 400],
    ];

    public function __construct(private readonly string $cachePathImages)
    {
    }

    /**
     * @throws FilesystemException
     * @throws ImageResizeException
     */
    #[Route('/', name: '.index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ProductRepository $products,
        FileUploader $uploader,
        Flusher $flusher,
        ThumbnailService $thumbnails,
    ): Response {
        $productId = (int)$request->get('product_id');
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
                    path: $file->getPatch(),
                    fileName: $file->getName(),
                    size: $file->getSize()
                );
            }
            $flusher->flush();

            foreach ($product->getImages() as $image) {
                $extension = explode('.', $image->getName())[1];
                if ($extension !== 'jpg') {
                    $oldName = $image->getName();
                    $file = $thumbnails->convertImagePngToJpeg(
                        path: $image->getPath(),
                        name: $image->getName()
                    );

                    $image->setName($file->getName());
                    $uploader->remove(path: $image->getPath(), name: $oldName);
                }
            }
            $flusher->flush();

            // Optimize & thumbnails Jpeg
            foreach ($product->getImages() as $image) {
                // try {
                foreach (self::THUMBNAILS as $thumbnail) {
                    $outputPath = $image->getPath() . $this->getCachePatch($thumbnail[0], $thumbnail[1]);
                    $thumbnails->createThumbnail(
                        path: $image->getPath(),
                        inputName: $image->getName(),
                        outputPath: $outputPath,
                        width: $thumbnail[0],
                        height: $thumbnail[1],
                    );
                }

                foreach (self::THUMBNAILS as $thumbnail) {
                    $outputPath = $image->getPath() . $this->getCachePatch($thumbnail[0], $thumbnail[1]);
                    $thumbnails->createThumbnail(
                        path: $image->getPath(),
                        inputName: $image->getName(),
                        outputPath: $outputPath,
                        width: $thumbnail[0],
                        height: $thumbnail[1],
                        type: ThumbnailService::WEBP,
                    );
                }
                /*} catch (ImageResizeException $e) {
                    $this->addFlash('warning', $e->getMessage());
                } catch (FilesystemException $e) {
                    $this->addFlash('warning', $e->getMessage());
                }*/
            }

            return $this->redirectToRoute('store.admin.product.image.index', ['product_id'=> $productId]);
        }
        return $this->render(
            'store/admin/product/image/index.html.twig',
            [
                'product'=> $product,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{sort}/up', name: '.up')]
    public function up(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $sortNumber = (int)$request->attributes->get('sort');

        if ($sortNumber === 0) {
            return $this->redirectToRoute('store.admin.product.image.index', ['product_id'=> $productId]);
        }

        $product = $products->get($productId);

        $product->imageUp($sortNumber);
        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.image.index', ['product_id'=> $productId]);
    }

    #[Route(path: '/{sort}/down', name: '.down')]
    public function down(Request $request, ProductRepository $products, Flusher $flusher): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $sortNumber = (int)$request->attributes->get('sort');

        $product = $products->get($productId);
        $countImages = $product->getImages()->count();
        if ($sortNumber === ($countImages-1)) {
            return $this->redirectToRoute('store.admin.product.image.index', ['product_id'=> $productId]);
        }

        $product->imageDown($sortNumber);
        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.image.index', ['product_id'=> $productId]);
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/{image_id}/remove', name: '.remove', methods: ['GET', 'POST'])]
    public function remove(Request $request, ProductRepository $products, Flusher $flusher, FileUploader $uploader): Response
    {
        $productId = (int)$request->attributes->get('product_id');
        $imageId = (int)$request->attributes->get('image_id');

        $product = $products->get($productId);
        $image = $product->getImage($imageId);

        foreach (self::THUMBNAILS as $thumbnail) {
            $path = $image->getPath() . $this->getCachePatch($thumbnail[0], $thumbnail[1]);
            $uploader->remove(
                path: $path,
                name: $image->getName(),
            );

            $uploader->remove(
                path: $path,
                name: $image->getName() . '.webp',
            );
        }

        $uploader->remove(
            path: $image->getPath(),
            name: $image->getName(),
        );

        $product->removeImage($imageId);
        $flusher->flush();

        return $this->redirectToRoute('store.admin.product.image.index', ['product_id'=> $productId]);
    }

    private function getCachePatch($width = 0, int $height = 0): string
    {
        return self::CACHE_PATCH . '/' . $width . '-' . $height . '/';
    }
}
