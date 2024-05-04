<?php

declare(strict_types=1);

namespace App\Subscriber\Infrastructure\Controller\Api;

use App\Common\Infrastructure\Controller\ApiBaseController;
use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeDiscountType;
use App\Store\Infrastructure\Service\Promo\PromoCodeService;
use App\Subscriber\Domain\Entity\Subscriber;
use App\Subscriber\Domain\Exception\SubscriberException;
use App\Subscriber\Domain\Repository\SubscriberRepository;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

#[Route(path: '/api/subscriber', name: 'subscriber')]
class SubscribeController extends ApiBaseController
{
    #[Route(path: '/show', name: '.show')]
    public function show(): Response
    {
        return $this->json([
            'test' =>'ok',
        ]);
    }

    #[Route(path: '/', name: '.subscribe', methods: ['POST'])]
    public function subscribe(
        Request $request,
        SubscriberRepository $subscribers,
        SettingService $service,
        PromoCodeService $promoCodeService
    ): Response {
        $data = json_decode($request->getContent(), true);

        try {
            $email = $data['email'];
            // Assert::email($email);

            /* if ($subscribers->findByEmail($email)!== null) {
                 throw new SubscriberException('Already subscribe.');
             }*/

            // Регистрируем пользователя с уникальным е-майлом в базу
            $subscriber = new Subscriber(email: (string)$email);
            $subscribers->save($subscriber, true);

            // Регистрируем промокод
            $promoCode = $promoCodeService->getPromoCode(
                discountValue: 5,
                discountType: PromoCodeDiscountType::PERCENT
            );
            $promoCodeService->save();

            // Подрисываем пользователя на рассылку промокода
            $this->subscribeToUnisender(
                apiKey: $service->get()->getUnisender()->getKey(),
                listId: ['250'],
                email: $data['email'],
                promoCode: $promoCode->getCode(),
            );

            $message = 'success';
            $code = 201;
        } catch (Exception $e) {
            $message = 'Error - ' . $e->getMessage();
            $code = 400;
        }

        return $this->json(
            data: [
                'request' => $data['email'],
                'message' => $message,
                'code' => $code,
            ],
            status: $code
        );
    }

    public function subscribeToUnisender(string $apiKey, array $listId, string $email, string $promoCode): string
    {
        $client = new Client([
            'base_uri' => 'https://api.unisender.com/ru/api/',
            'timeout'  => 2.0,
        ]);

        // try {
        $response = $client->post('subscribe', [
            'form_params' => [
                'api_key' => $apiKey,
                'list_ids' => $listId,
                'fields[email]' => $email,
                'fields[Promocode]' => $promoCode,
                'double_optin' => 3,
            ],
        ]);

        $body = $response->getBody();
        return $body->getContents();
        /*} catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }*/
    }

    #[Route(path: '/{id}/unsubscribe', name: '.unsubscribe', methods: ['GET'])]
    public function unSubscribe(string $id, Request $request): Response
    {
        return $this->json([
            'message' => 'success',
            'code' => 200,
        ]);
    }
}
