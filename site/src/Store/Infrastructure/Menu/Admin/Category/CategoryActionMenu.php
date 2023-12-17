<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Menu\Admin\Category;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class CategoryActionMenu
{
    private FactoryInterface $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function build(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav nav-tabs card-header-tabs']);

        $menu
            ->addChild(
                'Properties',
                [
                    'route' => 'store.admin.category.edit',
                    'routeParameters' => ['id' => $options['category_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'SEO',
                [
                    'route' => 'store.admin.category.seo',
                    'routeParameters' => ['id' => $options['category_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Image',
                [
                    'route' => 'store.admin.category.image',
                    'routeParameters' => ['id' => $options['category_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild(
                'Templates',
                [
                    'route' => 'store.admin.category.template.edit',
                    'routeParameters' => ['id' => $options['category_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Attributes',
                [
                    'route' => 'store.admin.category.attribute.edit',
                    'routeParameters' => ['id' => $options['category_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Sub menu',
                [
                    'route' => 'store.admin.category.menu.index',
                    'routeParameters' => ['id' => $options['category_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
