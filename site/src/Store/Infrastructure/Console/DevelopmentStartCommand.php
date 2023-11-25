<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Repository\ProductRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'development:start',
    description: 'Development start update dependence.',
    aliases: ['development:start'],
    hidden: false
)]
class DevelopmentStartCommand extends Command
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ProductRepository $products,
        private readonly Flusher $flusher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Генерация Ids категорий
        /** @var Category $item */
        foreach ($this->categories->getAll() as $item) {
            $item->generateIds();
            $this->flusher->flush();
        }

        // Генерация Ids товаров
        /** @var Product $item */
        foreach ($this->products->getAllIterator() as $item) {
            $item->setCategoriesIds();
            $this->flusher->flush();
        }

        return Command::SUCCESS;
    }
}
