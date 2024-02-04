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
    name: 'store:moysklad:update-stocks',
    description: 'Update stocks from moysklad',
    aliases: ['s:m:us'],
    hidden: false
)]
class UpdateStocksCommand extends Command
{
    public function __construct(
        private readonly Moysklad $moysklad,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->moysklad->updateStocksFromMoysklad();

        return Command::SUCCESS;
    }
}
