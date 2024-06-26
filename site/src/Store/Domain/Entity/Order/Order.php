<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Order;

use App\Store\Domain\Entity\Order\ValueObject\ClientId;
use App\Store\Domain\Entity\Order\ValueObject\OrderAmoCRM;
use App\Store\Domain\Entity\Order\ValueObject\OrderCustomer;
use App\Store\Domain\Entity\Order\ValueObject\OrderDelivery;
use App\Store\Domain\Entity\Order\ValueObject\OrderEmailTrigger;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Entity\Order\ValueObject\OrderItemPrice;
use App\Store\Domain\Entity\Order\ValueObject\OrderMoysklad;
use App\Store\Domain\Entity\Order\ValueObject\OrderNumber;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
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
    #[ORM\Column(type: 'store_order_uuid')]
    private OrderId $id;

    #[ORM\Embedded(class: OrderNumber::class, columnPrefix: false)]
    private ?OrderNumber $orderNumber = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $completedAt = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $customerId;

    #[ORM\Embedded(class: OrderCustomer::class, columnPrefix: 'customer_')]
    private ?OrderCustomer $customer;

    #[ORM\Embedded(class: OrderDelivery::class, columnPrefix: 'delivery_')]
    private ?OrderDelivery $delivery;

    #[ORM\Embedded(class: OrderPayment::class, columnPrefix: 'payment_')]
    private ?OrderPayment $payment;

    #[ORM\Embedded(class: ClientId::class, columnPrefix: 'client_id_')]
    private ?ClientId $clientId = null;

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
    private ?string $cancelReason = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $ymUid = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $ymCounter = null;

    #[ORM\Embedded(class: OrderAmoCRM::class, columnPrefix: 'amo_')]
    private OrderAmoCRM $amoCRM;

    #[ORM\Embedded(class: OrderMoysklad::class, columnPrefix: 'moysklad_')]
    private OrderMoysklad $moysklad;

    #[ORM\Embedded(class: OrderEmailTrigger::class, columnPrefix: 'email_')]
    private ?OrderEmailTrigger $emailTrigger = null;

    public function __construct(
        OrderCustomer $customer,
        OrderDelivery $delivery,
        OrderPayment $payment,
        ?int $customerId = null,
    ) {
        $this->id = OrderId::generate();
        $this->customer = $customer;
        $this->delivery = $delivery;
        $this->payment = $payment;

        $this->customerId = $customerId;

        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();

        $this->items = new ArrayCollection();

        $this->addStatus(OrderStatus::NEW);

        $this->amoCRM = new OrderAmoCRM();
        $this->moysklad = new OrderMoysklad();
        $this->emailTrigger = new OrderEmailTrigger();
    }

    public function getEmailTrigger(): ?OrderEmailTrigger
    {
        return $this->emailTrigger;
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

    public function pay(): void
    {
        if ($this->isPaid()) {
            throw new StoreOrderException('Order is already paid.');
        }

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
        return $this->getCost() + $this->deliveryCost;
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

    public function getId(): OrderId
    {
        return $this->id;
    }

    public function getOrderNumber(): ?OrderNumber
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?OrderNumber $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
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

    public function getCustomer(): OrderCustomer
    {
        return $this->customer;
    }

    public function getDelivery(): OrderDelivery
    {
        return $this->delivery;
    }

    public function getPayment(): ?OrderPayment
    {
        return $this->payment;
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
        $result = 0;
        foreach ($this->items as $item) {
            $result = $result + $item->getPrice()->getSalePrice() * $item->getQuantity();
        }
        return $result;
    }

    public function getDeliveryCost(): int
    {
        return $this->deliveryCost;
    }

    public function getDiscount(): int
    {
        return 0;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getYmUid(): ?string
    {
        return $this->ymUid;
    }

    public function setYmUid(?string $ymUid): void
    {
        $this->ymUid = $ymUid;
    }

    public function getYmCounter(): ?string
    {
        return $this->ymCounter;
    }

    public function setYmCounter(?string $ymCounter): void
    {
        $this->ymCounter = $ymCounter;
    }

    public function getAmoCRM(): OrderAmoCRM
    {
        return $this->amoCRM;
    }

    public function getMoysklad(): OrderMoysklad
    {
        return $this->moysklad;
    }

    #[ORM\PreFlush]
    public function preFlush(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PostLoad()]
    public function checkEmbeds(): void
    {
        if ($this->orderNumber->isEmpty()) {
            $this->orderNumber = null;
        }
    }

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function isStatusDelivered(): bool
    {
        return $this->currentStatus === OrderStatus::DELIVERED;
    }

    public function statusDelivered(): void
    {
        if ($this->isStatusDelivered()) {
            throw new StoreOrderException('Already setting status delivered.');
        }
        $this->addStatus(OrderStatus::DELIVERED);
    }

    public function addStatus(string $status): void
    {
        Assert::oneOf($status, OrderStatus::list());
        $this->statuses[] = new OrderStatus($status, new DateTimeImmutable());
        $this->currentStatus = $status;
        $this->status = $status;
    }
}
