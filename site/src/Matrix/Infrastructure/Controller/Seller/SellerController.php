<?php

declare(strict_types=1);

namespace App\Matrix\Infrastructure\Controller\Seller;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Matrix\Domain\Entity\Seller\Seller;
use App\Matrix\Domain\Exception\MatrixException;
use App\Matrix\Infrastructure\Form\Seller\SellerEditForm;
use App\Matrix\Infrastructure\Repository\SellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/matrix/seller', name: 'matrix.admin.seller')]
class SellerController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(SellerRepository $sellers): Response
    {
        return $this->render(
            'admin/matrix/seller/index.html.twig',
            [
                'pagination' => $sellers->list(),
            ]
        );
    }

    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, SellerRepository $sellers): Response
    {
        $form = $this->createForm(SellerEditForm::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $seller = new Seller(name: $data['name'], inn: $data['inn']);
                $sellers->save($seller, true);
                $this->addFlash('success', 'Succes added new seller - ' . $seller->getName());
                return $this->redirectToRoute('matrix.admin.seller.index');
            } catch (MatrixException $e) {
                $this->addFlash('danger', 'Error added new seller - ' . $e->getMessage());
            }
        }

        return $this->render('admin/matrix/seller/new.html.twig', ['form' => $form->createView()]);
    }

    #[Route(path: '/edit/{id}', name: '.edit')]
    public function edit(int $id, Seller $seller, Request $request, Flusher $flusher): Response
    {
        $form = $this->createForm(
            SellerEditForm::class,
            [
                'name' => $seller->getName(),
                'inn' => $seller->getInn(),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                $seller->setName($data['name']);
                $seller->setInn($data['inn']);
                $flusher->flush();

                $this->addFlash('success', 'Succes edit seller - ' . $seller->getName());
                return $this->redirectToRoute('matrix.admin.seller.index');
            } catch (MatrixException $e) {
                $this->addFlash('danger', 'Error edit seller - ' . $e->getMessage());
            }
        }

        return $this->render('admin/matrix/seller/new.html.twig', ['form' => $form->createView()]);
    }
}
