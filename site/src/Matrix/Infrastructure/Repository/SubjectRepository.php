<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository;

use App\Matrix\Domain\Entity\Subject;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Domain\Repository\SubjectRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SubjectRepository implements SubjectRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Subject> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Subject::class);
    }

    public function get(int $id): Subject
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix subject.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Subject $subject, bool $flush = false): void
    {
        $this->em->persist($subject);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Subject $subject, bool $flush = false): void
    {
        $this->em->remove($subject);

        if ($flush) {
            $this->em->flush();
        }
    }
}
