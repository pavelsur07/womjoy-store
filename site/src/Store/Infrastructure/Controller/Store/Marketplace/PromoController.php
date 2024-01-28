<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store\Marketplace;

use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Message\Message;
use App\Store\Domain\Entity\Message\ValueObject\MessageId;
use App\Store\Domain\Entity\Message\ValueObject\MessageTopic;
use App\Store\Infrastructure\Form\Message\MessageMarketplaceNewForm;
use App\Store\Infrastructure\Repository\MessageRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromoController extends BaseController
{
    #[Route(path: '/marketplace/promo/basic-gift-course', name: 'store.marketplace.gift_course')]
    public function promo(
        Request $request,
        Flusher $flusher,
        MessageRepository $messages,
    ): Response {
        $messageId = $request->get('messageId');
        if ($messageId === null) {
            $message = null;
        } else {
            $message = 'message success';
        }

        $form = $this->createForm(MessageMarketplaceNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newMessage = new Message(
                id: MessageId::generate(),
                createdAt: new DateTimeImmutable(),
                topic: new MessageTopic(MessageTopic::Promo),
                name: $data['name'],
                email: $data['email'],
                phone: $data['phone'],
                message: $data['message'] ?? '',
            );

            // TODO Регистрация + отправка письма с видео курсом + страница спасибо о получении видео курса
            $messages->save($newMessage);
            $flusher->flush();
            $this->addFlash('success', 'Message sending success.');
            return $this->redirectToRoute('store.marketplace.gift_course.thank_you');
        }
        return $this->render(
            'pion/store/promo/gift_course/show.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'form' => $form->createView(),
                'messageId' => $message,
            ]
        );
    }

    #[Route(path: '/marketplace/promo/thank-you', name: 'store.marketplace.gift_course.thank_you')]
    public function thankYou(): Response
    {
        return $this->render(
            'pion/store/promo/thank_you.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }
}
