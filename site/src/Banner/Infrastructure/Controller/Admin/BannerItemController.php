<?php

declare(strict_types=1);

namespace App\Banner\Infrastructure\Controller\Admin;

use App\Banner\Domain\Entity\Item;
use App\Banner\Infrastructure\Form\Admin\BannerItemEditForm;
use App\Banner\Infrastructure\Form\Admin\BannerItemImageEditForm;
use App\Banner\Infrastructure\Repository\BannerRepository;
use App\Common\Infrastructure\Doctrine\Flusher;
use App\Common\Infrastructure\Uploader\FileUploader;
use App\Store\Infrastructure\Controller\AccountController;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/banners', name: 'admin.banner')]
class BannerItemController extends AccountController
{
    #[Route(path: '/{id}/edit/item/new', name: '.new_item', methods: ['GET', 'POST'])]
    public function newItem(string $id, Request $request, BannerRepository $banners, Flusher $flusher): Response
    {
        $form = $this->createForm(BannerItemEditForm::class, []);
        $form->handleRequest($request);

        $banner = $banners->get($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $item = new Item(id: Uuid::uuid4()->toString(), banner: $banner);
            $item->setTitle($data['title']);
            $item->setDescription($data['description']);
            $item->setUrl($data['url']);

            $banner->addItem($item);
            $flusher->flush();
            $this->addFlash('success', 'Item created.');
            return $this->redirectToRoute('admin.banner.edit', ['id' => $id]);
        }

        return $this->render(
            'admin/banner/item/edit.html.twig',
            [
                'form' => $form->createView(),
                'banner' => $banner,
            ]
        );
    }

    #[Route(path: '/{id}/edit/item/{item_id}/edit', name: '.item.edit', methods: ['GET', 'POST'])]
    public function editItem(string $id, string $item_id, Request $request, BannerRepository $banners, Flusher $flusher): Response
    {
        $banner = $banners->get($id);
        $item = $banner->getItem($item_id);
        $form = $this->createForm(
            BannerItemEditForm::class,
            [
                'title' => $item->getTitle(),
                'description' => $item->getDescription(),
                'url' => $item->getUrl(),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $item->setTitle($data['title']);
            $item->setDescription($data['description']);
            $item->setUrl($data['url']);

            $flusher->flush();

            $this->addFlash('success', 'Item edited.');
            return $this->redirectToRoute('admin.banner.edit', ['id' => $id]);
        }

        return $this->render(
            'admin/banner/item/edit.html.twig',
            [
                'item' => $item,
                'banner' => $banner,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit/item/{item_id}/edit/desktop-image', name: '.item.edit.image_desktop', methods: ['GET', 'POST'])]
    public function addDesktopImage(string $id, string $item_id, Request $request, BannerRepository $banners, FileUploader $uploader, Flusher $flusher): Response
    {
        $banner = $banners->get($id);
        $item = $banner->getItem($item_id);

        $form = $this->createForm(BannerItemImageEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $uploaded = $uploader->upload($file, $path = 'banner');

            if ($item->getPatchMobileImage() !== null) {
                $uploader->remove(
                    path: $item->getPatchDesktopImage(),
                    name: $item->getNameDesktopImage()
                );
            }

            $item->setPatchDesktopImage($uploaded->getPath());
            $item->setNameDesktopImage($uploaded->getName());
            $flusher->flush();

            $this->addFlash('success', 'Item edited mobile image.');
            return $this->redirectToRoute('admin.banner.item.edit.image_desktop', ['id' => $id, 'item_id' => $item_id]);
        }
        return $this->render(
            'admin/banner/item/edit_desktop_image.html.twig',
            [
                'item' => $item,
                'banner' => $banner,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit/item/{item_id}/edit/mobile-image', name: '.item.edit.image_mobile', methods: ['GET', 'POST'])]
    public function addMobileImage(string $id, string $item_id, Request $request, BannerRepository $banners, FileUploader $uploader, Flusher $flusher)
    {
        $banner = $banners->get($id);
        $item = $banner->getItem($item_id);

        $form = $this->createForm(BannerItemImageEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $uploaded = $uploader->upload($file, $path = 'banner');

            if ($item->getPatchMobileImage() !== null) {
                $uploader->remove(
                    path: $item->getPatchMobileImage(),
                    name: $item->getNameMobileImage()
                );
            }

            $item->setPatchMobileImage($uploaded->getPath());
            $item->setNameMobileImage($uploaded->getName());
            $flusher->flush();

            $this->addFlash('success', 'Item edited mobile image.');
            return $this->redirectToRoute('admin.banner.item.edit.image_mobile', ['id' => $id, 'item_id' => $item_id]);
        }
        return $this->render(
            'admin/banner/item/edit_mobile_image.html.twig',
            [
                'item' => $item,
                'banner' => $banner,
                'form' => $form->createView(),
            ]
        );
    }
}
