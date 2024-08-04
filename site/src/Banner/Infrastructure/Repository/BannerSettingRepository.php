<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Repository;

use App\Banner\Domain\Entity\BannerSetting;
use App\Banner\Domain\Exception\BannerSettingException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BannerSettingRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<BannerSetting> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(BannerSetting::class);
    }

    public function get(int $id): BannerSetting
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new BannerSettingException('Not found menu setting.');
        }

        return $object;
    }

    public function findById(int $id): ?BannerSetting
    {
        return $this->repo->find($id);
    }

    public function save(BannerSetting $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(BannerSetting $object, bool $flush): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
