<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Api\AmoCRM;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMClient;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMoAccessTokenStorage;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMoAuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/v1/amo-crm', name: 'store.api.amo_crm')]
class AmoCRMController extends AbstractController
{
    /**
     * @throws AmoCRMoAuthApiException
     */
    #[Route(path: '/token/refresh', name: 'token.refresh')]
    public function refreshToken(Request $request, AmoCRMClient $client, AmoCRMoAuthService $service, AmoCRMoAccessTokenStorage $storage): Response
    {
        $code = $request->get('code');
        $accessToken = $client->get()->getOAuthClient()->getAccessTokenByCode($code);

        $service->saveOAuthToken(
            accessToken: $accessToken,
            baseDomain: $storage->load()->getBaseDomain(),
        );

        return $this->redirectToRoute('admin.dashboard.show');
    }
}
