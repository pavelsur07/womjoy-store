<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Repository;

use App\Page\Domain\Entity\Page;
use App\Page\Domain\Exception\PageException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class PageRepository
{
    private EntityManagerInterface $em;

    /** @var EntityRepository<Page> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Page::class);
    }

    public function get(int $id): Page
    {
        $object = $this->repo->find($id);
        if ($object === null) {
            throw new PageException('Page not found.');
        }

        return $object;
    }

    public function getAll(): array
    {
        return $this->repo->findAll();
    }

    public function save(Page $object, bool $flush): void
    {
        $this->em->persist($object);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function remove(Page $object, bool $flush): void
    {
        $this->em->remove($object);

        if ($flush) {
            $this->em->flush();
        }
    }
}
