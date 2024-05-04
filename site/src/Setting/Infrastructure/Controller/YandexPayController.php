<?php

declare(strict_types=1);

namespace App\Setting\Infrastructure\Controller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Setting\Domain\Entity\ValueObject\SettingYandexPay;
use App\Setting\Infrastructure\Form\YandexPayEditForm;
use App\Setting\Infrastructure\Service\SettingService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YandexPayController extends AbstractController
{
    public function __construct(
        private readonly SettingService $service,
        private readonly Flusher $flusher,
    ) {}

    #[Route(path: '/admin/setting/yandex-pay', name: 'setting.yandex_pay')]
    public function edit(Request $request): Response
    {
        $setting = $this->service->get();
        $form = $this->createForm(
            YandexPayEditForm::class,
            [
                'apiKey' => $setting->getYandexPay()->getApiKey(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $setting->setYandexPay(new SettingYandexPay($data['apiKey']));
                $this->flusher->flush();
                $this->addFlash('success', 'Success edit setting yandex pay');
                $this->redirect('setting.yandex-pay');
            } catch (Exception $exception) {
                $this->addFlash('danger', 'Error edit setting yandex pay ' . $exception->getMessage());
                $this->redirect('setting.yandex-pay');
            }
        }

        return $this->render(
            'setting/yandex_pay/edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
