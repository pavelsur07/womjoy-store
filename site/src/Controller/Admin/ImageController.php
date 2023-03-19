<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Command\Product\Image\Add\File;
use App\Command\Product\Image\Add\ProductImageAddCommand;
use App\Command\Product\Image\Add\ProductImageHandler;
use App\Form\ProductImageAddForm;
use App\Repository\ProductRepository;
use App\Service\Uploader\FileUploader;
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
}
