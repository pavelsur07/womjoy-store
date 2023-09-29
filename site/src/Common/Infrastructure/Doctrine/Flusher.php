<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

final readonly class Flusher
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function flush(): void
    {
        $this->em->flush();
    }
}
