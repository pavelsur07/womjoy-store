<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy\Enum;

/**
 * @see https://docs.imgproxy.net/generating_the_url?id=resizing-type
 */
enum ResizingType: string
{
    /**
     * Изменяет размер изображения, сохраняя соотношение сторон в соответствии с заданным размером.
     */
    case Fit = 'fit';

    /**
     * Изменение размера изображения с сохранением соотношения сторон для заполнения заданного размера и обрезка выступающих частей.
     */
    case Fill = 'fill';

    /**
     * То же, что и fill, но если изображение с измененным размером меньше запрошенного размера, imgproxy обрежет результат, чтобы сохранить запрошенное соотношение сторон.
     */
    case FillDown = 'fill-down';

    /**
     * Изменяет размер изображения без сохранения соотношения сторон.
     */
    case Force = 'force';

    /**
     * Если исходный и результирующий размеры имеют одинаковую ориентацию (книжную или альбомную), imgproxy будет использовать fill. В противном случае он будет использовать fit.
     */
    case Auto = 'auto';
}
