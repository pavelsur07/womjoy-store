<?php

declare(strict_types=1);

namespace App\Guarantee\Application\Command\New;

use App\Guarantee\Domain\Entity\Guarantee;
use App\Guarantee\Domain\Entity\ValueObject\GuaranteeService;
use App\Guarantee\Domain\Repository\GuaranteeRepositoryInterface;
use DateTimeImmutable;

final readonly class GuaranteeNewHandler
{
    public function __construct(
        private GuaranteeRepositoryInterface $guarantees,
    ) {}

    public function __invoke(GuaranteeNewCommand $command): void
    {
        $guarantee = new Guarantee(
            phone: $command->getPhone(),
            email: $command->getEmail(),
            message: $command->getMessage(),
            serviceName: new GuaranteeService($command->getService()),
            createdAt: new DateTimeImmutable(),
        );
        $this->guarantees->save($guarantee, true);
    }
}
