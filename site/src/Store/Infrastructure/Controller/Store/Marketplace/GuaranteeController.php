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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class GuaranteeController extends BaseController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/marketplace/guarantee', name: 'store.marketplace.guarantee')]
    public function guarantee(
        Request $request,
        Flusher $flusher,
        MessageRepository $messages,
        MailerInterface $mailer,
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
                topic: new MessageTopic(MessageTopic::Substitution),
                name: $data['name'],
                email: $data['email'],
                phone: $data['phone'],
                message: $data['message']
            );
            $messages->save($newMessage);
            $flusher->flush();

            $email = (new TemplatedEmail())
                ->from('info@womjoy.ru')
                ->to(new Address($data['email']))
                ->subject('WOMJOY Инструкция по обмену товара ненадлежащего качества, купленого на маркетплейсе.')
                ->htmlTemplate('pion/email/store/guarantee/guarantee.html.twig')

                ->context([
                    'user' => $data['name'],
                ]);

            // TODO Настроить создание лида в АмоСРМ и отправка сообщения отвественному за гарантию

            $mailer->send($email);

            $this->addFlash('success', 'Message sending success.');
            return $this->redirectToRoute('store.marketplace.guarantee.thank_you');
        }
        return $this->render(
            'pion/store/guarantee/new.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'form' => $form->createView(),
                'messageId' => $message,
            ]
        );
    }

    #[Route(path: '/marketplace/guarantee/thank-you', name: 'store.marketplace.guarantee.thank_you')]
    public function thankYou(): Response
    {
        return $this->render(
            'pion/store/guarantee/thank_you.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
            ]
        );
    }
}
