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
                'Rating',
                [
                    'route' => 'store.admin.product.rating.edit',
                    'routeParameters' => ['id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
