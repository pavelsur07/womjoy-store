<?php

declare(strict_types=1);

namespace App\Store\Application\SendMail\Order;

use App\Store\Domain\Exception\StoreOrderException;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

readonly class OrderNewSendMailHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private OrderRepositoryInterface $orders,
    ) {}

    /**
     * @throws TransportExceptionInterface
     */
    #[AsMessageHandler]
    public function __invoke(OrderNewSendMailCommand $command): void
    {
        $order = $this->orders->find($command->getOrderUuid());

        if ($order === null) {
            throw new StoreOrderException('Order not found!');
        }

        $email = (new TemplatedEmail())
            ->from('info@womjoy.ru')
            ->to(new Address($order->getCustomer()->getEmail()))
            ->subject('Thanks for order!')

            // path of the Twig template to render
            ->htmlTemplate('pion/email/store/order/order_new.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'user' => $order->getCustomer()->getName(),
            ]);

        $this->mailer->send($email);
    }
}
