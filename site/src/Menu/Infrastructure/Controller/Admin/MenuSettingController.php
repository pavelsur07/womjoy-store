<?php

declare(strict_types=1);

namespace App\Menu\Infrastructure\Controller\Admin;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Menu\Domain\Exception\MenuException;
use App\Menu\Domain\Repository\MenuRepositoryInterface;
use App\Menu\Infrastructure\Form\MenuSettingEditForm;
use App\Menu\Infrastructure\Repository\MenuForChoice;
use App\Menu\Infrastructure\Service\MenuSettingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/menu/setting', name: 'menu.admin.setting')]
class MenuSettingController extends AbstractController
{
    #[Route(path: '/edit', name: '.edit')]
    public function edit(Request $request, MenuSettingService $service, MenuRepositoryInterface $menus, Flusher $flusher): Response
    {
        $setting = $service->getSetting();

        $form = $this->createForm(
            MenuSettingEditForm::class,
            [
                'footerMenu' => $setting->getFooterMenu() !==null ?
                    new MenuForChoice(
                        label: $setting->getFooterMenu()->getName(),
                        value: (string)$setting->getFooterMenu()->getId()
                    ) :
                    null,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $footer = $menus->get((int)$data['footerMenu']->getValue());
                $setting->changeFooterMenu($footer);
                $flusher->flush();
                $this->addFlash('success', 'Success setting changed.');
            } catch (MenuException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render(
            'menu/admin/setting/edit.html.twig',
            [
                'form'=> $form->createView(),
            ]
        );
    }
}
