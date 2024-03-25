<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Request;

use App\Store\Infrastructure\Service\Payment\YandexPay\Model\Cart\RenderedCart;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\MerchantRedirectUrls;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\OrderExtensions;
use App\Store\Infrastructure\Service\Payment\YandexPay\Model\RiskInfo;

class CreateOrderRequest extends AbstractRequest
{
    public const PAYMENT_METHOD_CARD = 'CARD';
    public const PAYMENT_METHOD_SPLIT = 'SPLIT';

    public const PREFERRED_PAYMENT_METHOD_FULLPAYMENT = 'FULLPAYMENT';
    public const PREFERRED_PAYMENT_METHOD_SPLIT = 'SPLIT';

    public const ORDER_SOURCE_WEBSITE = 'WEBSITE';
    public const ORDER_SOURCE_APP = 'APP';
    public const ORDER_SOURCE_CRM = 'CRM';
    public const ORDER_SOURCE_CASH_REGISTER = 'CASH_REGISTER';

    /**
     * Доступные методы оплаты на платежной форме Яндекс Пэй.
     *
     * Если вы интегрируете оплату только одним методом, например, Карта — указывается один метод ["CARD"]. Для платежей по банковским картам и через Сплит необходимо передать: ["CARD", "SPLIT"].
     * Enum: CARD, SPLIT
     *
     * @var string[]
     */
    private array $availablePaymentMethods = [
        self::PAYMENT_METHOD_CARD,
        self::PAYMENT_METHOD_SPLIT,
    ];

    /**
     * Корзина.
     */
    private RenderedCart $cart;

    /**
     * Трехбуквенный код валюты заказа (ISO 4217).
     *
     * Enum: RUB
     * Max length: 2048
     */
    private string $currencyCode = 'RUB';

    /**
     * Дополнительные параметры для оформления офлайн заказа.
     */
    private ?OrderExtensions $extensions = null;

    /**
     * Произвольные данные по заказу для внутреннего использования.
     *
     * Max length: 2048
     */
    private ?string $metadata = null;

    /**
     * Идентификатор заказа на стороне продавца (должен быть уникальным). Дальнейшее взаимодействие по заявке на оплату будет осуществляться с использованием этого идентификатора. Также данный идентификатор будет использоваться в сверках
     * Max length: 2048.
     */
    private string $orderId;

    /**
     * Поверхность на которой инициализировали создание заказа.
     *
     * Необходимо для последующей аналитики
     *
     * WEBSITE: Кнопка размещена на сайте. Ссылка на оплату сформировалась после действий (нажатия кнопки) пользователя на сайте
     *
     * APP: Кнопка размещена в мобильном приложении. Ссылка на оплату сформировалась после действий (нажатия кнопки) пользователя в приложении
     *
     * CRM: Ссылка на оплату сформирована менеджером в CRM или другой админке
     *
     * CASH_REGISTER: Ссылка на оплату сформирована для отображения на офлайн-кассе
     * Enum: WEBSITE, APP, CRM, CASH_REGISTER
     * Default: null
     */
    private ?string $orderSource = self::ORDER_SOURCE_WEBSITE;

    /**
     * Предпочтительный метод оплаты.
     *
     * Переданный метод будет автоматически выбран на форме оплаты, если это не противоречит доступным методам оплаты. По умолчанию - Карта.
     * Enum: FULLPAYMENT, SPLIT
     */
    private ?string $preferredPaymentMethod = null;

    /**
     * Назначение платежа
     * Max length: 128.
     */
    private ?string $purpose = null;

    /**
     * Ссылки для переадресации пользователя с формы оплаты. Обязательно для онлайн продавца.
     */
    private MerchantRedirectUrls $redirectUrls;

    /**
     * Дополнительная информация, наличие которой может увеличить вероятность одобрения по сплиту.
     */
    private ?RiskInfo $risk = null;

    /**
     * Время жизни заказа (в секундах).
     *
     * 180 <= ttl <= 604800
     * Default: 1800
     */
    private int $ttl = 1800;

    public function __construct(string $orderId, RenderedCart $cart, MerchantRedirectUrls $redirectUrls)
    {
        $this->orderId = $orderId;
        $this->cart = $cart;
        $this->redirectUrls = $redirectUrls;
    }

    public function getAvailablePaymentMethods(): array
    {
        return $this->availablePaymentMethods;
    }

    public function setAvailablePaymentMethods(array $availablePaymentMethods): self
    {
        $this->availablePaymentMethods = $availablePaymentMethods;

        return $this;
    }

    public function getCart(): RenderedCart
    {
        return $this->cart;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getExtensions(): ?OrderExtensions
    {
        return $this->extensions;
    }

    public function setExtensions(?OrderExtensions $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(?string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getOrderSource(): ?string
    {
        return $this->orderSource;
    }

    public function setOrderSource(?string $orderSource): self
    {
        $this->orderSource = $orderSource;

        return $this;
    }

    public function getPreferredPaymentMethod(): ?string
    {
        return $this->preferredPaymentMethod;
    }

    public function setPreferredPaymentMethod(?string $preferredPaymentMethod): self
    {
        $this->preferredPaymentMethod = $preferredPaymentMethod;

        return $this;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(?string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getRedirectUrls(): MerchantRedirectUrls
    {
        return $this->redirectUrls;
    }

    public function getRisk(): ?RiskInfo
    {
        return $this->risk;
    }

    public function setRisk(?RiskInfo $risk): self
    {
        $this->risk = $risk;

        return $this;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function setTtl(int $ttl): self
    {
        $this->ttl = $ttl;

        return $this;
    }
}
