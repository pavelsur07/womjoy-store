<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Menu\Admin\Order;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class OrderActionMenu
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
                'Order',
                [
                    'route' => 'store.order.admin.show',
                    'routeParameters' => ['id' => $options['order_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Delivery',
                [
                    'route' => 'store.admin.order.delivery.edit',
                    'routeParameters' => ['order_id' => $options['order_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'Amo CRM',
                [
                    'route' => 'store.admin.order.amo.edit',
                    'routeParameters' => ['order_id' => $options['order_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
