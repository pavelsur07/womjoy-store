<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends BaseController
{
    #[Route(path: '/search', name: 'search')]
    public function search(Request $request): Response
    {
        return $this->render(
            "{$this->template}/store/search/index.html.twig",
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'referer' => $request->headers->get('referer'),
                'param'=> $request->get('param'),
            ]
        );
    }
}
