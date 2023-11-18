<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
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

        $order = $orders->get(new OrderId($orderId));

        $leadsService = $apiClient->leads();
        $lead = new LeadModel();

        $lead = (new LeadModel())
            ->setName('Заказ ' . $order->getCustomer()->getName())
            ->setPrice($externalLead['price'])
            ->setTags(
                (new TagsCollection())
                    ->add(
                        (new TagModel())
                            ->setName($externalLead['tag'])
                    )
            )
            ->setContacts(
                (new ContactsCollection())
                    ->add(
                        (new ContactModel())
                            ->setFirstName($externalLead['contact']['first_name'])
                            ->setLastName($externalLead['contact']['last_name'])
                            ->setCustomFieldsValues(
                                (new CustomFieldsValuesCollection())
                                    ->add(
                                        (new MultitextCustomFieldValuesModel())
                                            ->setFieldCode('PHONE')
                                            ->setValues(
                                                (new MultitextCustomFieldValueCollection())
                                                    ->add(
                                                        (new MultitextCustomFieldValueModel())
                                                            ->setValue($externalLead['contact']['phone'])
                                                    )
                                            )
                                    )
                            )
                    )
            )
            ->setCompany(
                (new CompanyModel())
                    ->setName('Novikova N.A.')
            )
            ->setRequestId($order->getId()->value());

        return $this->redirectToRoute('store.order.admin.show', ['id'=> $orderId]);
    }
}
