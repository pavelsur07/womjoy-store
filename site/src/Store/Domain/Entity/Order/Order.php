<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order;

use App\Store\Domain\Entity\Order\ValueObject\ClientId;
use App\Store\Domain\Entity\Order\ValueObject\CustomerData;
use App\Store\Domain\Entity\Order\ValueObject\DeliveryData;
use App\Store\Domain\Entity\Order\ValueObject\OrderItemPrice;
use App\Store\Domain\Entity\Order\ValueObject\OrderStatus;
use App\Store\Domain\Entity\Order\ValueObject\ProductData;
use App\Store\Domain\Entity\Product\Variant;
use App\Store\Domain\Exception\StoreOrderException;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`store_orders`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'integer')]
    private int $customerId;

    #[ORM\Embedded(class: CustomerData::class, columnPrefix: 'customer_')]
    private CustomerData $customerData;

    #[ORM\Embedded(class: DeliveryData::class, columnPrefix: 'delivery_')]
    private DeliveryData $deliveryData;

    #[ORM\Embedded(class: ClientId::class, columnPrefix: 'client_id_')]
    private ClientId|null $clientId = null;

    /** @var ArrayCollection<array-key, OrderItem> */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderItem::class, cascade: ['ALL'], orphanRemoval: true)]
    private Collection $items;

    #[ORM\Column(type: 'json')]
    private array $statuses;
    #[ORM\Column(type: 'string')]
    private string $currentStatus;

    #[ORM\Column]
    private int $cost = 0;

    #[ORM\Column]
    private int $deliveryCost = 0;

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $paymentMethod = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $cancelReason = null;

    public function __construct(
        int $customerId,
        DateTimeImmutable $createdAt,
        CustomerData $customerData,
    ) {
        $this->customerId = $customerId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $createdAt;
        $this->customerData = $customerData;

        $this->items = new ArrayCollection();
        $this->addStatus(OrderStatus::NEW);
    }

    public function addItem(Variant $variant, int $quantity): void
    {
        for ($i = 1; $i <= $quantity; ++$i) {
            $this->items->add(
                new OrderItem(
                    order: $this,
                    productVariant: $variant,
                    productData: new ProductData(
                        name: $variant->getProduct()->getName(),
                        article: $variant->getArticle(),
                        barcode: $variant->getArticle(),
                    ),
                    price: new OrderItemPrice(
                        salePrice: $variant->getProduct()->getPrice()->getListPrice(),
                        currency: 'RUB',
                        currencyRate: 1,
                    ),
                    quantity: 1
                )
            );
        }
    }

    public function pay(string $method): void
    {
        if ($this->isPaid()) {
            throw new StoreOrderException('Order is already paid.');
        }
        $this->paymentMethod = $method;
        $this->addStatus(OrderStatus::PAID);
    }

    public function send(): void
    {
        if ($this->isSent()) {
            throw new StoreOrderException('Order is already sent.');
        }
        $this->addStatus(OrderStatus::SENT);
    }

    public function complete(): void
    {
        if ($this->isCompleted()) {
            throw new StoreOrderException('Order is already completed.');
        }
        $this->addStatus(OrderStatus::COMPLETED);
    }

    public function cancel(string $reason): void
    {
        if ($this->isCancelled()) {
            throw new StoreOrderException('Order is already cancelled.');
        }
        $this->cancelReason = $reason;
        $this->addStatus(OrderStatus::CANCELLED);
    }

    public function getTotalCost(): int
    {
        return $this->cost + $this->deliveryCost;
    }

    public function canBePaid(): bool
    {
        return $this->isNew();
    }

    public function isNew(): bool
    {
        return $this->currentStatus === OrderStatus::NEW;
    }

    public function isPaid(): bool
    {
        return $this->currentStatus === OrderStatus::PAID;
    }

    public function isSent(): bool
    {
        return $this->currentStatus === OrderStatus::SENT;
    }

    public function isCompleted(): bool
    {
        return $this->currentStatus === OrderStatus::COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->currentStatus === OrderStatus::CANCELLED;
    }

    public function isCancelledByCustomer(): bool
    {
        return $this->currentStatus === OrderStatus::CANCELLED_BY_CUSTOMER;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getCustomerData(): CustomerData
    {
        return $this->customerData;
    }

    public function getDeliveryData(): DeliveryData
    {
        return $this->deliveryData;
    }

    public function getClientId(): ?ClientId
    {
        return $this->clientId;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getDeliveryCost(): int
    {
        return $this->deliveryCost;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function preFlush(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    private function addStatus(string $status): void
    {
        Assert::oneOf($status, OrderStatus::list());
        $this->statuses[] = new OrderStatus($status, new DateTimeImmutable());
        $this->currentStatus = $status;
    }
}
