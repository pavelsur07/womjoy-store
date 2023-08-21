<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Syncing;

use App\Matrix\Domain\Entity\Syncing\Key\Key;
use App\Matrix\Domain\Entity\Syncing\Key\ValueObject\WildberriesKey;
use App\Matrix\Infrastructure\Form\Syncing\Key\KeyNewForm;
use App\Matrix\Infrastructure\Repository\Syncing\KeyRepository;
use DomainException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/syncing/key', name: 'matrix.admin.syncing.key')]
class KeyController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(KeyRepository $keys): Response
    {
        return $this->render(
            'admin/matrix/syncing/key/index.html.twig',
            [
                'pagination' => $keys->list(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, KeyRepository $keys): Response
    {
        $form = $this->createForm(KeyNewForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $keys->save(
                    new Key(
                        name: $data['name'],
                        wildberriesKey: new WildberriesKey(
                            value: $data['key'],
                            type: WildberriesKey::KEY_STATISTICS
                        )
                    ),
                    true
                );
                $this->addFlash('success', 'Success added new key.');
            } catch (DomainException $e) {
                $this->addFlash('danger', 'Error added new key - ' . $e->getMessage());
            }

            return $this->redirectToRoute('matrix.admin.syncing.key.index');
        }

        return $this->render(
            'admin/matrix/syncing/key/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: '.edit')]
    public function edit(int $id, Key $key, Request $request, KeyRepository $keys): Response
    {
        $form = $this->createForm(
            KeyNewForm::class,
            [
                'name' => $key->getName(),
                'key' => null,
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $keys->save(
                    new Key(
                        name: $data['name'],
                        wildberriesKey: new WildberriesKey(
                            value: $data['key'],
                            type: WildberriesKey::KEY_STATISTICS
                        )
                    ),
                    true
                );
                $this->addFlash('success', 'Success edit new key.');
            } catch (DomainException $e) {
                $this->addFlash('danger', 'Error edit new key - ' . $e->getMessage());
            }

            return $this->redirectToRoute('matrix.admin.syncing.key.index');
        }

        return $this->render(
            'admin/matrix/syncing/key/edit.html.twig',
            [
                'form' => $form->createView(),
                'name' => $key->getName(),
            ]
        );
    }

    #[Route(path: '/{id}/remove', name: '.remove')]
    public function remove(int $id, Key $key, KeyRepository $keys): Response
    {
        $keys->remove($key, true);

        return $this->redirectToRoute('matrix.admin.syncing.key.index');
    }
}
