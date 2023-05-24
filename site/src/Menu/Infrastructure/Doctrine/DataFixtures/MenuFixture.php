<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Doctrine\DataFixtures;

use App\Menu\Domain\Entity\Menu;
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
                'href'=> '/',
            ],
            [
                'name'=> 'Брюки',
                'href'=> '/',
            ],
            [
                'name'=> 'Леггинсы',
                'href'=> '/',
            ],
            [
                'name'=> 'Топы',
                'href'=> '/',
            ],
            [
                'name'=> 'Рашгарды',
                'href'=> '/',
            ],
            [
                'name'=> 'Футболки',
                'href'=> '/',
            ],
            [
                'name'=> 'Худи',
                'href'=> '/',
            ],
            [
                'name'=> 'Шорты',
                'href'=> '/',
            ],
            [
                'name'=> 'Аксессуары',
                'href'=> '/',
            ],
            [
                'name'=> 'Распродажа',
                'href'=> '/',
            ],
            [
                'name'=> 'Новинки',
                'href'=> '/',
            ],
            [
                'name'=> 'Покупателям',
                'href'=> '/',
            ],
        ];
    }
}
