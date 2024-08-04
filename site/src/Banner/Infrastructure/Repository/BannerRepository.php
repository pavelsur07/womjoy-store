<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Repository;

use App\Banner\Domain\Entity\Banner;
use App\Banner\Domain\Exception\BannerException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class BannerRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Banner> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Banner::class);
    }

    public function get(string $id): Banner
    {
        Assert::uuid($id);
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new BannerException('Banner not found.');
        }

        return $object;
    }

    public function getAll(): array
    {
        return $this->repo->findAll();
    }

    public function getRootAll(): array
    {
        $result = [];
        foreach ($this->repo->findAll() as $item) {
            $result[] = new BannerForChoice(label: $item->getName(), value: (string)$item->getId());
        }

        return $result;
    }

    public function save(Banner $object, bool $flush): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Banner $object, bool $flush): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
