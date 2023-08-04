<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Menu\Admin\Attribute;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class AttributeActionMenu
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
                'Attribute',
                [
                    'route' => 'store.admin.attribute.edit',
                    'routeParameters' => ['id' => $options['id_attribute']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Variants',
                [
                    'route' => 'store.admin.attribute.variant.index',
                    'routeParameters' => ['id_attribute' => $options['id_attribute']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
