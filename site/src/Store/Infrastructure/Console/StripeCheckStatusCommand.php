<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use App\Store\Infrastructure\Service\Payment\PaymentProvider;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'store:stripe:check-status',
    description: 'Check orders awaiting payment status.',
    aliases: ['s:s:c'],
    hidden: false
)]
class StripeCheckStatusCommand extends Command
{
    public function __construct(
        private readonly string $stripeApiKey,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Flusher $flusher,
    ) {
        Stripe::setApiKey($this->stripeApiKey);

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orders = $this->orderRepository->getOrdersAwaitingPayment(
            PaymentProvider::PAYMENT_PROVIDER_STRIPE
        );

        /** @var Order $order */
        foreach ($orders as $order) {
            $session = Session::retrieve(
                $order->getPayment()->getTransactionId()
            );

            // Если статус открыт, то пропускаем проверку
            if ($session->status === $session::STATUS_OPEN) {
                continue;
            }

            $status = match ($session->payment_status) {
                'paid' => OrderPayment::PAYMENT_STATUS_SUCCEEDED,
                'unpaid' => OrderPayment::PAYMENT_STATUS_CANCELLED,
            };

            if ($status === OrderPayment::PAYMENT_STATUS_SUCCEEDED) {
                $order->pay();
                $order->getPayment()->setStatusSucceeded();
            }

            if ($status === OrderPayment::PAYMENT_STATUS_CANCELLED) {
                $order->cancel('Payment fail.');
                $order->getPayment()->setStatusCancelled();
            }
        }

        $this->flusher->flush();

        return Command::SUCCESS;
    }
}
