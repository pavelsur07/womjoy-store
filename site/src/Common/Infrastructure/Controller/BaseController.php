<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Controller;

use App\Menu\Domain\Entity\Menu;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use App\Store\Domain\Entity\Category\Category;
use App\Store\Domain\Entity\Home\AssignCategory;
use App\Store\Domain\Entity\Home\Home;
use App\Store\Domain\Service\HomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseController extends AbstractController
{
    public array $menu = [
        'top' => [],
        'header' => [],
        'footer' => [],
        'categories' => [],
    ];

    public array $metaData =[
        'title' =>'Title base controller default',
        'description' => 'Description base controller default',
        'h1' => 'default',
        'index' => false,
    ];

    public function __construct(
        private readonly MenuRepositoryInterface $menus,
        private readonly HomeService $homeService,
        private readonly UrlGeneratorInterface $generator,
    ) {
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

        $home = $this->homeService->get();
        $this->menu['categories'] = $this->menuCategories($home);
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

    private function menuCategories(Home $home): array
    {
        $result = [];
        /** @var AssignCategory $category */
        foreach ($home->getCategories() as $category) {
            $result[] = [
                'name' => $category->getCategory()->getName(),
                'href' => $this->generator->generate('store.category.show', ['slug' => $category->getCategory()->getSlug()]),
                'imagePath' => $category->getCategory()->getImage()->getPath() . '/' . $category->getCategory()->getImage()->getName(),
            ];
        }

        return $result;
    }
}
