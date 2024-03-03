<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Promo\PromoCode;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeId;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class PromoCodeRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<PromoCode> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, private PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(PromoCode::class);
    }

    public function get(PromoCodeId $id): PromoCode
    {
        $object = $this->repo->find($id->value());
        if ($object === null) {
            throw new StoreProductException('Promo Code not found.');
        }
        return $object;
    }

    public function getAll(
        int $page,
        int $size,
    ): PaginationInterface {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(PromoCode::class, 'p');

        // $qb->orderBy('p.createdAt', 'DESC');
        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function findByPromoCode(string $code): null|PromoCode
    {
        $result = $this->repo->findBy(
            [
                'code' => $code,
            ]
        );

        if ($result === []) {
            return null;
        }

        return $result;
    }

    public function findById(PromoCodeId $id): null|PromoCode
    {
        return $this->repo->find($id->value());
    }

    public function save(PromoCode $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(PromoCode $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }
}
