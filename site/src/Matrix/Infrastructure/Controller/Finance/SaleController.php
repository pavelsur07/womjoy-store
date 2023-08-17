<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Finance;

use App\Matrix\Infrastructure\Repository\Finance\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/finance/sale', name: 'matrix.admin.finance.sale')]
class SaleController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(SaleRepository $sales): Response
    {
        return $this->render(
            'admin/finance/sale/index.html.twig',
            [
                'pagination' => $sales->list(),
            ]
        );
    }
}
