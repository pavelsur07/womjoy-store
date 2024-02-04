<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api\Moysklad;

use App\Store\Infrastructure\Service\Moysklad\Moysklad;
use App\Store\Infrastructure\Service\Moysklad\MoyskladClient;
use Evgeek\Moysklad\Api\Record\Objects\Documents\CustomerOrder;
use Evgeek\Moysklad\Tools\Guid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route(path: '/api/v1/moysklad/webhook', name: 'store.api.moysklad.webhook.')]
class WebhookController extends AbstractController
{
    #[Route(path: '/register', name: 'register')]
    public function register(Request $request, Moysklad $moysklad): Response
    {
        $moysklad->createWebhookForUpdateCustomerOrder(
             $this->generateUrl('store.api.moysklad.webhook.order.update', [],UrlGeneratorInterface::ABSOLUTE_URL),
        );

        return $this->redirect(
            $request->headers->has('referer')
                ? $request->headers->get('referer')
                : $this->generateUrl('admin.dashboard.show')
        );
    }

    #[Route(path: '/order/update', name: 'order.update')]
    public function orderUpdate(Request $request, Moysklad $moysklad): Response
    {
        // Получаем содержимое webhook запроса
        $payloadInputBug = $request->getPayload();

        if($payloadInputBug->has('events')) {
            $events = $payloadInputBug->all('events');

            foreach ($events as $event) {
                $orderGuid = Guid::extractFirst($event['meta']['href']);

                if($orderGuid) {
                    $moysklad->updateOrderFromMoysklad($orderGuid);
                }
            }
        }

        return $this->json([]);
    }
}
