<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Guarantee\Infrastructure\Form\GuaranteeNewForm;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuaranteeController extends BaseController
{
    public const PER_PAGE = 8;

    #[Route(path: '/guarantee/new', name: 'guarantee.new')]
    public function show(Request $request): Response
    {
        $form = $this->createForm(GuaranteeNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('guarantee.thank_you');
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

    #[Route(path: '/guarantee/thank-you', name: 'guarantee.thank_you')]
    public function thankYou(Request $request, ProductRepository $products): Response
    {
        $popularity = $products->getAll(
            page: $request->query->getInt('page', 1),
            size: $request->query->getInt('size', self::PER_PAGE),
        );
        return $this->render(
            'thank_you.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'popularity' => $popularity,
            ]
        );
    }
}
