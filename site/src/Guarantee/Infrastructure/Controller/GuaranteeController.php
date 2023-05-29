<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Guarantee\Infrastructure\Form\GuaranteeNewForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuaranteeController extends BaseController
{
    #[Route(path: '/guarantee', name: 'guarantee.new')]
    public function show(Request $request): Response
    {
        $form = $this->createForm(GuaranteeNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }
        return $this->render(
            'guarantee/new.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'form' => $form->createView(),
            ]
        );
    }
}
