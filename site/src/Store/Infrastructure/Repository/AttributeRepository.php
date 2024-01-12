<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Attribute\Attribute;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;

class AttributeRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Attribute> */
    private EntityRepository $repo;

    public function __construct(
        private readonly PaginatorInterface $paginator,
        EntityManagerInterface $em
    ) {
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

    public function getAll(int $page, int $size, string $sort = 'name', string $direction = 'desc'): PaginationInterface
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Attribute::class, 'p');

        if (!\in_array($sort, ['name', 'id'], true)) {
            throw new UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy('p.' . $sort, $direction === 'desc' ? 'desc' : 'asc');

        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function hasBrandAttribute(): bool
    {
        $result = $this->repo->findBy(
            [
                'type'=> Attribute::TYPE_BRAND,
            ]
        );

        return $result !== null;
    }

    public function hasColorAttribute(): bool
    {
        $result = $this->repo->findBy(
            [
                'type'=> Attribute::TYPE_COLOR,
            ]
        );

        return $result !== null;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function findById(int $id): null|Attribute
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
