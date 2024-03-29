<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Menu;

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
                    'route' => 'matrix.admin.product.edit',
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
                    'route' => 'matrix.admin.product.image.index',
                    'routeParameters' => ['product_id' => $options['product_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
