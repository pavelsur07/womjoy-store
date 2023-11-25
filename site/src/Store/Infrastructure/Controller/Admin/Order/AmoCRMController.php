<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use App\Store\Domain\Entity\Order\ValueObject\OrderId;
use App\Store\Domain\Repository\OrderRepositoryInterface;
use App\Store\Infrastructure\Service\AmoCRM\AmoCRMClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/orders/{order_id}/amo', name: 'store.admin.order.amo')]
class AmoCRMController extends AbstractController
{
    #[Route(path: '/create-lead', name: '.create_lead')]
    public function createLead(Request $request, OrderRepositoryInterface $orders, AmoCRMClient $client): Response
    {
        $orderId = $request->get('order_id');
        $apiClient = $client->get();

        try {
            $order = $orders->get(new OrderId($orderId));

            $leadsService = $apiClient->leads();
            // $lead = new LeadModel();

            $contact = new ContactModel();
            $contact->setFirstName($order->getCustomer()->getFirstName());
            $contact->setLastName($order->getCustomer()->getLastName());

            $contactCollection = (new ContactsCollection())->add($contact);

            $fieldsCollection = new CustomFieldsValuesCollection();

            $fields = new TextCustomFieldValuesModel();

            $lead = (new LeadModel())
                ->setName('Заказ ' . $order->getCustomer()->getName())
                ->setPrice($order->getTotalCost())
                ->setTags(
                    (new TagsCollection())
                        ->add(
                            (new TagModel())
                                ->setName($order->getStatus())
                        )
                )
                ->setCreatedAt($order->getCreatedAt()->getTimestamp())
                ->setContacts($contactCollection)
               // ->setCustomFieldsValues($fieldsCollection)
                ->setRequestId($order->getId()->value());

            $lead = $leadsService->addOne($lead);

            $this->addFlash('success', 'Success Test lead is added. ' . $lead->getPrice());
        } catch (AmoCRMApiException $e) {
            $this->addFlash('success', 'Error lead is not added.');
        }

        return $this->redirectToRoute('store.order.admin.show', ['id'=> $orderId]);
    }
}
