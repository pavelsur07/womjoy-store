<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Store\Marketplace;

use App\Common\Infrastructure\Controller\BaseController;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Domain\Entity\Message\Message;
use App\Store\Domain\Entity\Message\ValueObject\MessageId;
use App\Store\Domain\Entity\Message\ValueObject\MessageTopic;
use App\Store\Infrastructure\Form\Message\MessageMarketplaceNewForm;
use App\Store\Infrastructure\Repository\MessageRepository;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Client;
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
        SettingService $service,
    ): Response {
        $messageId = $request->get('messageId');
        if ($messageId === null) {
            $message = null;
        } else {
            $message = 'message success';
        }

        $setting = $service->get();

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
                message: ''
            );
            $messages->save($newMessage);
            $flusher->flush();

            /*if ($this->addEmailToListWithPromoCode(
                email: $data['phone'],
                promocode: 'TEST-PROMO',
                apiKey: $service->get()->getUnisender()->getKey()
            )) {
            }*/

           /* $email = (new TemplatedEmail())
                ->from('info@womjoy.ru')
                ->to(new Address($data['email']))
                ->subject('WOMJOY Инструкция по обмену товара ненадлежащего качества, купленого на маркетплейсе.')
                ->htmlTemplate('pion/email/store/guarantee/guarantee.html.twig')

                ->context([
                    'user' => $data['name'],
                ]);*/

            // TODO Настроить создание лида в АмоСРМ и отправка сообщения отвественному за гарантию

            $result = $this->subscribeToUnisender(
                apiKey: $service->get()->getUnisender()->getKey(),
                listId: ['354'],
                name: $data['name'],
                phone: $data['phone'],
                email: $data['email'],
            );

            /*
            service@womjoy.ru
            6DZ-XN6-WuH-5D5
            */

            $mailer->send($email);

            $this->addFlash('success', 'Message sending success.' . $result);
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

    public function subscribeToUnisender($apiKey, $listId, $name, $phone, $email): string
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
                'fields[Name]' => $name,
                'fields[phone]' => $phone,
                'double_optin' => 3,
            ],
        ]);

        $body = $response->getBody();
        return $body->getContents();
        /*} catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }*/
    }

    public function addEmailToListWithPromoCode($email, $promocode, string $apiKey): bool
    {
        // $apiKey = 'YOUR_API_KEY'; // Замените на свой API-ключ Unisender
        $listId = 250; // Замените на ID вашего списка рассылки в Unisender

        $client = new Client([
            'base_uri' => 'https://api.unisender.com/ru/api/',
        ]);

        try {
            $response = $client->post('subscribe', [
                'form_params' => [
                    'api_key' => $apiKey,
                    'format' => 'json',
                    'list_ids' => $listId,
                    'fields[email]' => $email,
                    'tags' => 'promocode', // Добавляем тег "promocode"
                    'tags_values' => $promocode, // Передаем значение переменной promocode
                    'double_optin' => 3,
                ],
            ]);

            $body = $response->getBody();
            $data = json_decode($body->getContents(), true);

            if ($data['result'] === 'error') {
                // Обработка ошибки добавления
                echo 'Ошибка добавления: ' . $data['message'];
            } else {
                // Успешно добавлено
                // echo 'Email успешно добавлен в список';
                return true;
            }
        } catch (Exception $e) {
            // Обработка исключений
            // echo 'Произошла ошибка: ' . $e->getMessage();
        }
        return false;
    }

    // Пример использования функции
    /*email = 'example@example.com'; // Замените на адрес электронной почты
    $promoCode = 'YOUR_PROMO_CODE'; // Замените на актуальный промокод
    addEmailToListWithPromoCode($email, $promoCode);*/

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
