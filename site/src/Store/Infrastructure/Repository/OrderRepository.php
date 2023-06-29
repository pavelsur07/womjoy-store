<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Exception\StoreOrderException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class OrderRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Order> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Order::class);
    }

    public function getAll(): array
    {
        return $this->repo->findAll();
    }

    public function get(int $id): Order
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new StoreOrderException('Order not found');
        }
        return $object;
    }

    public function findOwn(int $id, int $customerId): Order|null
    {
        return $this->repo->findOneBy(
            [
                'id'=> $id,
                'customerId' => $customerId,
            ]
        );
    }

    public function save(Order $object, bool $flush = false): void
    {
        $this->em->persist($object);
        if ($flush) {
            $this->em->flush();
        }
    }
}
