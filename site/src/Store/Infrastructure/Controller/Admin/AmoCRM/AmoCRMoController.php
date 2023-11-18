<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\AmoCRM;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\AmoCRM\AmoCRMoAccessTokenEditForm;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMoAccessTokenStorage;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/amo-crm', name: 'store.admin.amo')]
class AmoCRMoController extends AbstractController
{
    #[Route(path: '/edit', name: '.edit')]
    public function edit(Request $request, AmoCRMoAccessTokenStorage $storage, Flusher $flusher): Response
    {
        $token = $storage->load();

        $form = $this->createForm(
            AmoCRMoAccessTokenEditForm::class,
            [
                'clientId' => $token->getClientId(),
                'integrationId' => $token->getIntegrationId(),
                'secretKey' => $token->getSecretKey(),
                'baseDomain' => $token->getBaseDomain(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $token->setClientId($data['clientId']);
            $token->setIntegrationId($data['integrationId']);
            $token->setSecretKey($data['secretKey']);
            $token->setBaseDomain($data['baseDomain']);

            $flusher->flush();

            return $this->redirectToRoute('store.admin.amo.get.access_token', ['code' => $data['code']]);
        }

        return $this->render(
            'admin/store/amo/edit.html.twig',
            [
                'form' => $form->createView(),
                'token' => $token,
            ]
        );
    }

    // Получение Access токен по коду Авторизации!!
    #[Route(path: '/token/{code}/get-access-token', name: '.get.access_token')]
    public function getAccessTokenFirst(string $code, AmoCRMoAccessTokenStorage $storage, Flusher $flusher): Response
    {
        $token = $storage->load();

        $subdomain = 'servicewomjoyru'; // Поддомен нужного аккаунта
        $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; // Формируем URL для запроса

        /** Соберем данные для запроса */
        $data = [
            'client_id' => $token->getClientId(),
            'client_secret' => $token->getSecretKey(),
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->generateUrl(
                route: 'admin.dashboard.show',
                parameters: [],
                referenceType: UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ];

        /**
         * Нам необходимо инициировать запрос к серверу.
         * Воспользуемся библиотекой cURL (поставляется в составе PHP).
         * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
         */
        $curl = curl_init(); // Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); // Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];
        $this->addFlash('success', 'Token ready');

        try {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new Exception($errors[$code] ?? 'Undefined error', $code);
            }

            /**
             * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
             * нам придётся перевести ответ в формат, понятный PHP.
             */
            $response = json_decode($out, true);

            $token->setAccessToken($response['access_token']);
            $token->setRefreshToken($response['refresh_token']);
            $token->setExpires($response['expires_in']);

            $flusher->flush();

            $this->addFlash('success', 'Succes token ready.');
        } catch (Exception $e) {
            $this->addFlash('danger', 'Error ' . $e->getCode());
        }

        return $this->redirectToRoute('store.admin.amo.edit');
    }

    // Получение нового access token по его истечении
    public function updateAccessToken(Request $request): Response
    {
        return $this->redirectToRoute('store.admin.amo.edit');
    }
}
