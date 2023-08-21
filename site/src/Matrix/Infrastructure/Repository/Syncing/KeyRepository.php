<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository\Syncing;

use App\Matrix\Domain\Entity\Syncing\Key\Key;
use App\Matrix\Domain\Exception\MatrixException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class KeyRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Key> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Key::class);
    }

    public function get(int $id): Key
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix key.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Key $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Key $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
