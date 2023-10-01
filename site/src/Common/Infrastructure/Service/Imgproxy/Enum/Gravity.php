<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Service\Imgproxy\Enum;

/**
 * @see https://docs.imgproxy.net/generating_the_url?id=gravity
 */
enum Gravity: string
{
    case Smart = 'sm';
    case North = 'no';
    case South = 'so';
    case East = 'ea';
    case West = 'we';
    case NorthEast = 'noea';
    case NorthWest = 'nowe';
    case SouthEast = 'soea';
    case SouthWest = 'sowe';
    case Center = 'ce';
    case FocusPoint = 'fp';
}
