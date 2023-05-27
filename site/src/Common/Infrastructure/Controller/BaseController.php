<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Controller;

use App\Menu\Domain\Entity\Menu;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use App\Store\Domain\Entity\Category\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public array $menu = [
        'top' => [],
        'header' => [],
        'footer' => [],
    ];

    public array $metaData =[
        'title' =>'Title base controller default',
        'description' => 'Description base controller default',
        'h1' => 'default',
        'index' => false,
    ];

    public function __construct(private readonly MenuRepositoryInterface $menus)
    {
        $headerMenu = $this->menus->findById(id: 1);
        if ($headerMenu === null) {
            $headerMenu = new Menu(name: 'Header menu', href: '/');
            $headerMenu->setRoot(1);
            $this->menus->save(object: $headerMenu, flush: true);

            $count = \count($headerMenu->getChildren());
            $item = new Menu(name: 'Home page', href: '/');
            $item->setParent($headerMenu);
            $item->setSort($count++);
            $this->menus->save($item, true);
        }

        $this->menu['header'] = $this->menus->menuTree($headerMenu);
    }

    public function setTitle(string $title): void
    {
        $this->metaData['title'] = $title;
    }

    public function setDescription(string $description): void
    {
        $this->metaData['description'] = $description;
    }

    public function setH1(string $h1): void
    {
        $this->metaData['h1'] = $h1;
    }

    public function breadcrumbsCategoryGenerate(Category $category, ?array $bread = null): array
    {
        $bread[] = [
            'name' =>$category->getName(),
            'slug' => $category->getSlug(),
        ];

        if ($category->getParent() !== null) {
            return $this->breadcrumbsCategoryGenerate($category->getParent(), $bread);
        }
        return array_reverse($bread);
    }
}
