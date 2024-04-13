import React from 'react';
import {useSelector} from "react-redux";
import { useTranslation } from 'react-i18next'


const CartPionCheckout = () => {
    const { t } = useTranslation();
    const cart = useSelector((state) => state.cart);

    return (
        <>
            <div className="cart-checkout" data-float-target=".c-main__btn">
                <div className="cart-checkout__text">
                    <span className="cart-checkout__top">Итого</span>
                    <div className="cart-checkout__val">
                        {cart.amount} тов. на <b>{new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} ₽</b>
                    </div>
                </div>
                <a href="/cart/checkout/" className="w-action-btn">{t('Checkout')}</a>
            </div>
        </>
    );
}

export default CartPionCheckout;