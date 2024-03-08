<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Product\Variant;
use App\Store\Domain\Exception\StoreProductException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class VariantRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Variant> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Variant::class);
    }

    public function get(int $id): Variant
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new StoreProductException('Variant not found.');
        }

        return $object;
    }

    public function findById(int $id): ?Variant
    {
        return $this->repo->find($id);
    }

    public function updateMoyskladId(string $article, string $barcode, ?string $moyskladId): void
    {
        $qb = $this->repo->createQueryBuilder('variant');

        // build update query
        $qb->update()->set('variant.moyskladId', ':moysklad_id')
            ->andWhere(
                $qb->expr()->eq('variant.article', ':article')
            )
            ->andWhere(
                $qb->expr()->eq('variant.barcode', ':barcode')
            );

        $qb
            ->setParameter('moysklad_id', $moyskladId)
            ->setParameter('article', $article)
            ->setParameter('barcode', $barcode);

        $qb->getQuery()->execute();
    }

    public function updateQuantityFromMoysklad(string $moyskladId, int $quantity = 0): void
    {
        $qb = $this->repo->createQueryBuilder('variant');

        // build update query
        $qb->update()->set('variant.quantity', ':quantity')
            ->where(
                $qb->expr()->eq('variant.moyskladId', ':moysklad_id')
            );

        $qb
            ->setParameter('moysklad_id', $moyskladId)
            ->setParameter('quantity', $quantity);

        $qb->getQuery()->execute();
    }

    public function save(Variant $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Variant $entity, bool $flush = false): void
    {
        $this->em->remove($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    //    /**
    //     * @return Variant[] Returns an array of Variant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Variant
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
