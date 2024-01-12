<?php

declare(strict_types=1);

namespace App\Subscriber\Domain\Repository;

use App\Subscriber\Domain\Entity\Subscriber;
use App\Subscriber\Domain\Exception\SubscriberException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[] findAll()
 * @method Subscriber[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Subscriber> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Subscriber::class);
    }

    public function get(int $id): Subscriber
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new SubscriberException('Subscriber not found.');
        }
        return $object;
    }

    public function findByEmail(string $email): null|Subscriber
    {
        return $this->repo->findOneBy(
            [
                $email => mb_strtolower($email),
            ]
        );
    }

    public function findById(int $id): null|Subscriber
    {
        return $this->repo->find($id);
    }

    public function save(Subscriber $subscriber, bool $flusher = false): void
    {
        $this->em->persist($subscriber);
        if ($flusher) {
            $this->em->flush();
        }
    }

    public function remove(Subscriber $subscriber, bool $flusher = false): void
    {
        $this->em->remove($subscriber);
        if ($flusher) {
            $this->em->flush();
        }
    }
}
