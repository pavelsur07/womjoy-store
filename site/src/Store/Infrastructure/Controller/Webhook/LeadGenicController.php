<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Webhook;

use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeDiscountType;
use App\Store\Infrastructure\Service\Promo\PromoCodeService;
use App\Subscriber\Domain\Entity\Subscriber;
use App\Subscriber\Domain\Repository\SubscriberRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeadGenicController extends AbstractController
{
    #[Route(path: '/endpoint/webhook/lead-genic', methods: ['POST'])]
    public function subscribe(Request $request, SubscriberRepository $subscribers, PromoCodeService $promoCodes): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $email = $data['email'];

            // Сохраняем подписчика к нам в базу
            $subscriber = new Subscriber(email: (string)$email);
            $subscribers->save($subscriber, true);

            // Генерируем промокод и сохраняем его в базу
            $code = $promoCodes->getPromoCode(discountValue: 10, discountType: PromoCodeDiscountType::PERCENT);
            $promoCodes->save();

            // Отправляем промокод письмом через сервис Unisender

            $message = 'success';
            $code = 201;
        } catch (Exception $e) {
            $message = 'Error - ' . $e->getMessage();
            $code = 400;
        }

        return $this->json(
            [
                'message'=> $message,
                'code' => $code,
            ]
        );
    }
}
