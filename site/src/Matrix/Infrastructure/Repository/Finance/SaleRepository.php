<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository\Finance;

use App\Matrix\Domain\Entity\Finance\Sale;
use App\Matrix\Domain\Exception\MatrixException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SaleRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Sale> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Sale::class);
    }

    public function get(int $id): Sale
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found sale.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Sale $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Sale $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
