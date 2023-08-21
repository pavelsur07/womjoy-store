<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository\Syncing;

use App\Matrix\Domain\Entity\Syncing\Wildberries\ReportDetailByPeriod;
use App\Matrix\Domain\Exception\MatrixException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ReportDetailByPeriodRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<ReportDetailByPeriod> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, private PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(ReportDetailByPeriod::class);
    }

    public function get(int $id): ReportDetailByPeriod
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix ReportDetailByPeriod.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function getAll(int $page, int $size, string $sort = 'createdAt', string $direction = 'desc'): PaginationInterface
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(ReportDetailByPeriod::class, 'p');

        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function save(ReportDetailByPeriod $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(ReportDetailByPeriod $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
