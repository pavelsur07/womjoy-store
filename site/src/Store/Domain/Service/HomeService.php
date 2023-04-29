<?php

declare(strict_types=1);

namespace App\Store\Domain\Service;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Home\Home;
use App\Store\Domain\Repository\HomeSettingRepositoryInterface;

readonly class HomeService
{
    public function __construct(
        private HomeSettingRepositoryInterface $repo,
        private Flusher $flusher,
    ) {
    }

    public function get(): Home
    {
        $home = $this->repo->find();
        if ($home === null) {
            $home = new Home();
            $this->repo->save($home);
            $this->flusher->flush();
        }

        return $home;
    }
}
