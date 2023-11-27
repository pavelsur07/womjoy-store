<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Controller\Admin\Order;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
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

            // $leadsService = $apiClient->leads();
            // $lead = new LeadModel();

            $contact = new ContactModel();
            $contact->setName($order->getCustomer()->getFirstName() . ' ' . $order->getCustomer()->getLastName());
            $contact->setFirstName($order->getCustomer()->getFirstName());
            $contact->setLastName($order->getCustomer()->getLastName());

            // Добавляем телефон и email в контакт
            // $contactFields = new CustomFieldsValuesCollection();

            // $contact->setCustomFieldsValues($contactFields);

            $lead = (new LeadModel())
                ->setName('Заказ ' . $order->getCustomer()->getName())
                ->setPrice($order->getTotalCost())
                ->setTags(
                    (new TagsCollection())
                        ->add(
                            (new TagModel())
                                ->setName($order->getStatus())
                        )
                        ->add(
                            (new TagModel())
                                ->setName('Заказ с сайта')
                        )
                )
                ->setCreatedAt($order->getCreatedAt()->getTimestamp())
                ->setSourceExternalId($order->getId()->value());

            // $lead->setContacts((new ContactsCollection())->add($contact));

            $lead->setContacts(
                (new ContactsCollection())
                    ->add(
                        (new ContactModel())
                            ->setFirstName($order->getCustomer()->getFirstName())
                            ->setLastName($order->getCustomer()->getLastName())
                            ->setCustomFieldsValues(
                                (new CustomFieldsValuesCollection())
                                    ->add(
                                        (new MultitextCustomFieldValuesModel())
                                            ->setFieldCode('EMAIL')
                                            ->setValues(
                                                (new MultitextCustomFieldValueCollection())
                                                    ->add(
                                                        (new MultitextCustomFieldValueModel())
                                                            ->setValue($order->getCustomer()->getEmail())
                                                    )
                                            )
                                    )
                                    ->add(
                                        (new MultitextCustomFieldValuesModel())
                                            ->setFieldCode('PHONE')
                                            ->setValues(
                                                (new MultitextCustomFieldValueCollection())
                                                    ->add(
                                                        (new MultitextCustomFieldValueModel())
                                                            ->setValue($order->getCustomer()->getPhone())
                                                    )
                                            )
                                    )
                            )
                    )
            );

            // -------------------------------------------------------------------------------------
            // Создадим коллекцию полей сущности
            $fieldsValues = new CustomFieldsValuesCollection();
            // Создадим модель значений поля типа текст
            $textCustomFieldValuesModel = new TextCustomFieldValuesModel();
            // Укажем ID поля
            $textCustomFieldValuesModel->setFieldId(1001239);
            // Добавим значения
            $textCustomFieldValuesModel->setValues(
                (new TextCustomFieldValueCollection())
                    ->add((new TextCustomFieldValueModel())->setValue('Текст'))
            );
            // Добавим значение в коллекцию полей сущности
            $fieldsValues->add($textCustomFieldValuesModel);

            // Создадим модель значений поля типа текст
            $numberField = new TextCustomFieldValuesModel();
            // Укажем ID поля
            $numberField->setFieldId(670825);
            $numberField->setValues(
                (new TextCustomFieldValueCollection())
                    ->add((new TextCustomFieldValueModel())->setValue((string)$order->getOrderNumber()->value()))
            );

            // Добавим значение в коллекцию полей сущности
            $fieldsValues->add($numberField);

            // _ym_uid
            $utmContentField = new TextCustomFieldValuesModel();
            $utmContentField->setFieldCode('_YM_UID');
            $utmContentField->setValues(
                (new TextCustomFieldValueCollection())
                    ->add((new TextCustomFieldValueModel())->setValue($order->getYmUid()))
            );
            $fieldsValues->add($utmContentField);

            // _ym_counter
            $utmContentField = new TextCustomFieldValuesModel();
            $utmContentField->setFieldCode('_YM_COUNTER');
            $utmContentField->setValues(
                (new TextCustomFieldValueCollection())
                    ->add((new TextCustomFieldValueModel())->setValue($order->getYmCounter()))
            );

            $fieldsValues->add($utmContentField);

            // Установим в сущности эти поля
            $lead->setCustomFieldsValues($fieldsValues);
            $lead->setRequestId($order->getId()->value());

            // $lead = $leadsService->addOne($lead);

            $leadsCollection = new LeadsCollection();
            $leadsCollection->add($lead);

            $addedLeadsCollection = $apiClient->leads()->addComplex($leadsCollection);

            $this->addFlash('success', 'Success Test lead is added. ' . $lead->getSourceId());
        } catch (AmoCRMApiException $e) {
            $this->addFlash('danger', 'Error lead is not added. ' . $e->getMessage());
        }

        return $this->redirectToRoute('store.order.admin.show', ['id'=> $orderId]);
    }
}
