<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\AttributeAssignment;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Entity\Product\ValueObject\ProductStatus;
use App\Store\Infrastructure\Form\Product\Admin\ProductFilter;
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

    public function findByArticle(string $article): ?Product
    {
        return $this->repo->findOneBy(['article' => $article]);
    }

    public function getAll(
        int $page,
        int $size,
        ?ProductFilter $filter,
        string $sort = 'createdAt',
        string $direction = 'desc',
        ?string $status = null
    ): PaginationInterface {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');

        if ($status !== null) {
            $qb->andWhere('p.status.value = :status_value');
            $qb->setParameter('status_value', $status);
        }

        if ($filter->getName() !== null) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(p.name)', ':name')
            );
            $qb->setParameter('name', '%' . mb_strtolower($filter->getName()) . '%');
        }

        if ($filter->getArticle() !== null) {
            $qb->andWhere(
                $qb->expr()->like('LOWER(p.article)', ':article')
            );
            $qb->setParameter('article', '%' . mb_strtolower($filter->getArticle()) . '%');
        }

        if (!\in_array($sort, ['createdAt', 'id'], true)) {
            throw new UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy('p.' . $sort, $direction === 'desc' ? 'desc' : 'asc');
        $qb->addOrderBy('p.id', 'DESC');

        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function search(string $filter): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');

        $qb
            ->andWhere('p.status.value = :status ')
            ->setParameter('status', ProductStatus::ACTIVE);

        $qb
            ->andWhere(
                $qb->expr()->like('LOWER(p.searchData)', ':name')
            )
            ->setParameter('name', '%' . mb_strtolower($filter) . '%');

        $qb->orderBy('p.id', 'DESC');
        $qb->getQuery();

        // return $this->paginator->paginate($qb, $page, $size);
        return $qb;
    }

    public function getAllIterator(): iterable
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');
        $qb->orderBy('p.id', 'ASC');

        return $qb->getQuery()->toIterable();
    }

    public function listByCategoryQueryBuilder(
        Category $category,
        ?string $sort,
        ?string $direction = 'asc',
        array $filterIds = []
    ): QueryBuilder {
        $expr = $this->em->getExpressionBuilder();

        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('c.category', 'cc')
            ->andWhere(
                $expr->orX(
                    $expr->like('p.categoriesIds', ':ids'),
                    $expr->in('cc.id', explode('/', $category->getIds()))
                )
            )
            ->setParameter('ids', $category->getIds() . '%')
            ->andWhere('p.status.value = :status_value')
            ->setParameter('status_value', ProductStatus::ACTIVE)
            ->groupBy('p.id');

        if (\count($filterIds) > 0) {
            // Добавить фильтрацию товаров по характеристикам
            foreach ($filterIds as $attributeId => $variantIds) {
                // создаём алиас для подзапроса
                $alias = \sprintf('attr_%d', $attributeId);

                $filterQb = $this->em->createQueryBuilder()->select('IDENTITY(' . $alias . '.product)')
                    ->from(AttributeAssignment::class, $alias);

                $filterQb->where(
                    $expr->eq($alias . '.attribute', $attributeId)
                );
                $filterQb->andWhere(
                    $expr->in($alias . '.variant', $variantIds)
                );

                $qb->andWhere(
                    $expr->in('p.id', $filterQb->getDQL())
                );
            }
        }

        if ($sort) {
            $qb->orderBy($sort, $direction);
        }

        if ($sort === 'popularity') {
            $qb->orderBy('p.popularity', 'ASC');
        }

        // $qb->addOrderBy('p.id', 'DESC');

        return $qb;
    }

    /*
    public function listBySearchPhraseSearchPhrase(
        string $searchPhrase,
        ?string $sort,
        ?string $direction = 'asc'
    ) :QueryBuilder {
    }
    */

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
