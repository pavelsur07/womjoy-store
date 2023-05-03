<?php

namespace App\Matrix\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route(path: '/admin/matrix/products/', name: 'matrix.admin.product.index')]
    public function index(): Response
    {
        return $this->render('admin/matrix/product/index.html.twig');
    }
}