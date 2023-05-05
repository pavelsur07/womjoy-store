<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository;

use App\Matrix\Domain\Entity\Model;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Domain\Repository\ModelRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ModelRepository implements ModelRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Model> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Model::class);
    }

    public function get(int $id): Model
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix model.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Model $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Model $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
