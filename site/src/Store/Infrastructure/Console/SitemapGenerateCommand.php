<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\ProductRepository;
use App\Store\Infrastructure\Service\Sitemap\MapItem;
use App\Store\Infrastructure\Service\Sitemap\SitemapService;
use DateTimeImmutable;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'sitemap:generate',
    description: 'Reindex all cards & categories.',
    aliases: ['sitemap:generate'],
    hidden: false
)]
final class SitemapGenerateCommand extends Command
{
    public function __construct(
        private readonly SitemapService $service,
        private readonly ProductRepository $products,
        private readonly FileUploader $uploader,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
        parent::__construct();
    }

    /**
     * @throws FilesystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var Product $item */
        foreach ($this->products->getAllIterator() as $item) {
            $products[] = new MapItem(
                location: 'https://womjoy.ru' . $this->urlGenerator->generate(
                    'store.product.show',
                    ['slug'=> $item->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_PATH
                ),
                lastModified: new DateTimeImmutable(),
            );
        }
        /** @var MapItem[] $products */
        $map = $this->service->generateMap($products);
        $fileName = 'sitemap.xml';
        $this->uploader->write($map, $fileName);

        return Command::SUCCESS;
    }
}
