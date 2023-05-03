<?php

declare(strict_types=1);

namespace App\Http\Store\Admin;

use App\Store\Domain\Repository\CategoryRepositoryInterface;
use App\Store\Infrastructure\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard/', name: 'admin.dashboard.show', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard/show.html.twig');
    }

    #[Route(path: '/admin/dashboard/tree-init',name: 'admin.tree_init')]
    public function treeInit(): Response
    {
        $em = $this->getDoctrine()->getManager();
        return $this->redirectToRoute('admin.dashboard.show');
    }
}
