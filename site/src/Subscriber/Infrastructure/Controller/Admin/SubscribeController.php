<?php

declare(strict_types=1);

namespace App\Subscriber\Infrastructure\Controller\Admin;

use App\Subscriber\Domain\Repository\SubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/subscriber', name: 'store.admin.subscriber')]
class SubscribeController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(SubscriberRepository $subscribes): Response
    {
        return $this->render(
            'admin/subscribe/index.html.twig',
            [
                'list' => $subscribes->list(),
            ]
        );
    }
}
