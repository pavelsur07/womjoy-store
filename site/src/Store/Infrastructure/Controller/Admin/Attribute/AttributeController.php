<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Attribute;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Domain\Entity\Attribute\Attribute;
use App\Store\Domain\Exception\StoreAttributeException;
use App\Store\Infrastructure\Form\Attribute\AttributeEditForm;
use App\Store\Infrastructure\Repository\AttributeRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/attribute', name: 'store.admin.attribute')]
class AttributeController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(Request $request, AttributeRepository $attributes): Response
    {
        return $this->render(
            'admin/store/attribute/index.html.twig',
            [
                'pagination' => $attributes->list(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, AttributeRepository $attributes): Response
    {
        $form = $this->createForm(AttributeEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $attributes->save(new Attribute(name: $data['name'], type: $data['type']), true);
        }

        return $this->render(
            'admin/store/attribute/new.html.twig',
            [
                'pagination' => $attributes->list(),
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Attribute $attribute, Request $request, AttributeRepository $attributes, Flusher $flusher): Response
    {
        $form = $this->createForm(
            AttributeEditForm::class,
            [
                'name' => $attribute->getName(),
                'type' => $attribute->getType(),
                'isVisibleFilter' => $attribute->isVisibleFilter(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();
                $attribute->editName($data['name']);

                if ($data['type'] === Attribute::TYPE_BRAND) {
                    /*
                    if ($attribute->isBrand() === false) {
                         if ($attributes->hasBrandAttribute()) {
                             throw new StoreAttributeException('Brande already added attribute.');
                         }
                         $attribute->brandTypeActive();
                     }
                    */

                    $attribute->brandTypeActive();
                }

                if ($data['type'] === Attribute::TYPE_COLOR) {
                    if ($attribute->isColor() === false) {
                        /* if ($attributes->hasColorAttribute()) {
                             throw new StoreAttributeException('Color already added attribute.');
                         }*/

                        $attribute->colorTypeActive();
                    }
                }

                $attribute->setIsVisibleFilter($data['isVisibleFilter']);

                $flusher->flush();
                $this->addFlash('success', 'Success attribute edit');
            } catch (Exception $e) {
                $this->addFlash('danger', 'Error - ' . $e->getMessage());
            }
        }

        return $this->render(
            'admin/store/attribute/edit.html.twig',
            [
                'attribute' => $attribute,
                'form' => $form->createView(),
            ]
        );
    }
}
