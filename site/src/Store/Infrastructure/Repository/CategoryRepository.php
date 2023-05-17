<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class CategoryRepository implements CategoryRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Category> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Category::class);
    }

    public function get(int $id): Category
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new DomainException('Product not fount');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Category $category, bool $flush = false): void
    {
        $this->em->persist($category);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Category $category, $flush = false): void
    {
        $this->em->remove($category);

        if ($flush) {
            $this->em->flush();
        }
    }
}
