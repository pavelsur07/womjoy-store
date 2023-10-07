<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository;

use App\Matrix\Domain\Entity\Seller\Seller;
use App\Matrix\Domain\Exception\MatrixException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SellerRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Seller> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Seller::class);
    }

    public function get(int $id): Seller
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found seller model.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Seller $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Seller $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
