<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Setting\Domain\Entity\ValueObject\SettingUnisender;
use App\Setting\Infrastructure\Form\SettingEditUnisenderForm;
use App\Setting\Infrastructure\Service\SettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UnisenderController extends AbstractController
{
    public function __construct(
        private readonly SettingService $service,
        private readonly Flusher $flusher,
    ) {}

    #[Route(path: '/admin/setting/unisender', name: 'setting.unisender')]
    public function edit(Request $request): Response
    {
        $setting = $this->service->get();

        $form = $this->createForm(
            SettingEditUnisenderForm::class,
            [
                'key' => $setting->getUnisender()->getKey(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $setting->setUnisender(new SettingUnisender($data['key']));

            $this->flusher->flush();
        }

        return $this->render(
            'setting/unisender/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
