<?php

namespace App\Guarantee\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuaranteeController extends BaseController
{
    #[Route(path: '/guarantee',name: 'guarantee.new')]
    public function show(Request $request): Response
    {
        return $this->render('guarantee/new.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }
}