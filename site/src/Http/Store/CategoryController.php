<?php

namespace App\Http\Store;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route(path: '/collections/test', name: 'store.collections')]
    public function index(): Response
    {
        return $this->render('store/category/show.html.twig');
    }
}