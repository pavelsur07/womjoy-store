<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\AmoCRM\AmoCRMoAccessToken;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AmoCRMoAccessTokenRepository
{
    private EntityManagerInterface $em;
    /**
     * @var EntityRepository<AmoCRMoAccessToken>
     */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(AmoCRMoAccessToken::class);
    }

    public function find(): AmoCRMoAccessToken|null
    {
        return $this->repo->find(1);
    }

    public function save(AmoCRMoAccessToken $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
