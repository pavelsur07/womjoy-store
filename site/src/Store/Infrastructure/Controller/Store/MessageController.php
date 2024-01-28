<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Message\Message;
use App\Store\Domain\Entity\Message\ValueObject\MessageId;
use App\Store\Domain\Entity\Message\ValueObject\MessageTopic;
use App\Store\Infrastructure\Form\Message\MessageNewForm;
use App\Store\Infrastructure\Repository\MessageRepository;
use DateTimeImmutable;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends BaseController
{
    #[Route(path: '/about/contact/', name: 'store.message.contact.new')]
    public function message(
        Request $request,
        Flusher $flusher,
        MessageRepository $messages,
        string $googleRecaptchaSiteKey,
        string $googleRecaptchaSecret
    ): Response {
        $recaptcha = new ReCaptcha($googleRecaptchaSecret);
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        $messageId = $request->get('messageId');
        if ($messageId === null) {
            $message = null;
        } else {
            $message = 'message success';
        }

        $form = $this->createForm(MessageNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$resp->isSuccess()) {
                // Do something if the submit wasn't valid ! Use the message to show something
                $message = "The reCAPTCHA wasn't entered correctly. Go back and try it again.";
                $this->addFlash('danger', $message);
                return $this->redirectToRoute('store.message.contact.new', ['messageId' =>'error']);
            }

            $data = $form->getData();
            $newMessage = new Message(
                id: MessageId::generate(),
                createdAt: new DateTimeImmutable(),
                topic: new MessageTopic(MessageTopic::Return),
                name: $data['name'],
                email: $data['email'],
                phone: $data['phone'],
                message: $data['message']
            );
            $messages->save($newMessage);
            $flusher->flush();
            $this->addFlash('success', 'Message sending success.');
            return $this->redirectToRoute('store.message.contact.new', ['messageId' =>'success']);
        }
        return $this->render(
            'pion/store/message/new.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'form' => $form->createView(),
                'messageId' => $message,
                'site_key' => $googleRecaptchaSiteKey,
            ]
        );
    }
}
