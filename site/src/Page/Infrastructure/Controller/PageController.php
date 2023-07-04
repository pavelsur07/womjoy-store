<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Controller;

use App\Common\Infrastructure\Controller\BaseController;
use App\Page\Domain\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/pages', name: 'page')]
class PageController extends BaseController
{
    #[Route(path: '/{slug}', name: '.page.show')]
    public function show(string $slug, Page $page): Response
    {
        $this->setTitle($page->getSeoTitle());
        $this->setDescription($page->getSeoDescription());
        return $this->render(
            'page/page/show.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'page'=>$page,
            ]
        );
    }
}
