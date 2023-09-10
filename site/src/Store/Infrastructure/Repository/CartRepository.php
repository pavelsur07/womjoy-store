<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Cart\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CartRepository
{
    private PaginatorInterface $paginator;
    private EntityManagerInterface $em;

    /** @var EntityRepository<Cart> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Cart::class);
        $this->paginator = $paginator;
    }

    public function getAll(
        int $page,
        int $size,
    ): PaginationInterface {
        // return $this->repo->findAll();
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Cart::class, 'p');

        $qb->orderBy('p.id', 'ASC');

        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function findById(int|null $id, string $status = ''): ?Cart
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
