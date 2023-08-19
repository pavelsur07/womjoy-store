<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin;

use App\Matrix\Infrastructure\Wildberries\HttpRequest;
use App\Store\Infrastructure\Console\SitemapGenerateCommand;
use App\Store\Infrastructure\Service\YandexMarket\YandexMarket;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard/wb', name: 'admin.dashboard.wb', methods: ['GET'])]
    public function getWb(): Response
    {
        $wb = new HttpRequest(
            baseUri: '',
            accessToken: 'Ups!',
        );

        $options = [
            'query' => [
                'dateFrom' => (new DateTimeImmutable('2023-06-20'))->format('Y-m-d\TH:i:sP'),
                'limit'=>100000,
                'dateTo' => (new DateTimeImmutable('2023-08-18'))->format('Y-m-d\TH:i:sP'),
            ],
        ];
        return $this->json($wb->get(
            url: 'https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod',
            options: $options,
        ));
    }

    #[Route('/admin/dashboard/', name: 'admin.dashboard.show', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('store/admin/dashboard/show.html.twig');
    }

    #[Route('/admin/dashboard/generate/yml', name: 'admin.dashboard.generate.yml', methods: ['GET'])]
    public function generateYml(YandexMarket $market): Response
    {
        $market->setProperty(company: 'WOMJOY', name: 'ВУМДЖОЙ ООО', url: 'https://womjoy.ru');
        $market->generate();
        return $this->redirectToRoute('admin.dashboard.show');
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/admin/dashboard/generate/sitemap', name: 'admin.dashboard.generate.sitemap', methods: ['GET'])]
    public function generateSitemap(SitemapGenerateCommand $command): Response
    {
        $command->run(
            new ArrayInput([]),
            new NullOutput()
        );
        return $this->redirectToRoute('admin.dashboard.show');
    }
}
