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
                'href' =>'/collections/novinki-dlya-zhenshchin-115',
                'is_dropdown' => false,
                'is_list' => true,
                'items'=> [
                    [
                        'name' => 'НОВИНКИ',
                        'href' => '/collections/novinki-dlya-zhenshchin-115',
                    ],
                    [
                        'name' => 'СКОРО В ПРОДАЖЕ',
                        'href' => '/collections/skoro-v-prodazhe-118',
                    ],
                ],
            ],
            // -  Меню с вложенными элементами -
            [
                'name'=>'Капсулы',
                'href' =>'#',
                'is_dropdown' => true,
                'is_list' => false,
                'items'=> [
                    [
                        'name' => 'Street and Home',
                        'href' => '/collections/kapsula-street-and-home-149',
                    ],
                    [
                        'name' => 'Black & White',
                        'href' => '/collections/kapsula-black-white-150',
                    ],
                    [
                        'name' => 'Pion',
                        'href' => '/collections/kapsula-pion-151',
                    ],
                    [
                        'name' => 'New line',
                        'href' => '/collections/kapsula-new-line-152',
                    ],
                ],
            ],
            [
                'name'=>'Каталог',
                'href' =>'/collections/zhenskaya-odezhda',
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
                        'href' => '/collections/zhenskie-vodolazki-i-longslivy-142',
                    ],
                    [
                        'name' => 'Легинсы',
                        'href' => '/collections/zhenskie-leginsy-i-losiny-109',
                    ],
                    [
                        'name' => 'Майки',
                        'href' => '/collections/zhenskie-mayki-135',
                    ],
                    [
                        'name' => 'Халаты',
                        'href' => '/collections/halaty-zhenskie',
                    ],
                    [
                        'name' => 'Рубашки',
                        'href' => '/collections/rubashki-zhenskie-143',
                    ],
                    [
                        'name' => 'Толстовки и свитшоты',
                        'href' => '/collections/zhenskie-tolstovki-i-svitshot-139',
                    ],
                    [
                        'name' => 'Топы и кроп-топы',
                        'href' => '/collections/zhenskie-topy-136',
                    ],
                    [
                        'name' => 'Футболки',
                        'href' => '/collections/zhenskie-futbolki-134',
                    ],
                    [
                        'name' => 'Шорты',
                        'href' => '/collections/zhenskie-shorty-114',
                    ],
                ],
            ],
            // -  Меню список -
            [
                'name'=>'НОВИНКИ',
                'href' =>'/collections/novinki-dlya-zhenshchin-115',
                'is_dropdown' => false,
                'is_list' => true,
                'items'=> [
                    [
                        'name' => 'ОДЕЖДА ДЛЯ ДОМА',
                        'href' => '/collections/odezhda-dlya-doma-121',
                    ],
                    [
                        'name' => 'ОДЕЖДА ДЛЯ СПОРТА',
                        'href' => '/collections/odezhda-dlya-sporta-120',
                    ],
                    [
                        'name' => 'Одежда для активного отдыха',
                        'href' => '/collections/odezhda-dlya-aktivnogo-otdyha',
                    ],
                    /*[
                        'name' => 'АКСЕССУАРЫ',
                        'href' => '/collections/odezhda-dlya-aktivnogo-otdyha',
                    ],*/
                ],
            ],
        ];
    }
}
