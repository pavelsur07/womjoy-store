<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use App\Store\Infrastructure\Service\Payment\AlfaAcquiringClient;
use App\Store\Infrastructure\Service\Payment\PaymentProvider;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Voronkovich\SberbankAcquiring\OrderStatus;

#[AsCommand(
    name: 'store:alfa:check-status',
    description: 'Check orders awaiting payment status.',
    aliases: ['s:a:c'],
    hidden: false
)]
class AlfaCheckStatusCommand extends Command
{
    public function __construct(
        private readonly AlfaAcquiringClient $acquiringClient,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Flusher $flusher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orders = $this->orderRepository->getOrdersAwaitingPayment(
            PaymentProvider::PAYMENT_PROVIDER_ALFA
        );

        /** @var Order $order */
        foreach ($orders as $order) {
            try {
                $alfaOrderStatus = $this->acquiringClient->getOrderStatus(
                    $order->getPayment()->getTransactionId(),
                );
            } catch (Exception) {
                // @TODO что делать если ошибка от банка
                continue;
            }

            // Если статус открыт, то пропускаем проверку
            if ($alfaOrderStatus['orderStatus'] === OrderStatus::CREATED) {
                continue;
            }

            $status = match ($alfaOrderStatus['orderStatus']) {
                OrderStatus::APPROVED, OrderStatus::DEPOSITED => OrderPayment::PAYMENT_STATUS_SUCCEEDED,
                OrderStatus::REVERSED, OrderStatus::DECLINED, OrderStatus::REFUNDED => OrderPayment::PAYMENT_STATUS_CANCELLED,
            };

            if ($status === OrderPayment::PAYMENT_STATUS_SUCCEEDED) {
                $order->pay();
                $order->getPayment()->setStatusSucceeded();
            }

            if ($status === OrderPayment::PAYMENT_STATUS_CANCELLED) {
                $order->cancel(
                    $alfaOrderStatus['actionCodeDescription'] ?? 'Payment fail.'
                );
                $order->getPayment()->setStatusCancelled();
            }
        }

        $this->flusher->flush();

        return Command::SUCCESS;
    }
}
