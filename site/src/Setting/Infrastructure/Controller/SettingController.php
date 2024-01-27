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
    ) {}

    #[Route(path: '/admin/setting/base', name: 'setting.base')]
    public function edit(Request $request): Response
    {
        $setting = $this->service->get();

        $form = $this->createForm(
            SettingEditBaseForm::class,
            [
                'phone' => $setting->getPhone(),
                'email' => $setting->getEmail(),
                'h1' => $setting->getSeoDefault()->getH1(),
                'title' => $setting->getSeoDefault()->getTitle(),
                'description' => $setting->getSeoDefault()->getDescription(),
                'companyName' => $setting->getCompany()->getName(),
                'storeName' => $setting->getStoreName(),
                'storeUrl' => $setting->getStoreUrl(),
                'postalCode' => $setting->getCompany()->getPostalCode(),
                'addressCountry' => $setting->getCompany()->getAddressCountry(),
                'addressLocality' => $setting->getCompany()->getAddressLocality(),
                'streetAddress' => $setting->getCompany()->getStreetAddress(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $setting->setPhone($data['phone']);
            $setting->setEmail($data['email']);

            $setting->getSeoDefault()->setH1($data['h1']);
            $setting->getSeoDefault()->setTitle($data['title']);
            $setting->getSeoDefault()->setDescription($data['description']);

            $setting->getCompany()->setName($data['companyName']);
            $setting->setStoreName($data['storeName']);
            $setting->setStoreUrl($data['storeUrl']);
            $setting->getCompany()->setPostalCode($data['postalCode']);
            $setting->getCompany()->setAddressCountry($data['addressCountry']);
            $setting->getCompany()->setAddressLocality($data['addressLocality']);
            $setting->getCompany()->setStreetAddress($data['streetAddress']);

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
