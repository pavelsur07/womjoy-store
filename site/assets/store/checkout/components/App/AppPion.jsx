// Корневой компонент приложения
import React, {useState} from 'react';

// Имортируем комопненты
import OrderDetailsPion from "../OrderDetails/OrderDetailsPion";
import CustomerPion from "../Customer/CustomerPion";
import PaymentPion from "../Payment/PaymentPion";
import DeliveryPion from "../Delivery/DeliveryPion";
import OrderItemsPion from "../OrderItems/OrderItemsPion";
import {useDispatch, useSelector} from "react-redux";
import {checkout as checkoutAction} from "../../../redux/actions/checkout";
import {z} from "zod";

const AppPion = () => {
    const dispatch = useDispatch();
    const checkout = useSelector((state) => state.checkout);

    const [errorsCustomer, setErrorsCustomer] = useState({});
    const [errorsDelivery, setErrorsDelivery] = useState({});

    const schemaCheckoutCustomer = z.object({
        name: z.string().min(1, 'необходимо заполнить поле'),
        email: z.string().email('указан некорректный e-mail'),
        phone: z.string().min(18, 'необходимо заполнить поле'),
        comment: z.string().nullable()
    });

    const schemaCheckoutDelivery = z.object({
        address: z.string({
            invalid_type_error: 'необходимо указать адрес доставки',
            required_error: 'необходимо указать адрес доставки',
        }).min(10, 'необходимо указать адрес доставки'),
    });

    const clearValidateErrors = () => {
        setErrorsCustomer({});
        setErrorsDelivery({});
    };

    const handleClickCheckout = () => {
        clearValidateErrors();

        let hasErrors = false;

        /*
         * ВАЛИДАЦИЯ ДАННЫХ ПОКУПАТЕЛЯ
         */
        const checkoutCustomerValidateResult = schemaCheckoutCustomer.safeParse(checkout.customer);

        if (!checkoutCustomerValidateResult.success) {
            setErrorsCustomer(
                checkoutCustomerValidateResult.error.format()
            );

            hasErrors = true;
        }

        /*
         * ВАЛИДАЦИЯ ДАННЫХ ДОСТАВКИ
         */
        const checkoutDeliveryValidateResult = schemaCheckoutDelivery.safeParse(checkout.delivery);

        if (!checkoutDeliveryValidateResult.success) {
            setErrorsDelivery(
                checkoutDeliveryValidateResult.error.format()
            );

            hasErrors = true;
        }

        if (hasErrors) {
            return;
        }

        // 1. run validate checkout data
        // 2. if error, rendered error and red border on input

        // 2. else not error, try submit data to backend,
        // 3. if return error render error on page

        dispatch(
            checkoutAction()
        )
    };

    return (
        <section className="checkout w-section">
            <h1 className="mb-5 text-uppercase">ОФОРМЛЕНИЕ ЗАКАЗА</h1>

            <div className="row position-relative align-items-start">
                <div className="col col-12 col-lg-9">
                    <OrderItemsPion heading="ТОВАРЫ В ЗАКАЗЕ" />
                    <CustomerPion heading="ПОКУПАТЕЛЬ" errors={errorsCustomer} />
                    <DeliveryPion heading="ДОСТАВКА" errors={errorsDelivery}/>
                    <PaymentPion heading="ОПЛАТА" />
                </div>
                <div className="col col-12 col-sm-9 col-md-6 col-lg-3 position-sticky mx-auto top-0">
                    <OrderDetailsPion onClickCheckout={handleClickCheckout} />
                </div>
            </div>
        </section>
    );
};

export default AppPion;
