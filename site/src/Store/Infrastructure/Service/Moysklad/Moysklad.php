<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Moysklad;

use App\Store\Domain\Entity\Order\OrderItem;
use App\Store\Infrastructure\Repository\OrderRepository;
use App\Store\Infrastructure\Repository\VariantRepository;
use Evgeek\Moysklad\Api\Record\Objects\Documents\CustomerOrder;
use Evgeek\Moysklad\Api\Record\Objects\Entities\Assortment;
use Evgeek\Moysklad\Api\Record\Objects\Entities\Counterparty;
use Evgeek\Moysklad\Api\Record\Objects\Entities\Product;
use Evgeek\Moysklad\Api\Record\Objects\Nested\State;
use Evgeek\Moysklad\Exceptions\RequestException;
use Evgeek\Moysklad\Tools\Meta;

readonly class Moysklad
{
    public function __construct(
        private MoyskladClient $moyskladClient,
        private OrderRepository $orderRepository,
        private VariantRepository $variantRepository,
        private MoyskladOrganization $moyskladOrganization,
        private MoyskladStore $moyskladStore,
    ) {}

    /**
     * @throws RequestException
     */
    public function loadAndUpdateMoyskladIds(): void
    {
        // Получаем инициализированный клиент
        $moyskladClient = $this->moyskladClient->get();

        // Подготавливаем SDK для получения всей коллекции
        $productCollection = Product::collection($moyskladClient)->filter('article', '!=', '')->get();

        // Создаём обработчк который будет обновлять локальные товары
        $handleUpdateProductVariant = function ($row): void {
            // $product->id, $product->article, $product->barcodes,

            foreach ($row->barcodes as $barcodes) {
                if (!isset($barcodes->code128)) {
                    continue;
                }

                $this->variantRepository->updateMoyskladId($row->article, $barcodes->code128, $row->id);
            }
        };

        // each result
        $productCollection->eachGenerator($handleUpdateProductVariant);
    }

    public function exportOrdersToMoysklad(): void
    {
        // Получаем инициализированный клиент
        $moyskladClient = $this->moyskladClient->get();

        // получаем оплаченные заказы н
        $orders = $this->orderRepository->getOrdersPaidNotCreatedInMoysklad();

        foreach ($orders as $order) {
            $customer = $order->getCustomer();

            try {
                $counterparty = $moyskladClient->query()->entity()
                    ->counterparty()->search($customer->getEmail())->limit(1)->get();

                // если нет не одного контрагент, создаём
                if ($counterparty->meta->size === 0) {
                    $name = sprintf('%s %s', $customer->getLastName(), $customer->getFirstName());

                    $counterparty = Counterparty::make($moyskladClient, [
                        'name' => $name,
                        'email' => $customer->getEmail(),
                        'phone' => $customer->getPhone(),
                    ])->create();
                } else {
                    $counterparty = array_shift($counterparty->rows);
                }

                $positions = [];

                /** @var OrderItem $orderItem */
                foreach ($order->getItems() as $orderItem) {
                    $positions[] = [
                        'quantity' => $orderItem->getQuantity(),
                        'reserve' => $orderItem->getQuantity(),
                        'price' => $orderItem->getPrice()->getSalePrice(),
                        'assortment' => [
                            'meta' => Meta::product(
                                $orderItem->getProductVariant()->getMoyskladId()
                            ),
                        ],
                    ];
                }

                $customerOrder = CustomerOrder::make($moyskladClient, [
                    'name' => (string)$order->getOrderNumber()->value(),
                    'organization' => [
                        'meta' => Meta::organization($this->moyskladOrganization->get()),
                    ],
                    'agent' => [
                        'meta' => Meta::counterparty($counterparty->id),
                    ],
                    'positions' => $positions,
                    'state' => [
                        'meta' => Meta::state('customerorder', 'af22ee11-1786-11ef-0a80-07e3001bb246')
                    ],
                    'store' => [
                        'meta' => Meta::store($this->moyskladStore->get()),
                    ],
                ]);

                $customerOrder->create();
            } catch (RequestException $e) {
                /**
                 * @TODO Добавить логирование в случае ошибки
                 */
                continue;
            }

            // успешно создали заказ в moysklad
            $order->getMoysklad()->created();

            // записываем ID заказа из moysklad
            $order->getMoysklad()->setId($customerOrder->id);

            // сохраняем флаг
            $this->orderRepository->save($order, true);
        }
    }

    public function updateStocksFromMoysklad(): void
    {
        // Получаем инициализированный клиент
        $moyskladClient = $this->moyskladClient->get();

        // Подготавливаем SDK для получения всей коллекции
        $assortmentCollection = Assortment::collection($moyskladClient)->get();

        // Создаём обработчк который будет обновлять остатки
        $handleUpdateAssortment = function ($assortment): void {
            $this->variantRepository->updateQuantityFromMoysklad($assortment->id, (int)$assortment->quantity);
        };

        // each result
        $assortmentCollection->eachGenerator($handleUpdateAssortment);
    }

    public function createWebhookForUpdateCustomerOrder(string $url): void
    {
        // Получаем инициализированный клиент
        $moyskladClient = $this->moyskladClient->get();

        //        // получение списка webhook
        //        dump($moyskladClient->query()->entity()->webhook()->get()); die;
        //        // удаление webhook по id
        //        $moyskladClient->query()->entity()->webhook()->byId('38da1c8f-c370-11ee-0a80-08e0007c95f7')->delete(); die;

        $moyskladClient->query()->entity()->webhook()->create([
            'url' => $url,
            'action' => 'UPDATE',
            'entityType' => CustomerOrder::TYPE,
        ]);
    }

    public function updateOrderFromMoysklad(string $guid): void
    {
        // Получаем инициализированный клиент
        $moyskladClient = $this->moyskladClient->get();

        $customerOrder = $moyskladClient->query()->entity()
            ->customerorder()->byId($guid)->expand('state')->get();

        // получаем Заказ по ID МойСклад
        $order = $this->orderRepository->getOrderByMoyskladId($customerOrder->id);

        if ($order) {
            // @todo: Тут необходимо написать реализацию маппинга статусов заказа из Moysklad в статусы сайта
            // @todo: $customerOrder->state->name === 'Выполнен'

            //            if ($customerOrder->state->name === 'Выполнен') {
            //                $order->complete();
            //            }
        }

        $this->orderRepository->save($order, true);
    }
}
