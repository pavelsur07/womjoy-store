<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Repository;

use App\Store\Domain\Entity\Order\Order;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Entity\Order\ValueObject\OrderNumber;
use App\Store\Domain\Entity\Order\ValueObject\OrderPayment;
use App\Store\Domain\Exception\StoreOrderException;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;

class OrderRepository implements OrderRepositoryInterface
{
    private PaginatorInterface $paginator;

    private EntityManagerInterface $em;

    /** @var EntityRepository<Order> */
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository(Order::class);
        $this->paginator = $paginator;
    }

    public function find(string $id): ?Order
    {
        return $this->repo->find($id);
    }

    public function getAll(
        int $page,
        int $size,
        string $sort = 'createdAt',
        string $direction = 'asc',
        ?string $status = null
    ): PaginationInterface {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Order::class, 'p');

        /*if ($status !== null) {
            $qb->andWhere('p.status.value = :status_value');
            $qb->setParameter('status_value', $status);
        }*/

        if (!\in_array($sort, ['createdAt', 'id'], true)) {
            throw new UnexpectedValueException('Cannot sort by ' . $sort);
        }

        // $qb->orderBy('p.' . $sort, $direction === 'asc' ? 'asc' : 'desc');
        $qb->orderBy('p.id', 'ASC');

        $qb->getQuery();

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function get(OrderId $id): Order
    {
        $object = $this->repo->find($id->value());
        if ($object === null) {
            throw new StoreOrderException('Order not found');
        }

        return $object;
    }

    public function findOwn(int $id, int $customerId): Order|null
    {
        return $this->repo->findOneBy(
            [
                'id' => $id,
                'customerId' => $customerId,
            ]
        );
    }

    public function save(Order $order, bool $flush = false): void
    {
        $this->em->persist($order);
        if ($flush) {
            $this->em->flush();
        }
    }

    public function nextOrderNumber(): OrderNumber
    {
        try {
            $nextOrderNumber = $this->em->createQueryBuilder()->from(Order::class, 'orders')
                ->select('max(orders.orderNumber.value) + 1 as next_order_number')
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            $nextOrderNumber = 1;
        }

        if ($nextOrderNumber === null) {
            $nextOrderNumber = 1;
        }

        return new OrderNumber(
            (int)$nextOrderNumber
        );
    }

    public function getOrdersAwaitingPayment(string $paymentProviderName): array
    {
        $expr = $this->em->getExpressionBuilder();

        $queryBuilder = $this->em->createQueryBuilder()->from(Order::class, 'orders')->select('orders')
            ->where(
                $expr->eq('orders.payment.method', ':payment_method')
            )
            ->andWhere(
                $expr->eq('orders.payment.status', ':payment_status')
            )
            ->andWhere(
                $expr->isNotNull('orders.payment.transactionId')
            )
            ->andWhere(
                $expr->eq('orders.payment.provider', ':payment_provider')
            )
            ->orderBy('orders.updatedAt', 'asc');

        $queryBuilder
            ->setParameter('payment_method', OrderPayment::PAYMENT_METHOD_ONLINE)
            ->setParameter('payment_status', OrderPayment::PAYMENT_STATUS_WAITING)
            ->setParameter('payment_provider', $paymentProviderName);

        return $queryBuilder->getQuery()->getResult();
    }
}
