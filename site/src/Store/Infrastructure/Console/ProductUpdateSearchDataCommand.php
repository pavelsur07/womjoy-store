<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'product:update-search-data',
    description: 'Update search data.',
    aliases: ['product:update-search-data'],
    hidden: false
)]
class ProductUpdateSearchDataCommand extends Command
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly Flusher $flusher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            /** @var Product $item */
            foreach ($this->products->getAllIterator() as $item) {
                $item->searchDataGenerate();
            }

            $this->flusher->flush();
        } catch (Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
