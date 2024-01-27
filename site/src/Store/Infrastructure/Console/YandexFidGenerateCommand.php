<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console;

use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Domain\Entity\Yml\Item;
use App\Store\Domain\Entity\Yml\Yml;
use App\Store\Infrastructure\Repository\YmlRepository;
use App\Store\Infrastructure\Service\YandexMarket\YandexMarketGenerator;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'yandex:fid-generate',
    description: 'Yandex fid generate.',
    aliases: ['yandex:fid-generate'],
    hidden: false
)]
class YandexFidGenerateCommand extends Command
{
    public function __construct(
        private readonly YmlRepository $ymls,
        private readonly YandexMarketGenerator $generator,
        private readonly SettingService $service,
    ) {
        parent::__construct();
    }

    /**
     * @throws FilesystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->ymls->list();
        $items = [];

        /** @var Yml $value */
        foreach ($this->ymls->list() as $value) {
            $items[] = $value->getId();
        }

        $store = $this->service->get();

        $this->generator->setProperty(
            company: $store->getCompany()->getName(),
            name: $store->getStoreName(),
            url: $store->getStoreUrl(),
        );

        foreach ($items as $item) {
            $fid = $this->ymls->get($item);

            $categories = [];
            $products =[];

            /** @var Item $value */
            foreach ($fid->getItems() as $value) {
                $categories[$value->getProduct()->getMainCategory()->getId()] = $value->getProduct()->getMainCategory();
                if ($value->getProduct()->getStatus()->isActive()) {
                    $products[] = $value->getProduct();
                }
            }

            $this->generator->generate($categories, $products, $fid->getFileName());
        }

        return Command::SUCCESS;
    }
}
