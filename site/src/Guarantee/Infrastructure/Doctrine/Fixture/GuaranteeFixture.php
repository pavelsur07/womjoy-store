<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Doctrine\Fixture;

use App\Common\Infrastructure\Helper\ExternalService;
use App\Guarantee\Domain\Entity\Guarantee;
use App\Guarantee\Domain\Entity\ValueObject\GuaranteeService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GuaranteeFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = new Guarantee(
            phone: '+78889999990',
            email: '1-example@mail.com',
            message: 'new message',
            serviceName: new GuaranteeService(ExternalService::WB),
            createdAt: new DateTimeImmutable(),
        );
        $manager->persist($object);

        $object = new Guarantee(
            phone: '+78889999991',
            email: '2-example@mail.com',
            message: 'new message',
            serviceName: new GuaranteeService(ExternalService::WB),
            createdAt: new DateTimeImmutable(),
        );
        $manager->persist($object);

        $manager->flush();
    }
}
