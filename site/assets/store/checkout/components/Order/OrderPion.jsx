import React, { useState, useEffect } from 'react';
import {useDispatch, useSelector} from "react-redux";
import {checkout as checkoutAction} from "../../../redux/actions/checkout";

const OrderPion = () => {
    const dispatch = useDispatch();
    const cart = useSelector((state) => state.cart);

    const handleCheckoutClick = () => {
        dispatch(checkoutAction())
    };

    return(
        <div className="col col-12 col-sm-9 col-md-6 col-lg-3 position-sticky mx-auto top-0">
            <div className="d-flex flex-column align-items-start pt-6">
                <a href="/pages/delivery" className="w-black-link mb-3" target="_blank">УСЛОВИЯ ДОСТАВКИ</a>
                <a href="/pages/payment" className="w-black-link mb-3" target="_blank">ОПЛАТА ЗАКАЗОВ</a>
                <div className="d-flex justify-content-between mb-3 w-100">
                    <span>ТОВАРЫ:</span>
                    <span>{cart.amount} шт.</span>
                </div>
                <div className="d-flex justify-content-between w-100">
                    <span>ИТОГО:</span>
                    <span className="w-text-xl">
                        {new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} р.
                    </span>
                </div>

                <button
                    className="w-primary-btn mx-auto mt-2 d-block d-lg-none"
                    type="button"
                    onClick={ handleCheckoutClick }
                >
                    Оформить заказ
                </button>

                <button
                    className="w-primary-btn w-100 c-main__btn btn-primary mt-2"
                    type="button"
                    onClick={ handleCheckoutClick }
                >
                    Оформить заказ
                </button>
            </div>
        </div>
    )
}

export default OrderPion