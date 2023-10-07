<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Yml;

use App\Store\Domain\Entity\Yml\Yml;
use App\Store\Infrastructure\Form\Yml\YmlNewForm;
use App\Store\Infrastructure\Repository\YmlRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/yml', name: 'store.admin.yml')]
class YmlController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(YmlRepository $ymls): Response
    {
        return $this->render(
            'admin/store/yml/index.html.twig',
            [
                'pagination' => $ymls->list(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, YmlRepository $ymls): Response
    {
        $form = $this->createForm(YmlNewForm::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /*$yml = new Yml(
                name: $data['name'],
                fileName: Uuid::uuid4()->toString(),
                path: ''
            );

            $ymls->save($yml, true);*/
        }

        return $this->render(
            'admin/store/yml/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/remove/{id}', name: '.remove')]
    public function remove(int $id, Yml $yml, Request $request): void {}

    #[Route(path: '/active/{id}', name: '.active')]
    public function active(int $id, Yml $yml, Request $request): void {}

    #[Route(path: '/disable/{id}', name: '.disable')]
    public function disable(int $id, Yml $yml, Request $request): void {}
}
