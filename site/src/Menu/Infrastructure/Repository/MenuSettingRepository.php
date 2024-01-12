<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Repository;

use App\Menu\Domain\Entity\MenuSetting;
use App\Menu\Domain\Exception\MenuException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class MenuSettingRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<MenuSetting> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(MenuSetting::class);
    }

    public function get(int $id): MenuSetting
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new MenuException('Not found menu itemSetting.');
        }

        return $object;
    }

    public function findById(int $id): null|MenuSetting
    {
        return $this->repo->find($id);
    }

    public function list(): array
    {
        return $this->repo->findBy(
            [
                'parent' => null,
            ]
        );
    }

    public function save(MenuSetting $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(MenuSetting $object, bool $flush): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
