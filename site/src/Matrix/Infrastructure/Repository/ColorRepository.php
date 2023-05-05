<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository;

use App\Matrix\Domain\Entity\Color;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Domain\Repository\ColorRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ColorRepository implements ColorRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Color> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Color::class);
    }

    public function get(int $id): Color
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix color.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Color $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Color $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
