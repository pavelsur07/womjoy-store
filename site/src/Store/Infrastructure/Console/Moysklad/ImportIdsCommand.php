<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console\Moysklad;

use App\Store\Infrastructure\Service\Moysklad\Moysklad;
use Evgeek\Moysklad\Exceptions\RequestException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'store:moysklad:import-ids',
    description: 'Importing ID\'s from moysklad into a website',
    aliases: ['s:m:ii'],
    hidden: false
)]
class ImportIdsCommand extends Command
{
    public function __construct(
        private readonly Moysklad $moysklad,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->moysklad->loadAndUpdateMoyskladIds();
        } catch (RequestException $e) {
            $io->error(
                $e->getMessage()
            );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
