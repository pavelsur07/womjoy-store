<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Controller;

use App\Common\Domain\Exception\ValidationException;
use App\Common\Infrastructure\Controller\BaseController;
use App\Guarantee\Application\Command\New\GuaranteeNewCommand;
use App\Guarantee\Application\Command\New\GuaranteeNewHandler;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GuaranteeController extends BaseController
{
    public const PER_PAGE = 8;

    #[Route(path: '/guarantee/new', name: 'guarantee.new', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render(
            'guarantee/show.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }

    #[Route(path: '/guarantee/create', name: 'guarantee.create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator, GuaranteeNewHandler $handler): Response
    {
        $command = new GuaranteeNewCommand(
            message: $request->get('message'),
            service: $request->get('service'),
            email: $request->get('email'),
            phone: $request->get('phone'),
            isConfirmed: (bool)$request->get('is_confirm'),
            isSubscribe: (bool)$request->get('is_subscribe'),
        );

        $violations = $validator->validate($command);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $handler($command);

        return $this->redirectToRoute('guarantee.thank_you');
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
