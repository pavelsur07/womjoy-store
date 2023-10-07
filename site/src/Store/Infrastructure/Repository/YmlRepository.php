<?php

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Yml\Yml;
use App\Matrix\Domain\Exception\MatrixException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class YmlRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Yml> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Yml::class);
    }

    public function get(int $id): Yml
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

    public function save(Yml $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Yml $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}