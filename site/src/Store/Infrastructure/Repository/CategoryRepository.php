<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, 'App\Store\Domain\Entity\Category\Category');
    }

    public function get(int $id): Category
    {
        // TODO: Implement get() method.
    }

    public function save(Category $category): void
    {
        // TODO: Implement save() method.
    }

    public function remove(Category $category): void
    {
        // TODO: Implement remove() method.
    }
}
