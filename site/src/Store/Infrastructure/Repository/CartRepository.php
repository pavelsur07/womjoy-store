<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Cart\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CartRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Cart> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Cart::class);
    }

    public function findById(int $id, string $status = ''): ?Cart
    {
        return $this->repo->findOneBy(
            [
                'id'=> $id,
            ]
        );
    }

    public function findByOwner(int $customerId): Cart|null
    {
        return $this->repo->findOneBy(
            [
                'customerId' => $customerId,
            ]
        );
    }

    public function save(Cart $cart, bool $flush = false): void
    {
        $this->em->persist($cart);
        if ($flush) {
            $this->em->flush();
        }
    }
}
