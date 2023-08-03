<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Attribute\Attribute;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AttributeRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Attribute> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Attribute::class);
    }

    public function get(int $id): Attribute
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new StoreProductException('Attribute not found.');
        }
        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function findById(int $id): Attribute|null
    {
        return $this->repo->find($id);
    }

    public function save(Attribute $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Attribute $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}
