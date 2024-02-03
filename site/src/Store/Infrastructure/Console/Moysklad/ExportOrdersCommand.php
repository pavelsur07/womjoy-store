<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console\Moysklad;

use App\Store\Infrastructure\Service\Moysklad\Moysklad;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'store:moysklad:export-orders',
    description: 'Exporting orders into moysklad',
    aliases: ['s:m:eo'],
    hidden: false
)]
class ExportOrdersCommand extends Command
{
    public function __construct(
        private readonly Moysklad $moysklad,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

//        try {
            $this->moysklad->exportOrdersToMoysklad();
//        } catch (RequestException $e) {
//            $io->error(
//                $e->getMessage()
//            );
//
//            return Command::FAILURE;
//        }

        return Command::SUCCESS;
    }
}
