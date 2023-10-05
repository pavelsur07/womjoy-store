<?php

namespace App\Matrix\Infrastructure\Controller\Seller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/seller', name: 'matrix.admin.seller')]

class SellerController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(): Response
    {
        return $this->render('admin/matrix/seller/index.html.twig');
    }


    #[Route(path: '/', name: '.new')]
    public function new(Request $request): Response
    {
        $this->addFlash('success','Success new seller added.');
        return $this->redirectToRoute('matrix.admin.syncing.key.index');
    }
}