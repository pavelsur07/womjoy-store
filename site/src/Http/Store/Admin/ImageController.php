<?php

declare(strict_types=1);

namespace App\Http\Store\Admin;

use App\Command\Product\Image\Add\File;
use App\Command\Product\Image\Add\ProductImageAddCommand;
use App\Command\Product\Image\Add\ProductImageHandler;
use App\Command\Product\Image\Delete\ProductImageDeleteCommand;
use App\Command\Product\Image\Delete\ProductImageDeleteHandler;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Form\ProductImageAddForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/{id}/image', name: 'admin.product.image')]
class ImageController extends AbstractController
{
    #[Route('/', name: '.index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProductRepository $products, FileUploader $uploader, ProductImageHandler $handler): Response
    {
        $id = (int)$request->get('id');
        $product = $products->get($id);

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

            $command = new ProductImageAddCommand(
                productId: $id,
                files: $files
            );

            $handler($command);

            return $this->redirectToRoute('admin.product.image.index', ['id'=> $id]);
        }
        return $this->render(
            'admin/product/image/index.html.twig',
            [
                'product'=> $product,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/{image_id}/delete', name: '.delete', methods: ['GET', 'POST'])]
    public function delete(int $image_id, Request $request, ProductImageDeleteHandler $handler): Response
    {
        /*if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }*/
        $command = new ProductImageDeleteCommand($image_id);
        $handler($command);

        return $this->redirectToRoute('admin.product.image.index', ['id'=> $request->get('id')]);
    }
}
