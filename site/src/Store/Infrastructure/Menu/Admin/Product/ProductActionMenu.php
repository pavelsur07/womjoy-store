<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Menu\Admin\Product;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class ProductActionMenu
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
                    'route' => 'store.admin.product.edit',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Colors',
                [
                    'route' => 'store.admin.product.related_color.index',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Categories',
                [
                    'route' => 'store.admin.product.categories.index',
                    'routeParameters' => ['product_id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild(
                'SEO',
                [
                    'route' => 'store.admin.product.seo',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Images',
                [
                    'route' => 'store.admin.product.image.index',
                    'routeParameters' => ['product_id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Review',
                [
                    'route' => 'store.admin.product.review.index',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild(
                'Rating',
                [
                    'route' => 'store.admin.product.rating.edit',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild(
                'Related',
                [
                    'route' => 'store.admin.product.related_assignment.index',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Export',
                [
                    'route' => 'store.admin.product.export.edit',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild(
                'Attributes',
                [
                    'route' => 'store.admin.product.attribute.edit',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
