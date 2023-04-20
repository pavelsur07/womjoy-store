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
            ->setChildrenAttributes(
                [
                    'class' => 'nav nav-tabs card-header-tabs',
                    'data-bs-toggle' => 'tabs',
                ]
            );

        $menu
            ->addChild(
                'Product identity',
                [
                    'route' => 'admin.product.show',
                    'routeParameters' => ['id' => $options['id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setLinkAttributes(['class'=>'nav-link', 'data-bs-toggle'=> 'tab']);

        $menu
            ->addChild(
                'Images',
                [
                    'route' => 'admin.product.image.index',
                    'routeParameters' => ['id' => $options['id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setLinkAttributes(['class'=>'nav-link', 'data-bs-toggle'=> 'tab']);

        $menu
            ->addChild(
                'Variants',
                [
                    'route' => 'admin.product.image.index',
                    'routeParameters' => ['id' => $options['id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setLinkAttributes(['class'=>'nav-link', 'data-bs-toggle'=> 'tab']);

        return $menu;
    }
}
