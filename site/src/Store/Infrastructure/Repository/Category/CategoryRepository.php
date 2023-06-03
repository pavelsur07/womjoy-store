<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository\Category;

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
            throw new DomainException('Category not fount - ' . $id);
        }

        return $object;
    }

    public function getAll(): array
    {
        return $this->repo->findAll();
    }

    public function getCategoryTree(): array
    {
        $categories = [];

        foreach ($this->getRootNodes() as $category) {
            $categories = array_merge($categories, $this->normalizer($category));
        }
        return $categories;
    }

    public function getRootNodes(): array
    {
        return $this->repo->findBy(['parent' => null]);
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

    private function normalizer(Category $category, int $depth = 0): array
    {
        $categories = [
            new CategoryForChoice(
                label: sprintf('%s %s', str_repeat('—', $depth), $category->getName()),
                value: (string)$category->getId(),
            ),
        ];

        if ($category->getChildren()) {
            foreach ($category->getChildren() as $child) {
                $categories = array_merge($categories, $this->normalizer($child, $depth + 1));
            }
        }
        return $categories;
    }
}
