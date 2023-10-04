<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductStatusExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('store_product_status', [$this, 'status'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function status(Environment $twig, string $status): string
    {
        return $twig->render('admin/widget/product/status.html.twig', [
            'status' => $status,
        ]);
    }
}
