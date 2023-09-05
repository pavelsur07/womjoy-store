<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Company;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/company', name: 'matrix.admin.company')]
class CompanyController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(): Response
    {
        return $this->render('admin/matrix/company/index.html.twig');
    }
}
