<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

final readonly class Flusher
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
