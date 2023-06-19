<?php

declare(strict_types=1);

namespace App\Subscriber\Infrastructure\Controller\Api;

use App\Common\Infrastructure\Controller\ApiBaseController;
use App\Subscriber\Domain\Entity\Subscriber;
use App\Subscriber\Domain\Exception\SubscriberException;
use App\Subscriber\Domain\Repository\SubscriberRepository;
use Exception;
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
    public function subscribe(Request $request, SubscriberRepository $subscribers): Response
    {
        $data = json_decode($request->getContent(), true);

        try {
            $email = $data['email'];
            // Assert::email($email);

            /* if ($subscribers->findByEmail($email)!== null) {
                 throw new SubscriberException('Already subscribe.');
             }*/

            $subscriber = new Subscriber(email: (string)$email);
            $subscribers->save($subscriber, true);

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

    #[Route(path: '/{id}/unsubscribe', name: '.unsubscribe', methods: ['GET'])]
    public function unSubscribe(string $id, Request $request): Response
    {
        return $this->json([
            'message' => 'success',
            'code' => 200,
        ]);
    }
}
