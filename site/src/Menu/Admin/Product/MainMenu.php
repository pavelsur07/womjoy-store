<?php

declare(strict_types=1);

namespace App\Menu\Admin\Product;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MainMenu
{
    public function __construct(private FactoryInterface $factory)
    {
    }

    public function build(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes(['class' => 'nav nav-tabs mb-4']);

        $menu
            ->addChild('Base', [
                'route' => 'admin.product.show',
                'routeParameters' => ['id' => $options['id']],
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        $menu
            ->addChild('Images', [
                'route' => 'admin.product.image.index',
                'routeParameters' => ['id' => $options['id']],
            ])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
