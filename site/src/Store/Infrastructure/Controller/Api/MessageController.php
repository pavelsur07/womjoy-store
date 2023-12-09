<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/message', name: 'store.message.api')]
class MessageController extends AbstractController
{
    #[Route(path: '/new', name: '.new', methods: ['POST'], format: 'json')]
    public function new(Request $request): Response
    {
        return $this->json(
            [
                'message' => 'ok',
                'error' => null,
            ]
        );
    }
}
