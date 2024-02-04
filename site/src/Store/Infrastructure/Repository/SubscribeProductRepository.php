<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Product\SubscribeProduct;
use App\Store\Domain\Entity\Product\ValueObject\SubscribeProductId;
use App\Store\Domain\Entity\Product\Variant;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SubscribeProductRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<SubscribeProduct> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(SubscribeProduct::class);
    }

    public function get(SubscribeProductId $id): Variant
    {
        $object = $this->repo->find($id->value());
        if ($object === null) {
            throw new StoreProductException('Subscriber not found.');
        }

        return $object;
    }

    public function findById(SubscribeProductId $id): null|Variant
    {
        return $this->repo->find($id->value());
    }

    public function save(SubscribeProduct $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(SubscribeProduct $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}
