<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\PromoCode;

class PromoCodeGenerator
{
    public static function generatePromoCode($length = 8): string
    {
        // Символы, которые могут быть использованы в промокоде
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Длина строки символов
        $charLength = \strlen($characters);

        // Инициализируем переменную для хранения сгенерированного промокода
        $promoCode = '';

        // Генерируем случайные символы
        for ($i = 0; $i < $length; ++$i) {
            // Выбираем случайный индекс из строки символов
            $randomIndex = random_int(0, $charLength - 1);

            // Добавляем символ с выбранным индексом к промокоду
            $promoCode .= $characters[$randomIndex];
        }

        // Возвращаем сгенерированный промокод
        return $promoCode;
    }
}
