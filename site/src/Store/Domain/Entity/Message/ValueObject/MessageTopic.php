<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Message\ValueObject;

use App\Common\Domain\Entity\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
class MessageTopic extends StringValueObject
{
    public const Substitution = 'substitution';
    public const Return = 'return';
    public const PAY = 'pay';

    public const Promo = 'promo';

    /*
    'Замена' =>'Substitution',
    'Возврат' =>'return',
    'Оплата' =>'pay',
    'Информация о заказе' => 'Order information',
    'Доставка' => 'Delivery',
    'Информация об изделии' => 'Product information',
    'Отзыв' => 'Feedback',
    'Сотрудничество' => 'Collaboration',
    'Другое' => 'Another',*/
    #[ORM\Column(type: 'string', length: 20)]
    protected $value;

    public function __construct(string $value = '')
    {
        Assert::oneOf($value, self::list());
        parent::__construct($value);
    }

    public static function list(): array
    {
        return [
            self::PAY,
            self::Substitution,
            self::Return,
            self::Promo,
        ];
    }
}
