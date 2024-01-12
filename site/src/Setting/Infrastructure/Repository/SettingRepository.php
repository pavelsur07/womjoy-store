<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Repository;

use App\Setting\Domain\Entity\Setting;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SettingRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Setting> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Setting::class);
    }

    public function findById(int $id): null|Setting
    {
        return $this->repo->find($id);
    }

    public function save(Setting $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Setting $object, bool $flush): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
