<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Webhook;

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
    public function subscribe(Request $request, SubscriberRepository $subscribers): Response
    {
        $data = json_decode($request->getContent(), true);
        try {
            $email = $data['email'];

            $subscriber = new Subscriber(email: (string)$email);
            $subscribers->save($subscriber, true);

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
