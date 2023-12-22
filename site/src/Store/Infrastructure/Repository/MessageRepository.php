<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Message\Message;
use App\Store\Domain\Entity\Message\ValueObject\MessageId;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class MessageRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Message> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, private PaginatorInterface $paginator)
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

    public function getAll(
        int $page,
        int $size,
    ): PaginationInterface {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Message::class, 'p');

        $qb->orderBy('p.createdAt', 'DESC');
        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
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
