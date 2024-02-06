<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Setting\Domain\Entity\ValueObject\SettingMoysklad;
use App\Setting\Infrastructure\Form\MoyskladEditForm;
use App\Setting\Infrastructure\Service\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoyskladController extends AbstractController
{
    public function __construct(
        private readonly SettingService $service,
        private readonly Flusher $flusher,
    ) {}

    #[Route(path: '/admin/setting/moysklad', name: 'setting.moysklad')]
    public function edit(Request $request): Response
    {
        $setting = $this->service->get();

        $form = $this->createForm(
            MoyskladEditForm::class,
            [
                'token' => $setting->getMoysklad()->getToken(),
                'companyId' => $setting->getMoysklad()->getCompanyId(),
                'skladId' => $setting->getMoysklad()->getSkladId(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $setting->setMoysklad(
                new SettingMoysklad(
                    token: $data['token'],
                    companyId: $data['companyId'],
                    skladId: $data['skladId'],
                )
            );

            $this->flusher->flush();
            $this->addFlash('success', 'Success setting moysklad.');
            return $this->redirectToRoute('setting.moysklad');
        }

        return $this->render(
            'setting/moysklad/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
