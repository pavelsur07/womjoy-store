<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Console\Syncing\Wildberries;

use App\Matrix\Application\Command\Syncing\Wildberries\ReportDetailByPeriodCreateSyncingTask;
use App\Matrix\Domain\Entity\Syncing\Key\Key;
use App\Matrix\Infrastructure\Repository\Syncing\KeyRepository;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'matrix:wildberries:syncing-report-detail',
    description: 'Syncing Report period detail by period.',
    hidden: false
)]
class ReportDetailByPeriodSyncingCommand extends Command
{
    public function __construct(
        private MessageBusInterface $bus,
        private KeyRepository $keys,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Key $key */
        foreach ($this->keys->list() as $key) {
            $event = new ReportDetailByPeriodCreateSyncingTask(
                keyId: $key->getId(),
                keyValue: $key->getWildberriesKey()->getValue(),
                dateFrom: new DateTimeImmutable(),
                dateTo: new DateTimeImmutable(),
            );

            $this->bus->dispatch($event);
        }

        return Command::SUCCESS;
    }
}
