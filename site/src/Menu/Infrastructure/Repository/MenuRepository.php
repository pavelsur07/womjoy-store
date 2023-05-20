<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Repository;

use App\Menu\Domain\Entity\Menu;
use App\Menu\Domain\Exception\MenuException;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MenuRepository implements MenuRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Menu> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Menu::class);
    }

    public function get(int $id): Menu
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MenuException('Not found menu item.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findAll();
    }

    public function save(Menu $object, bool $flush): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Menu $object, bool $flush): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
