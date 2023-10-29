<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends BaseController
{
    #[Route(path: '/_error/404', name: 'not.found')]
    public function show(Request $request): Response
    {
        return $this->render(
            "$this->template/common/error/404.html.twig",
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }
}
