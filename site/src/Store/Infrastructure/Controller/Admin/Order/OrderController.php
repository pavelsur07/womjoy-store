<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use App\Store\Domain\Entity\Order\Order;
use App\Store\Infrastructure\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route(path: '/admin/orders', name: 'store.order.admin.index')]
    public function index(OrderRepository $orders): Response
    {
        return $this->render(
            'store/admin/order/index.html.twig',
            [
                'orders'=> $orders->getAll(),
            ]
        );
    }

    #[Route(path: '/admin/orders/{id}/show', name: 'store.order.admin.show')]
    public function show(int $id, Order $order): Response
    {
        return $this->render(
            'store/admin/order/show.html.twig',
            [
                'order'=> $order,
            ]
        );
    }
}
