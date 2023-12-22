<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Message;

use App\Store\Infrastructure\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    public const PER_PAGE = 10;

    #[Route(path: '/admin/messages', name: 'store.messages.admin.index')]
    public function index(Request $request, MessageRepository $messages): Response
    {
        return $this->render(
            'admin/store/message/index.html.twig',
            [
                'pagination'=> $messages->getAll(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                ),
            ]
        );
    }
}
