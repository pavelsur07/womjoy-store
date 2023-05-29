<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Repository;

use App\Guarantee\Domain\Entity\Guarantee;
use App\Guarantee\Domain\Exception\GuaranteeException;
use App\Guarantee\Domain\Repository\GuaranteeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

readonly class GuaranteeRepository implements GuaranteeRepositoryInterface
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Guarantee> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Guarantee::class);
    }

    public function get(int $id): Guarantee
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new GuaranteeException('Not found guarantee item.');
        }

        return $object;
    }

    public function list(): array
    {
        return $this->repo->findBy([], [
            'id' => 'DESC',
        ]);
    }

    public function save(Guarantee $object, bool $flush = false): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Guarantee $object, bool $flush = false): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
