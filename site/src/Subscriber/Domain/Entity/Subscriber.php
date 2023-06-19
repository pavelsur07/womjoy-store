<?php

declare(strict_types=1);

namespace App\Subscriber\Domain\Entity;

use App\Subscriber\Domain\Exception\SubscriberException;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`subscriber_subscribers`')]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;
    #[ORM\Column]
    private bool $isUnsubscribe = false;

    public function __construct(string $email)
    {
        Assert::email($email);
        $this->email = mb_strtolower($email);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isUnsubscribe(): bool
    {
        return $this->isUnsubscribe;
    }

    public function unsubscribe(): void
    {
        if ($this->isUnsubscribe) {
            throw new SubscriberException('Already unsubscribe.');
        }
        $this->isUnsubscribe = true;
    }
}
