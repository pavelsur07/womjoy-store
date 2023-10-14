<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Infrastructure\Form\Order\Admin\OrderFilter;
use App\Store\Infrastructure\Form\Order\Admin\OrderFilterForm;
use App\Store\Infrastructure\Form\Order\Admin\OrderStatusEditForm;
use App\Store\Infrastructure\Repository\OrderRepository;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public const PER_PAGE = 10;

    #[Route(path: '/admin/orders', name: 'store.order.admin.index')]
    public function index(Request $request, OrderRepository $orders): Response
    {
        $filter = new OrderFilter();

        $form = $this->createForm(OrderFilterForm::class, $filter);
        $form->handleRequest($request);

        return $this->render(
            'admin/store/order/index.html.twig',
            [
                'pagination'=> $orders->getAll(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                    filter: $filter,
                ),
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/admin/orders/{id}/show', name: 'store.order.admin.show')]
    public function show(string $id, Order $order, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            OrderStatusEditForm::class,
            [
                'status' => $order->getStatus(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $order->addStatus($data['status']);

                $flusher->flush();
                $this->addFlash('success', 'Success status changed.');
            } catch (DomainException $e) {
                $this->addFlash('danger', 'Error status not changed.');
            }

            return $this->redirectToRoute('store.order.admin.show', ['id' => $id]);
        }

        return $this->render(
            'admin/store/order/show.html.twig',
            [
                'order'=> $order,
                'form' =>$form->createView(),
            ]
        );
    }
}
