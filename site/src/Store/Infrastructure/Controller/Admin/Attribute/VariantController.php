<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Attribute;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Form\Attribute\VariantEditForm;
use App\Store\Infrastructure\Repository\AttributeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/attribute/{id_attribute}/variant', name: 'store.admin.attribute.variant')]
class VariantController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(Request $request, AttributeRepository $attributes, Flusher $flusher): Response
    {
        $attribute = $attributes->get((int)$request->get('id_attribute'));

        $form = $this->createForm(VariantEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $attribute->addVariant($data['name']);
            $flusher->flush();
            $this->addFlash('success', 'Success variant added. - ' . $data['name']);
        }
        return $this->render(
            'admin/store/attribute/variant/index.html.twig',
            [
                'attribute' => $attribute,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id_variant}/edit', name: '.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AttributeRepository $attributes, Flusher $flusher): Response
    {
        $attributeId =(int)$request->get('id_attribute');
        $variantId = (int)$request->get('id_variant');
        $attribute = $attributes->get($attributeId);
        $variant = $attribute->getVariant($variantId);

        $form = $this->createForm(
            VariantEditForm::class,
            [
                'name' => $variant->getName(),
                'colorValue' => $variant->getColorValue(),
                'isActive' => $variant->isActive(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $variant->setColorValue($data['colorValue']);
            $variant->setIsActive($data['isActive']);
            $flusher->flush();

            return $this->redirectToRoute('store.admin.attribute.variant.index', ['id_attribute'=> $attributeId]);
        }

        return $this->render(
            'admin/store/attribute/variant/edit.html.twig',
            [
                'attribute' => $attribute,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id_variant}/remove', name: '.remove', methods: ['POST'])]
    public function remove(Request $request, AttributeRepository $attributes, Flusher $flusher): Response
    {
        $attributeId =(int)$request->get('id_attribute');
        $variantId = (int)$request->get('id_variant');

        $attribute = $attributes->get($attributeId);

        if ($this->isCsrfTokenValid('delete' . $attribute->getId(), $request->request->get('_token'))) {
            $attribute->removeVariant($variantId);
            $flusher->flush();
            $this->addFlash('success', 'Message - success remove variant.');
        }

        return $this->redirectToRoute('store.admin.attribute.variant.index', ['id_attribute'=> $attributeId]);
    }
}
