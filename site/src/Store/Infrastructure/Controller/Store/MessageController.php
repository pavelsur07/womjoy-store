<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use App\Store\Infrastructure\Form\Message\MessageNewForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends BaseController
{
    #[Route(path: '/about/contact/', name: 'store.message.contact.new')]
    public function message(Request $request): Response
    {
        $messageId = $request->get('messageId');
        if ($messageId === null) {
            $message = null;
        } else {
            $message = 'message success';
        }

        $form = $this->createForm(MessageNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('store.message.contact.new', ['messageId' =>'success']);
        }
        return $this->render(
            'pion/store/message/new.html.twig',
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'form' => $form->createView(),
                'messageId' => $message,
            ]
        );
    }
}
