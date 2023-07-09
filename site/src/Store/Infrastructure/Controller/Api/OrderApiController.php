<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api;

use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Infrastructure\Request\Api\CheckoutDto;
use App\Store\Infrastructure\Service\Cart\CartService;
use App\Store\Infrastructure\Service\Order\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/checkout', name: 'store.checkout.api')]
class OrderApiController extends AbstractController
{
    #[Route(path: '/', name: '.index', methods: ['POST'], format: 'json')]
    public function index(
        CartService $cartService,
        OrderService $orderService,
        #[MapRequestPayload] CheckoutDto $checkoutDto,
    ): Response {

        $cart = $cartService->getCurrentCart(
            $userId = $this->getUser()?->getId()
        );

        $order = $orderService->checkout($cart, $checkoutDto);

        return $this->json(
            [
                'redirect_url' => match($order->getPayment()->getMethod()) {
                    OrderPayment::PAYMENT_METHOD_COD => $this->generateUrl('store.checkout.finish'),
                    OrderPayment::PAYMENT_METHOD_ONLINE => $this->generateUrl('store.checkout.finish'),
                }
            ]
        );
    }
}
