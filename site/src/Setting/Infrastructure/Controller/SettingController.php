<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Setting\Infrastructure\Form\SettingEditBaseForm;
use App\Setting\Infrastructure\Service\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingController extends AbstractController
{
    public function __construct(
        private readonly SettingService $service,
        private readonly Flusher $flusher,
    ) {
    }

    #[Route(path: '/admin/setting/base', name: 'setting.base')]
    public function edit(Request $request): Response
    {
        $setting = $this->service->get();

        $form = $this->createForm(
            SettingEditBaseForm::class,
            [
                'phone' => $setting->getPhone(),
                'email' => $setting->getEmail(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $setting->setPhone($data['phone']);
            $setting->setEmail($data['email']);
            $this->flusher->flush();
        }

        return $this->render(
            'setting/base.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
