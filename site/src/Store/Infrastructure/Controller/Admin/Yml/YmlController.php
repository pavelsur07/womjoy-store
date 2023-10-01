<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Yml;

use App\Store\Domain\Entity\Yml\Yml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/yml', name: 'store.admin.yml')]
class YmlController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(): void {}

    #[Route(path: '/add/{id}', name: '.add')]
    public function add(int $id, Yml $yml, Request $request): void {}

    #[Route(path: '/remove/{id}', name: '.remove')]
    public function remove(int $id, Yml $yml, Request $request): void {}

    #[Route(path: '/active/{id}', name: '.active')]
    public function active(int $id, Yml $yml, Request $request): void {}

    #[Route(path: '/disable/{id}', name: '.disable')]
    public function disable(int $id, Yml $yml, Request $request): void {}
}
