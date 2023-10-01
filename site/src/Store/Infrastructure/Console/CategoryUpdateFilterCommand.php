<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Store\Application\Command\Category\UpdateFilter\CategoryUpdateFilterCommand as Message;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'category:update-filter',
    description: 'Category update filter.',
    aliases: ['category:update-filter'],
    hidden: false
)]
class CategoryUpdateFilterCommand extends Command
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly MessageBusInterface $bus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var Category $item */
        foreach ($this->categories->getAllIterator() as $item) {
            $this->bus->dispatch(new Message($item->getId()));
        }

        return Command::SUCCESS;
    }
}
