import React from 'react';
import {useSelector} from "react-redux";
import { useTranslation } from 'react-i18next'


const OrderDetailsMobilePion = () => {
    const { t } = useTranslation();
    const cart = useSelector((state) => state.cart);

    const handleClickCheckout = () => {
        //make down Вызываем пользовательское событие 'checkoutClicked'
        const checkoutEvent = new CustomEvent('checkoutClicked');
        window.dispatchEvent(checkoutEvent);
    }

    return (
        <>
            <div className="cart-checkout" data-float-target=".c-main__btn">
                <div className="cart-checkout__text">
                    <span className="cart-checkout__top">Итого</span>
                    <div className="cart-checkout__val">
                        {cart.amount} тов. на <b>{new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} ₽</b>
                    </div>
                </div>
                <button className="w-action-btn" onClick={handleClickCheckout}>Оформить заказ</button>
            </div>
        </>
    );
}

export default OrderDetailsMobilePion;