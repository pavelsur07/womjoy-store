<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use App\Setting\Infrastructure\Service\SettingService;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Unisender\ApiWrapper\UnisenderApi;

class UnisenderController extends AbstractController
{
    public function __construct(
        private readonly SettingService $service,
    ) {}

    #[Route(path: '/admin/orders/{id}/unisender', name: 'store.order.admin.unisender')]
    public function pushEmailToUnisenderList(string $id, Request $request, OrderRepositoryInterface $orders): Response
    {
        $apiKey=$this->service->get()->getUnisender()->getKey();
        $listId=1;
        $email='test@app.ru';
        $order = $orders->get(new OrderId($id));

        $apiWrapper = new UnisenderApi($apiKey);

        try {
            $result = $apiWrapper->subscribe(
                [
                    'list_ids'=> 1,
                    'fields' => [
                        'email' => $order->getCustomer()->getEmail(),
                        'name' => $order->getCustomer()->getName(),
                    ],
                ],
            );

            $this->addFlash('success', 'Success add email to unisender.');
        } catch (Exception $e) {
            $message = 'Ошибка: ' . $e->getMessage();
            $this->addFlash('danger', $message);
        }

        return $this->redirectToRoute('store.order.admin.show', ['id' => $id]);
        // Пример использования функции
        // $apiKey = 'YOUR_API_KEY'; // Замените на ваш API ключ Unisender
        // $listId = 'YOUR_LIST_ID'; // Замените на ID вашего списка в Unisender
        // $email = 'example@example.com'; // Email адрес для добавления
    }
}
