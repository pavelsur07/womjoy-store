<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin;

use App\Store\Infrastructure\Console\SitemapGenerateCommand;
use App\Store\Infrastructure\Service\YandexMarket\YandexMarket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
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
