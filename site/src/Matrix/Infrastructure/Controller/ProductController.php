<?php

namespace App\Matrix\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route(path: '/admin/matrix/products/', name: 'matrix.admin.product.index')]
    public function index(): Response
    {
        return $this->render('admin/matrix/product/index.html.twig');
    }

    #[Route(path: '/admin/matrix/products/create', name: 'matrix.admin.product.create')]
    public function create(Request $request): Response
    {
        return $this->render('admin/matrix/product/create.html.twig');
    }
}