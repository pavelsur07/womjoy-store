<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\CartListener;

use App\Common\Infrastructure\Helper\SessionHelper;
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
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $cartId = null;

        if ($event->getRequest()->cookies->has(SessionHelper::CART_KEY)) {
            $cartId = (int)$event->getRequest()->cookies->get(SessionHelper::CART_KEY);
        }

        if (
            $cartId === null &&
            !$event->getRequest()->getSession()->has(SessionHelper::CART_KEY)
        ) {
            $cartId = $this->service->getCurrentCart()->getId();
        }

        if (!$event->getRequest()->getSession()->has(SessionHelper::CART_KEY)) {
            $this->session->getSession()->set(SessionHelper::CART_KEY, $cartId);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }
}
