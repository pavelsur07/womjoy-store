<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Message\Message;
use App\Store\Domain\Entity\Message\ValueObject\MessageId;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MessageRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Message> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Message::class);
    }

    public function get(MessageId $id): Message
    {
        $object = $this->repo->find($id->value());
        if ($object === null) {
            throw new StoreProductException('Message not found.');
        }
        return $object;
    }

    public function findById(MessageId $id): Message|null
    {
        return $this->repo->find($id->value());
    }

    public function save(Message $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}
