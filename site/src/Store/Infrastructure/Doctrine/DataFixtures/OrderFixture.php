<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Doctrine\DataFixtures;

use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderCustomer;
use App\Store\Domain\Entity\Order\ValueObject\OrderDelivery;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Domain\Entity\Product\Product;
use App\Store\Infrastructure\Repository\OrderRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly OrderRepository $orders,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var Product $product */
        $product = $this->getReference(ProductFixture::REFERENCE_PRODUCT);

        $order = new Order(
            customer: new OrderCustomer(
                phone: '+79888984488',
                name: 'Pavel',
                email: 'pavel@site.app',
                comment: 'comment'
            ),
            delivery: new OrderDelivery(address: 'Щербинка, ул. Барышевская Роща, 22 '),
            payment: new OrderPayment(
                method: OrderPayment::PAYMENT_METHOD_COD,
                status: OrderPayment::PAYMENT_STATUS_WAITING
            )
        );

        $order->addItem(
            variant: $product->getVariants()->first(),
            quantity: 1
        );

        $order->setOrderNumber(
            $this->orders->nextOrderNumber()
        );

        $manager->persist($order);
        $manager->flush();

        $order = new Order(
            customer: new OrderCustomer(
                phone: '+79888984499',
                name: 'Skyvoker',
                email: 'skyvoker@site.app',
                comment: 'comment from Skyvoker'
            ),
            delivery: new OrderDelivery(address: 'Щербинка, ул. Барышевская Роща, 22 '),
            payment: new OrderPayment(
                method: OrderPayment::PAYMENT_METHOD_COD,
                status: OrderPayment::PAYMENT_STATUS_WAITING
            )
        );

        $order->addItem(
            variant: $product->getVariants()->first(),
            quantity: 1
        );

        $order->setOrderNumber(
            $this->orders->nextOrderNumber()
        );

        $manager->persist($order);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixture::class,
        ];
    }
}
