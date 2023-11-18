<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
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
            $token->setCode($data['code']);

            $flusher->flush();

            // return $this->redirectToRoute('store.admin.amo.get.access_token', ['code' => $data['code']]);
            $this->addFlash('success', 'Success changed AmoCRM setting');
            return $this->redirectToRoute('store.admin.amo.edit');
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
    #[Route(path: '/token/get-access-token', name: '.get.access_token')]
    public function getAccessTokenFirst(AmoCRMoAccessTokenStorage $storage, Flusher $flusher): Response
    {
        $token = $storage->load();
        $code = 'def5020041f26dfb32323a4ac4a70e676fce5be1f3324b1c5ea55768a933074b9c36528beca696c4c92462bd2c7dc29d9ead9d49ba7a00169532c5e0cbfc1bf3045aa62281849ba6f44ab2e118cba866e58ef82456cc54a31c4e69263e70e400a7ea274bc289f12b2cb6f2f4204fb86a0898af40b5342e73960843694f5cf8a1184abaa79e4734a5d287d3e7d24b511497991d65f80b9c40cac2cd7ebd4f19059d33d7293b2b41b333a8c78070b3d17546f9faf76db3a26b7b73af7c08667483ceb1ab06389174db5ba8bcd0b1323be274b1864ec7dd8097c751d56fbcd2471bf11f6e1bda8d5c75c4a25f89d40d93c4d23228980a5aa82a2ed3b30297ec5901ec6ecf1ca9e234906f93117bd26caacf13e7c602d9dd9b12555e171878a741d6bd61cd17e66d55df0fd58d759128b0c594a815beeb15737ca2bc94f1b86dbc2cfbf933d02a30e85ff9de5b848674161fef1e749b4de74967b45cf912000af63cd782c4d7c644c29b98876625e0644c18e03cf541e50d285bb4ff3e5152bfa9e5c3e2c77c2ab0d630a89e57a0e69d582927bcf368b6963477f67a70204fc40816f444f0561ee76fe3e015e354a77af349c501bc3ca7774db1918f6dba6e2d9cdabeb7c55ac30ffdb544ae91eefe8b90d39e432201ee9db01f7290b5355cb2f51de70c065ae93949f87b9014';

        $subdomain = 'servicewomjoyru'; // Поддомен нужного аккаунта
        $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; // Формируем URL для запроса

        /** Соберем данные для запроса */
        $data = [
            'client_id' => (int)$token->getClientId(),
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
        // $this->addFlash('success', 'Token ready');

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
            $this->addFlash('danger', 'Error ' . $e->getCode() . ' ' . $out);
        }

        return $this->redirectToRoute('store.admin.amo.edit');
    }

    #[Route(path: '/token/get-token', name: '.get.token')]
    public function getAccessTokenByCode(Request $request, AmoCRMoAccessTokenStorage $storage, Flusher $flusher): Response
    {
        $token = $storage->load();

        $apiClient = new AmoCRMApiClient(
            clientId: $token->getIntegrationId(),
            clientSecret: $token->getSecretKey(),
            redirectUri: $this->generateUrl(
                route: 'admin.dashboard.show',
                parameters: [],
                referenceType: UrlGeneratorInterface::ABSOLUTE_URL
            ),
        );

        try {
            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($token->getCode());

            $token->setAccessToken($accessToken->getToken());
            $token->setRefreshToken($accessToken->getRefreshToken());
            $token->setExpires($accessToken->getExpires());
            $token->setBaseDomain($apiClient->getAccountBaseDomain());

            $flusher->flush();

            $this->addFlash('success', 'Access Token is ready!');

        } catch (Exception $e) {
            $this->addFlash('danger', 'Error Access Token '.$e->getMessage());
        }

        return $this->redirectToRoute('store.admin.amo.edit');
    }

    // Получение нового access token по его истечении
    public function updateAccessToken(Request $request): Response
    {
        return $this->redirectToRoute('store.admin.amo.edit');
    }
}
