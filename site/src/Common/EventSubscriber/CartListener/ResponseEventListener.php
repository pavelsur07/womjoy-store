<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\CartListener;

use App\Common\Infrastructure\Helper\SessionHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class ResponseEventListener implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $session
    ) {}

    public function onKernelResponse(ResponseEvent $event): void
    {
        // Проверяем установленнна кука с таким значением если нет то устанавливаем
        if (!$event->getRequest()->cookies->has(SessionHelper::CART_KEY)) {
            $response = $event->getResponse();
            $response->headers->setCookie(
                new Cookie(
                    name: SessionHelper::CART_KEY,
                    value: (string)$this->session->getSession()->get(SessionHelper::CART_KEY),
                    expire: time() + 36000*360,
                )
            );

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'onKernelResponse'];
    }
}
