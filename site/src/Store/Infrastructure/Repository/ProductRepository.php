<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use DomainException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository
{
    private PaginatorInterface $paginator;
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<Product>
     */
    private EntityRepository $repo;

    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->repo = $em->getRepository(Product::class);
    }

    public function get(int $id): Product
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new DomainException('Product not fount');
        }

        return $object;
    }

    public function getAll(int $page, int $size, string $sort = 'createdAt', string $direction = 'desc', ?string $status = null): PaginationInterface
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');

        if ($status !== null) {
            $qb->andWhere('p.status.value = :status_value');
            $qb->setParameter('status_value', $status);
        }

        if (!\in_array($sort, ['createdAt', 'id'], true)) {
            throw new UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy('p.' . $sort, $direction === 'desc' ? 'desc' : 'asc');

        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function getAllIterator(): iterable
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');
        $qb->orderBy('p.id', 'ASC');

        return $qb->getQuery()->toIterable();
    }

    public function listByCategoryQueryBuilder(Category $category, ?string $sort, ?string $direction = 'asc', array $filterIds = []): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->andWhere('p.categoriesIds LIKE :ids')
            ->setParameter('ids', $category->getIds() . '%')
            ->andWhere('p.status.value = :status_value')
            ->setParameter('status_value', ProductStatus::ACTIVE);

        if (\count($filterIds) > 0) {
            // Добавить фильтрацию товаров по характеристикам
        }

        if ($sort) {
            $qb->orderBy($sort, $direction);
        }

        $qb->addOrderBy('p.id', 'DESC');

        return $qb;
    }

    public function listByCategoryWithPagination(Category $category, int $page, int $size): PaginationInterface
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->andWhere('p.categoriesIds LIKE :ids')
            ->setParameter('ids', $category->getIds() . '%')
            ->andWhere('p.status.value = :status_value')
            ->setParameter('status_value', ProductStatus::ACTIVE);

        $qb->orderBy('p.id', 'ASC');
        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
