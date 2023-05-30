<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Doctrine\DataFixtures;

use App\Menu\Domain\Entity\Menu;
use App\Store\Infrastructure\Doctrine\DataFixtures\CategoryFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MenuFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $headerMenu = new Menu(name: 'Header menu', href: '/');
        $headerMenu->setRoot($rootId = 1);
        $manager->persist($headerMenu);
        $manager->flush();

        foreach (self::listItem() as $item) {
            $count = \count($headerMenu->getChildren());
            $item = new Menu(name: $item['name'], href: $item['href']);
            $item->setParent($headerMenu);
            $item->setSort($count++);
            $manager->persist($item);
        }

        $manager->flush();
    }

    public static function listItem(): array
    {
        return [
            [
                'name'=> 'Женщинам',
                'href'=> '/catalog/' . CategoryFixture::REFERENCE_WOMAN,
                'children'=> [
                    [
                        'name'=> 'Брюки',
                        'href'=> '/catalog/' . CategoryFixture::REFERENCE_PANTS,
                    ],
                    [
                        'name'=> 'Леггинсы',
                        'href'=> '/catalog/' . CategoryFixture::REFERENCE_LEGGINGS,
                    ],
                ],
            ],
            [
                'name'=> 'Брюки',
                'href'=> '/catalog/' . CategoryFixture::REFERENCE_PANTS,
                'children'=> null,
            ],
            [
                'name'=> 'Леггинсы',
                'href'=> '/catalog/' . CategoryFixture::REFERENCE_LEGGINGS,
                'children'=> null,
            ],
            [
                'name'=> 'Топы',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Рашгарды',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Футболки',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Худи',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Шорты',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Аксессуары',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Распродажа',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Новинки',
                'href'=> '/',
                'children'=> null,
            ],
            [
                'name'=> 'Покупателям',
                'href'=> '/',
                'children'=> null,
            ],
        ];
    }
}
