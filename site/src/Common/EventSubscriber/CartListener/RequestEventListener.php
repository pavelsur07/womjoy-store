<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\CartListener;

use App\Common\Infrastructure\Helper\SessionHelper;
use App\Store\Infrastructure\Repository\CartRepository;
use App\Store\Infrastructure\Service\Cart\CartService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class RequestEventListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $session,
        private CartService $service,
        private CartRepository $cartRepository,
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $cartId = null;
        $request = $event->getRequest();

        // проверяем COOKIE яндекса и отсутствие метки в сессии
        if ($request->cookies->has('_ym_uid') && !$request->getSession()->has(SessionHelper::CLIENT_ID)) {
            // устанавливаем сессию с уникальным индификаторам клиента яднекса.
            $this->session->getSession()->set(SessionHelper::CLIENT_ID, $request->cookies->get('_ym_uid'));
        }

        // если есть id корзины
        if ($request->cookies->has(SessionHelper::CART_KEY)) {
            // получаем id корзины из cookie
            $cartId = (int)$request->cookies->get(SessionHelper::CART_KEY);

            // ищем корзину по ID
            $cart = $this->cartRepository->findById($cartId);

            if (!$cart) {
                // получаем текущий ID корзины
                $cartId = $this->service->getCurrentCart()->getId();

                // удаляем несуществующий ID корзины из cookie
                $request->cookies->remove(SessionHelper::CART_KEY);
            }

            // записываем id в сессию
            $this->session->getSession()->set(SessionHelper::CART_KEY, $cartId);

            return;
        }

        if ($cartId === null && !$request->getSession()->has(SessionHelper::CART_KEY)) {
            $cartId = $this->service->getCurrentCart()->getId();
        }

        if (!$request->getSession()->has(SessionHelper::CART_KEY)) {
            $this->session->getSession()->set(SessionHelper::CART_KEY, $cartId);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }
}
