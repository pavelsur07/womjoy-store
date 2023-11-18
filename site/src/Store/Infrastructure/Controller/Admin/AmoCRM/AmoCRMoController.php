<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\LeadModel;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\AmoCRM\AmoCRMoAccessTokenEditForm;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMClient;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMoAccessTokenStorage;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
                'integrationId' => $token->getIntegrationId(),
                'secretKey' => $token->getSecretKey(),
                'baseDomain' => $token->getBaseDomain(),
                'redirectUrl' => $token->getRedirectUrl(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $token->setIntegrationId($data['integrationId']);
            $token->setSecretKey($data['secretKey']);
            $token->setBaseDomain($data['baseDomain']);
            $token->setCode($data['code']);
            $token->setRedirectUrl($data['redirectUrl']);

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
    #[Route(path: '/token/get-token', name: '.get.token')]
    public function getAccessTokenByCode(AmoCRMoAccessTokenStorage $storage, Flusher $flusher): Response
    {
        $token = $storage->load();
        $apiClient = new AmoCRMApiClient(
            clientId: $token->getIntegrationId(),
            clientSecret: $token->getSecretKey(),
            redirectUri: $token->getRedirectUrl(),
        );

        // $apiClient->setAccountBaseDomain($token->getBaseDomain() . '.amocrm.ru');
        $apiClient->setAccountBaseDomain($token->getBaseDomain());

        try {
            $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($token->getCode());

            $token->setAccessToken($accessToken->getToken());
            $token->setRefreshToken($accessToken->getRefreshToken());
            $token->setExpires($accessToken->getExpires());
            $token->setBaseDomain($apiClient->getAccountBaseDomain());

            $flusher->flush();

            $this->addFlash('success', 'Access Token is ready!');
        } catch (Exception $e) {
            $this->addFlash('danger', 'Error Access Token ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.admin.amo.edit');
    }

    #[Route(path: '/lead/test', name: '.lead.test')]
    public function testLead(AmoCRMClient $client): Response
    {
        $apiClient = $client->get();

        $leadsService = $apiClient->leads();
        $lead = new LeadModel();
        $lead->setName('Название сделки')
            ->setPrice(50000);

        /*$leadsCollection = new LeadsCollection();
        $leadsCollection->add($lead);*/

        try {
            $lead = $leadsService->addOne($lead);
            // $leadsCollection = $leadsService->add($leadsCollection);

            $this->addFlash('success', 'Success Test lead is added.');
        } catch (AmoCRMApiException $e) {
            $this->addFlash('success', 'Error lead is not added.');
        }

        return $this->redirectToRoute('store.admin.amo.edit');
    }

    // Получение нового access token по его истечении
    public function updateAccessToken(Request $request): Response
    {
        return $this->redirectToRoute('store.admin.amo.edit');
    }
}
