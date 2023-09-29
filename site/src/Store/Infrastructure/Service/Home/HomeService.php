<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Home;

use App\Store\Domain\Entity\Home\Home;
use App\Store\Domain\Entity\SeoMetadata;
use App\Store\Domain\Repository\HomeRepositoryInterface;

final readonly class HomeService
{
    public function __construct(
        private HomeRepositoryInterface $homes,
    ) {}

    public function get(): Home
    {
        $home = $this->homes->find();

        if ($home === null) {
            $home = $this->build();
        }

        return $home;
    }

    private function build(): Home
    {
        $home = new Home();
        $home->setSeoMetadata(
            new SeoMetadata(
                h1: 'H1 default',
                title: 'Title default',
                description: 'Description default',
                isIndexOn: false,
            )
        );
        $this->homes->save($home, true);
        return $home;
    }
}
