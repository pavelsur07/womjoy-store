<?php

namespace App\Store\Infrastructure\Controller\Webhook;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeadGenicController extends AbstractController
{
    #[Route(path: '/endpoint/webhook/lead-genic')]
    public function subscribe(Request $request): Response
    {
        return $this->json(
            [
                'message'=> 'ok'
            ]);
    }
}