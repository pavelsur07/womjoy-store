<?php

namespace App\Store\Infrastructure\Controller\Store;

use App\Common\Infrastructure\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class PromoController extends BaseController
{
    #[Route(path: '/promo/4edf93d3-afa0-4f4d-a3bb-458019667077/basic-gift-course', name: 'gift_course')]
    public function promo()
    {
        return $this->render("pion/store/promo/gift_course/show.html.twig",
            [
                'metaData' => $this->metaData,
                'menu' => $this->menu,
                'name' =>'Name',
                'h1' => 'H1'
            ]);
    }
}