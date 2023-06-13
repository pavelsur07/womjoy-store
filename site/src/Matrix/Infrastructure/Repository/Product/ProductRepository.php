<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository\Product;

use App\Matrix\Domain\Entity\Product\Product;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Domain\Repository\Product\ProductFilterInterface;
use App\Matrix\Domain\Repository\Product\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private PaginatorInterface $paginator;

    private EntityManagerInterface $em;

    /** @var EntityRepository<Product> */
    private EntityRepository $repo;

    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Product::class);
        $this->paginator = $paginator;
    }

    public function get(int $id): Product
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix product.');
        }

        return $object;
    }

    public function getAllIterable(): iterable
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');
        $qb->orderBy('p.id', 'ASC');

        return $qb->getQuery()->toIterable();
    }

    public function index(int $page, int $size, ProductFilterInterface $filter): PaginationInterface
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p');

        if ($filter->getStatus() !== null) {
            $qb
                ->andWhere('p.status.value = :status ')
                ->setParameter('status', $filter->getStatus());
        }

        if ($filter->getSubject() !== null) {
            $qb
                ->andWhere('p.subject = :subject ')
                ->setParameter('subject', $filter->getSubject());
        }

        if ($filter->getModel() !== null) {
            $qb
                ->andWhere('p.model = :model ')
                ->setParameter('model', $filter->getModel());
        }

        if ($filter->getColor() !== null) {
            $qb
                ->andWhere('p.color = :color ')
                ->setParameter('color', $filter->getColor());
        }

        if ($filter->getArticle() !== null) {
            $qb
                ->andWhere(
                    $qb->expr()->like('LOWER(p.article)', ':article')
                )
                ->setParameter('article', '%' . mb_strtolower($filter->getArticle()) . '%');
        }

        if ($filter->getName() !== null) {
            $qb
                ->andWhere(
                    $qb->expr()->like('LOWER(p.name)', ':name')
                )
                ->setParameter('name', '%' . mb_strtolower($filter->getName()) . '%');
        }

        $qb->orderBy('p.id', 'ASC');
        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function list(): array
    {
        return $this->repo->findBy([], ['id'=> 'ASC']);
    }

    public function save(Product $product, bool $flush = false): void
    {
        $this->em->persist($product);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Product $product, bool $flush = false): void
    {
        $this->em->remove($product);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function findByArticle(string $article): ?Product
    {
        return $this->repo->findOneBy(
            [
                'article'=> $article,
            ]
        );
    }
}
