<?php

declare(strict_types=1);

namespace App\Store\Domain\Entity\Message;

use App\Store\Domain\Entity\Message\ValueObject\MessageId;
use App\Store\Domain\Entity\Message\ValueObject\MessageTopic;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'store_message')]
class Message
{
    #[ORM\Column(type: 'store_message_uuid')]
    #[ORM\Id]
    private MessageId $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Embedded(class: MessageTopic::class, columnPrefix: 'topic_')]
    private MessageTopic $topic;

    #[ORM\Column(type: 'string')]
    private string $name;
    #[ORM\Column(type: 'string')]
    private string $email;
    #[ORM\Column(type: 'string')]
    private string $phone;
    #[ORM\Column(type: 'text')]
    private string $message;

    public function __construct(
        MessageId $id,
        DateTimeImmutable $createdAt,
        MessageTopic $topic,
        string $name,
        string $email,
        string $phone,
        string $message
    ) {
        Assert::email($email);
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->topic = $topic;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
    }

    public function getId(): MessageId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getTopic(): MessageTopic
    {
        return $this->topic;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
