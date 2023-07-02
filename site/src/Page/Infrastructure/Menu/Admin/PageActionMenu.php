<?php

declare(strict_types=1);

namespace App\Page\Infrastructure\Menu\Admin;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class PageActionMenu
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
                    'route' => 'page.admin.page.edit',
                    'routeParameters' => ['id' => $options['page_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');
        $menu
            ->addChild(
                'SEO',
                [
                    'route' => 'page.admin.page.seo.edit',
                    'routeParameters' => ['id' => $options['page_id']],
                ]
            )
            ->setAttribute('class', 'nav-item')
            ->setAttribute('data-bs-toggle', 'tabs')
            ->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}
