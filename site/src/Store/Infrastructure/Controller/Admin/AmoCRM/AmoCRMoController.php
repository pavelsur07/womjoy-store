<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\AmoCRM;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\AmoCRM\AmoCRMoAccessTokenEditForm;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMoAccessTokenStorage;
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
                'clientId' => $token->getClientId(),
                'baseUrl' => $token->getBaseDomain(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('store.admin.amo.edit');
        }

        return $this->render(
            'admin/store/amo/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
