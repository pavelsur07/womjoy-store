<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Promo;

use App\Common\Infrastructure\Unisender\UnisenderServiceBuilder;
use App\Store\Domain\Entity\Promo\ValueObject\PromoCodeDiscountType;
use App\Store\Infrastructure\Repository\PromoCodeRepository;
use App\Store\Infrastructure\Service\Promo\PromoCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/promo-code', name: 'store.admin.promo_code')]
class PromoCodeController extends AbstractController
{
    public const PER_PAGE = 10;

    #[Route(path: '/', name: '.index')]
    public function index(Request $request, PromoCodeRepository $promoCodes): Response
    {
        return $this->render(
            'admin/store/promo_code/index.html.twig',
            [
                'pagination'=> $promoCodes->getAll(
                    page: $request->query->getInt('page', 1),
                    size: $request->query->getInt('size', self::PER_PAGE),
                ),
            ]
        );
    }

    #[Route(path: '/test', name: '.test')]
    public function testGenerate(PromoCodeService $service, UnisenderServiceBuilder $builder): Response
    {
        $service->getPromoCode(discountValue: 10, discountType: PromoCodeDiscountType::PERCENT);
        $service->save();

        $unisender = $builder->build();
        $unisender->sendEmailWithPromoCode(email: 'pavelsur07@gmail.com', templateId: 10000, promocode: $service->getCode()->getCode());

        return $this->redirectToRoute('store.admin.promo_code.index');
    }
}
