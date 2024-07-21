// Корневой компонент приложения
import React, {useEffect, useState} from 'react';

// Имортируем комопненты
// Имортируем комопненты
import OrderDetailsPion from "../OrderDetails/OrderDetailsPion";
import CustomerPion from "../Customer/CustomerPion";
import PaymentPion from "../Payment/PaymentPion";
import DeliveryPion from "../Delivery/DeliveryPion";
import OrderItemsPion from "../OrderItems/OrderItemsPion";
import {useDispatch, useSelector} from "react-redux";
import {checkout as checkoutAction} from "../../../redux/actions/checkout";
import {z} from "zod";
import OrderItems from "../OrderItems/OrderItems";
import OrderDetails from "../OrderDetails/OrderDetails";
import Customer from "../Customer/Customer";
import Delivery from "../Delivery/Delivery";
import Payment from "../Payment/Payment";

const App = () => {
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

    useEffect(()=>{
        window.addEventListener('checkoutClicked', handleCheckoutClick);
    },[])

    const handleCheckoutClick = () => {
        handleClickCheckout()
    }

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
        <>
            <h1 className="cart__title title-xl">Оформление заказа</h1>
            <div className="cart__content">
                <div className="cart__left">
                    <div className="cart__items">
                        <OrderItems />
                        <Customer heading="Покупатель" errors={errorsCustomer} />
                        <Delivery heading="Доставка" errors={errorsDelivery}/>
                        <Payment heading="Оплата"/>
                    </div>

                </div>
                <OrderDetails onClickCheckout={handleClickCheckout} />

                {/*
                <Customer />
                <Delivery />
                <Payment />
                <OrderDetails />
                */}


            </div>
        </>
    );
};

export default App;
