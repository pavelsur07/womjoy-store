<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Application\Command\Category\UpdateFilter\CategoryUpdateFilterCommand;
use App\Store\Domain\Entity\Category\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category/filters', name: 'store.admin.category.filter')]
class CategoryFilterController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    #[Route(path: '/{id}/update', name: '.update')]
    public function update(int $id, Category $category, Request $request, Flusher $flusher): Response
    {
        $this->bus->dispatch(new CategoryUpdateFilterCommand($category->getId()));

        return $this->redirectToRoute('store.admin.category.edit', ['id' => $id]);
    }
}
