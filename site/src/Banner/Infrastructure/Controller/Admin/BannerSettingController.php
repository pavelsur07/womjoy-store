<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Controller\Admin;

use App\Banner\Infrastructure\Form\Admin\BannerSettingEditForm;
use App\Banner\Infrastructure\Repository\BannerForChoice;
use App\Banner\Infrastructure\Repository\BannerRepository;
use App\Banner\Infrastructure\Service\BannerService;
use App\Common\Infrastructure\Doctrine\Flusher;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/banners/setting', name: 'admin.banner.setting')]
class BannerSettingController extends AbstractController
{
    #[Route(path: '/hero-slider', name: '.hero_slider')]
    public function heroSlider(Request $request, BannerService $service, BannerRepository $banners, Flusher $flusher): Response
    {
        $settingHeroSlider = $service->getHeroSlider();

        $form = $this->createForm(
            BannerSettingEditForm::class,
            [
                'heroSlider' => $settingHeroSlider->getHeroSlider() !== null ?
                    new BannerForChoice(
                        label: $settingHeroSlider->getHeroSlider()->getName(),
                        value: $settingHeroSlider->getHeroSlider()->getId()
                    ) :
                    null,
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $heroSlider = $banners->get($data['heroSlider']->getValue());
                $settingHeroSlider->setHeroSlider($heroSlider);
                $flusher->flush();
                $this->addFlash('success', 'Success setting changed.');
                return $this->redirectToRoute('admin.banner.setting.hero_slider');
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('admin.banner.setting.hero_slider');
            }
        }

        return $this->render(
            'admin/banner/setting/hero_slider.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
