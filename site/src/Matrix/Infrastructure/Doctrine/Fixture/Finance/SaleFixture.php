<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Doctrine\Fixture\Finance;

use App\Matrix\Domain\Entity\Finance\Sale;
use App\Matrix\Domain\Entity\Finance\ValueObject\Service;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SaleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = new Sale(
            createdAt: new DateTimeImmutable(),
            saleAt: new DateTimeImmutable(),
            service: new Service(Service::WB),
            productIdentity: 'product-identity-1',
            sale: 10000,
            cost: 50000,
            transactionId: '001'
        );
        $manager->persist($object);

        $object = new Sale(
            createdAt: new DateTimeImmutable(),
            saleAt: new DateTimeImmutable(),
            service: new Service(Service::OZON),
            productIdentity: 'product-identity-2',
            sale: 20000,
            cost: 10000,
            transactionId: '002'
        );
        $manager->persist($object);

        $manager->flush();
    }
}
