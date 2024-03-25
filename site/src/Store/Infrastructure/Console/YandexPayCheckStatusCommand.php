<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use App\Store\Infrastructure\Service\Payment\YandexPay\YandexPay;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'store:yandex-pay:check-status',
    description: 'Check orders awaiting payment status.',
    aliases: ['s:ya-pay:c'],
    hidden: false
)]
class YandexPayCheckStatusCommand extends Command
{
    public function __construct(
        private readonly YandexPay $yandexPay,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Flusher $flusher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orders = $this->orderRepository->getOrdersAwaitingPaymentYandexPay();

        /** @var Order $order */
        foreach ($orders as $order) {
            try {
                $yandexOrderResponse = $this->yandexPay->getOrder(
                    $order->getPayment()->getTransactionId(),
                );
            } catch (Exception) {
                // @TODO что делать если ошибка от банка
                continue;
            }

            $yandexOrder = $yandexOrderResponse->getOrder();

            // Если статус открыт, то пропускаем проверку
            if ($yandexOrder['paymentStatus'] === 'PENDING') {
                continue;
            }

            $status = match ($yandexOrder['paymentStatus']) {
                'CAPTURED' => OrderPayment::PAYMENT_STATUS_SUCCEEDED,
                'FAILED', 'VOIDED' => OrderPayment::PAYMENT_STATUS_CANCELLED,
            };

            if ($status === OrderPayment::PAYMENT_STATUS_SUCCEEDED) {
                $order->pay();
                $order->getPayment()->setStatusSucceeded();
            }

            if ($status === OrderPayment::PAYMENT_STATUS_CANCELLED) {
                $order->cancel(
                    $yandexOrder['reason'] ?? 'Payment fail.'
                );
                $order->getPayment()->setStatusCancelled();
            }
        }

        $this->flusher->flush();

        return Command::SUCCESS;
    }
}
