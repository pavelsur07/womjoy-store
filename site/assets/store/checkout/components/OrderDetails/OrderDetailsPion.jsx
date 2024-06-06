import React from 'react';
import {useSelector} from "react-redux";

const OrderDetailsPion = ({ onClickCheckout }) => {
    const cart = useSelector((state) => state.cart);

    const handleCheckoutClick = () => {
        ym(67287694,'reachGoal','chek-pay')

        if (onClickCheckout) {
            onClickCheckout()
        }
    };

    return (
        <div className="d-flex flex-column align-items-start pt-6">
            <a href="/pages/delivery" className="w-black-link mb-3" target="_blank">УСЛОВИЯ ДОСТАВКИ</a>
            <a href="/pages/payment" className="w-black-link mb-3" target="_blank">ОПЛАТА ЗАКАЗОВ</a>
            <div className="d-flex justify-content-between mb-3 w-100">
                <span>ТОВАРЫ:</span>
                <span>{cart.amount} шт.</span>
            </div>

            <div className="d-flex justify-content-between mb-3 w-100">
                <span>ДОСТАВКА:</span>

                {
                    // цена доставки
                    cart.delivery_cost > 0 && (
                        <span>{cart.delivery_cost} ₽</span>
                    )
                }
                {
                    // бесплатно
                    !cart.delivery_cost && (
                        <span>БЕСПЛАТНО</span>
                    )
                }
            </div>

            <div className="d-flex justify-content-between mb-3 w-100">
                <span>ПРОМОКОД:</span>

                {
                    // цена доставки
                    cart.promo_code_discount > 0 && (
                        <span>{cart.promo_code_discount} ₽</span>
                    )
                }
                {
                    // бесплатно
                    !cart.promo_code_discount && (
                        <span>- 0.00 ₽</span>
                    )
                }
            </div>

            <div className="d-flex justify-content-between w-100">
                <span>ИТОГО:</span>
                <span className="w-text-xl">
                    {new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} ₽
                </span>
            </div>

            {/*<button
                className="w-primary-btn mx-auto mt-2 d-block d-lg-none"
                type="button"
                onClick={ handleCheckoutClick }
            >
                Оформить заказ
            </button>*/}

            <button
                className="w-action-btn w-100 c-main__btn btn-primary mt-2"
                type="button"
                onClick={handleCheckoutClick}
            >
                Оформить заказ
            </button>
        </div>
    )
}

export default OrderDetailsPion
