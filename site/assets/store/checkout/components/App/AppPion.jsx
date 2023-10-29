// Корневой компонент приложения
import React from 'react';

// Имортируем комопненты
import {useDispatch, useSelector} from "react-redux";
import ItemPion from "../Item/ItemPion";
import OrderPion from "../Order/OrderPion";
import CustomerPion from "../Customer/CustomerPion";
import {changePayment} from "../../../redux/actions/checkout";
import PaymentPion from "../Payment/PaymentPion";
import Delivery from "../Delivery/Delivery";
import DeliveryPion from "../Delivery/DeliveryPion";

const AppPion = () => {

    const cart = useSelector((state) => state.cart);

    return (
        <section className="checkout w-section">
            <h1 className="mb-5 text-uppercase">ОФОРМЛЕНИЕ ЗАКАЗА</h1>
            <div className="row position-relative align-items-start">
                <div className="col col-12 col-lg-9">
                    <div className="checkout__items">
                        <div className="checkout__item w-border-bottom-primary2 w-border-top-primary2">
                            <div className="py-4 w-text-lg">1. ТОВАРЫ В ЗАКАЗЕ</div>
                            <div className="pb-4">
                                <div className="mb-4 d-none d-md-flex position-relative align-items-center justify-content-between">
                                    <span className="cart__head text-start text-lg-center">Наименование товара</span>
                                    <span className="cart__head">Цвет</span>
                                    <span className="cart__head">Размер</span>
                                    <span className="cart__head">Цена</span>
                                    <span className="cart__head">Количество</span>
                                    <span className="cart__head">Сумма</span>
                                </div>

                                <div className="cart__rows pb-4 w-border-bottom-primary2">

                                    {
                                        cart.items.map(
                                            (item, index) => (
                                                <ItemPion key={index} item={item} />
                                            )
                                        )
                                    }

                                </div>

                            </div>
                        </div>

                    </div>
                    <CustomerPion />
                    <PaymentPion />
                    <DeliveryPion />

                </div>

                <OrderPion />


            </div>

        </section>
    );
};

export default AppPion;