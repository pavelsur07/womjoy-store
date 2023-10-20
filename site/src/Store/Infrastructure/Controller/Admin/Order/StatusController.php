<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use DomainException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/orders/{order_id}/status', name: 'store.admin.order.status')]
class StatusController extends AbstractController
{
    #[Route(path: '/pay', name: '.pay')]
    public function pay(Request $request, OrderRepositoryInterface $orders, Flusher $flusher, MailerInterface $mailer): Response
    {
        $orderId = $request->get('order_id');
        try {
            $order = $orders->get(new OrderId($orderId));

            $order->pay();
            $flusher->flush();
            $this->addFlash('success', 'Success order pay.');

            $email = (new TemplatedEmail())
                ->from('info@womjoy.ru')
                ->to(new Address($order->getCustomer()->getEmail()))
                ->subject('Thanks for order!')

                // path of the Twig template to render
                ->htmlTemplate('default/store/email/new_order.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'user' => 'user name',
                ]);

            $mailer->send($email);
        } catch (DomainException $e) {
            $this->addFlash('danger', 'Error order pay - ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.order.admin.show', ['id'=> $orderId]);
    }

    #[Route(path: '/send', name: '.send')]
    public function send(Request $request, OrderRepositoryInterface $orders, Flusher $flusher, MailerInterface $mailer): Response
    {
        $orderId = $request->get('order_id');
        try {
            $order = $orders->get(new OrderId($orderId));

            $order->send();
            $flusher->flush();
            $this->addFlash('success', 'Success order pay.');

            $email = (new TemplatedEmail())
                ->from('info@womjoy.ru')
                ->to(new Address($order->getCustomer()->getEmail()))
                ->subject('Order is send!')

                // path of the Twig template to render
                ->htmlTemplate('default/store/email/send_order.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'user' => 'user name',
                ]);

            $mailer->send($email);
        } catch (DomainException $e) {
            $this->addFlash('danger', 'Error order send - ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.order.admin.show', ['id'=> $orderId]);
    }
}
