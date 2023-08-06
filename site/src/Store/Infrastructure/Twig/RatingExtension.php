<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RatingExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('rating_star', [$this, 'rating'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function rating(Environment $twig, int $rating): string
    {
        return $twig->render('store/widget/common/rating.html.twig', [
            'rating' => $rating,
        ]);
    }
}
