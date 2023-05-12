<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Repository;

use App\Matrix\Domain\Entity\Product\Product;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProductRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Product> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Product::class);
    }

    public function get(int $id): Product
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MatrixException('Not found matrix product.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
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
