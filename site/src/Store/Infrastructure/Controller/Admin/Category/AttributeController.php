<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Category;

use App\Store\Domain\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category/{id}/attributes', name: 'store.admin.category.attribute')]
class AttributeController extends AbstractController
{
    #[Route(path: '/', name: '.edit')]
    public function edit(Request $request, CategoryRepositoryInterface $categories): Response
    {
        $category = $categories->get((int)$request->get('id'));
        return $this->render(
            'admin/store/category/attribute/edit.html.twig',
            [
                'category' => $category,
            ]
        );
    }
}
