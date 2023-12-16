<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use App\Store\Infrastructure\Form\Order\Admin\OrderDeliveryTrackEditForm;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/orders/{order_id}/delivery', name: 'store.admin.order.delivery')]
class DeliveryController extends AbstractController
{
    #[Route(path: '/edit', name: '.edit')]
    public function edit(Request $request, OrderRepositoryInterface $orders, Flusher $flusher): Response
    {
        $orderId = $request->get('order_id');
        $order = $orders->get(new OrderId($orderId));

        $form = $this->createForm(
            OrderDeliveryTrackEditForm::class,
            [
                'trackId' => $order->getDelivery()->getExternalDeliveryNumber(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $order->getDelivery()->setExternalDeliveryNumber($data['trackId']);
                $order->statusDelivered();
                $flusher->flush();
                $this->addFlash('success', 'Success set Track Id and status changed.');
            } catch (Exception $e) {
                $this->addFlash('danger', 'Error status not changed. ' . $e->getMessage());
            }
        }

        return $this->render(
            'admin/store/order/delivery/edit.html.twig',
            [
                'order'=> $order,
                'form' => $form->createView(),
            ]
        );
    }
}
