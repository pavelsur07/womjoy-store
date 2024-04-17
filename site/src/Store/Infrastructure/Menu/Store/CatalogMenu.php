<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Menu\Store;

class CatalogMenu
{
    public function build(): array
    {
        return [
            [
                'name'=>'НОВИНКИ',
                'href' =>'https://womjoy.ru/collections/novinki-dlya-zhenshchin-115',
                'is_dropdown' => false,
                'is_list' => true,
                'items'=> [
                    [
                        'name' => 'НОВИНКИ',
                        'href' => 'https://womjoy.ru/collections/novinki-dlya-zhenshchin-115',
                    ],
                    [
                        'name' => 'СКОРО В ПРОДАЖЕ',
                        'href' => 'https://womjoy.ru/collections/skoro-v-prodazhe-118',
                    ],
                ],
            ],
            // -  Меню с вложенными элементами -
            [
                'name'=>'Каталог',
                'href' =>'https://womjoy.ru/collections/zhenskie-bryuki-108',
                'is_dropdown' => true,
                'is_list' => false,
                'items'=> [
                    [
                        'name' => 'Брюки',
                        'href' => '/collections/zhenskie-bryuki-108',
                    ],
                    [
                        'name' => 'Велосипедки',
                        'href' => '/collections/velosipedki-zhenskie-113',
                    ],
                    [
                        'name' => 'Лонгсливы',
                        'href' => 'https://womjoy.ru/collections/zhenskie-vodolazki-i-longslivy-142',
                    ],
                    [
                        'name' => 'Легинсы',
                        'href' => 'https://womjoy.ru/collections/zhenskie-leginsy-i-losiny-109',
                    ],
                    [
                        'name' => 'Майки',
                        'href' => 'https://womjoy.ru/collections/zhenskie-mayki-135',
                    ],
                    [
                        'name' => 'Халаты',
                        'href' => 'https://womjoy.ru/collections/halaty-zhenskie',
                    ],
                    [
                        'name' => 'Рубашки',
                        'href' => 'https://womjoy.ru/collections/rubashki-zhenskie-143',
                    ],
                    [
                        'name' => 'Толстовки и свитшоты',
                        'href' => 'https://womjoy.ru/collections/zhenskie-tolstovki-i-svitshot-139',
                    ],
                    [
                        'name' => 'Топы и кроп-топы',
                        'href' => 'https://womjoy.ru/collections/zhenskie-topy-136',
                    ],
                    [
                        'name' => 'Футболки',
                        'href' => 'https://womjoy.ru/collections/zhenskie-futbolki-134',
                    ],
                    [
                        'name' => 'Шорты',
                        'href' => 'https://womjoy.ru/collections/zhenskie-shorty-114',
                    ],
                ],
            ],
            // -  Меню список -
            [
                'name'=>'НОВИНКИ',
                'href' =>'https://womjoy.ru/collections/novinki-dlya-zhenshchin-115',
                'is_dropdown' => false,
                'is_list' => true,
                'items'=> [
                    [
                        'name' => 'ОДЕЖДА ДЛЯ ДОМА',
                        'href' => 'https://womjoy.ru/collections/odezhda-dlya-doma-121',
                    ],
                    [
                        'name' => 'ОДЕЖДА ДЛЯ СПОРТА',
                        'href' => 'https://womjoy.ru/collections/odezhda-dlya-sporta-120',
                    ],
                    [
                        'name' => 'Одежда для путешествий',
                        'href' => 'https://womjoy.ru/collections/odezhda-dlya-aktivnogo-otdyha',
                    ],
                    [
                        'name' => 'АКСЕССУАРЫ',
                        'href' => 'https://womjoy.ru/collections/odezhda-dlya-aktivnogo-otdyha',
                    ],
                ],
            ],
        ];
    }
}
