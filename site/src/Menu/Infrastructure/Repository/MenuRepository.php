<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Repository;

use App\Menu\Domain\Entity\Menu;
use App\Menu\Domain\Exception\MenuException;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MenuRepository implements MenuRepositoryInterface
{
    private EntityManagerInterface $em;
    private Connection $connection;

    /** @var EntityRepository<Menu> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, Connection $connection)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Menu::class);
        $this->connection = $connection;
    }

    public function get(int $id): Menu
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MenuException('Not found menu item.');
        }

        return $object;
    }

    public function findById(int $id): Menu|null
    {
        return $this->repo->find($id);
    }

    /**
     * @throws Exception
     */
    public function menuTree(Menu $menu): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select([
                'p.id',
                'p.root',
                'p.sort',
                'p.name',
                'p.parent_id',
                'p.href',
            ])
            ->from('menu_menus', 'p')
            ->where('p.root = :rootId')
            ->setParameter('rootId', $menu->getId())
            ->orderBy('sort', 'asc');

        return $this->buildTree($qb->fetchAllAssociative())[0]['children'];
    }

    public function list(): array
    {
        return $this->repo->findBy(
            [
                'parent' => null,
            ]
        );
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

    private function buildTree(array $elements, ?int $parentId = null): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] === $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }
        return $branch;
    }
}
