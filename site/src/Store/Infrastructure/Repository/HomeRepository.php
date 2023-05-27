<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Home\Home;
use App\Store\Domain\Repository\HomeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class HomeRepository implements HomeRepositoryInterface
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<Home>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Home::class);
    }

    public function find(): Home|null
    {
        return $this->repo->find(1);
    }

    public function save(Home $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
