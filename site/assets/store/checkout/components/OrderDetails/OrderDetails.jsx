import React from 'react';
import {useSelector} from "react-redux";

const OrderDetailsPion = ({ onClickCheckout }) => {
    const cart = useSelector((state) => state.cart);

    const handleCheckoutClick = () => {
        /*ym(67287694,'reachGoal','chek-pay')*/

        if (onClickCheckout) {
            onClickCheckout()
        }
    };

    return (
        <div className="c-main">
            <div className="c-main__title">Ваш заказ</div>
            <ul className="c-main__list">
                <li>
                    <span>Товары, {cart.amount} шт.</span>
                    <span>
                        {new Intl.NumberFormat('ru-RU').format(cart.cost)} ₽
                    </span>
                </li>
                <li>
                    <span>Скидка</span>

                        {
                            // есть скидка
                            cart.discount > 0 && (
                                <span>
                                    {cart.discount} ₽
                                </span>
                            )
                        }

                    {
                        // нет скидки
                        !cart.delivery && (
                            <span>0.00 ₽ </span>
                        )
                    }

                </li>
                <li>
                    <span>Доставка</span>

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

                </li>
            </ul>

            <div className="c-main__final">
                Сумма заказа
                <span className="c-main__cost">
                    {new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} ₽
                </span>
            </div>

            <button
                className="c-main__btn btn-primary"
                type="button"
                onClick={handleCheckoutClick}>
                Оформить заказ
            </button>

            <label className="c-main__check f-check">
                <input type="checkbox" checked hidden/>
                <span className="f-check__sq">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M3.08709 9.08709C2.72097 9.4532 2.72097 10.0468 3.08709 10.4129L6.08709 13.4129C6.4532 13.779 7.0468 13.779 7.41291 13.4129L14.9129 5.91291C15.279 5.5468 15.279 4.9532 14.9129 4.58709C14.5468 4.22097 13.9532 4.22097 13.5871 4.58709L6.75 11.4242L4.41291 9.08709C4.0468 8.72097 3.4532 8.72097 3.08709 9.08709Z"
                                  fill="#1A1E24"/>
                        </svg>
                    </span>
                <span className="f-check__txt">Согласен с условиями
                    <a href="https://womjoy.ru/pages/oferta"> Правил пользования торговой площадкой</a>
                </span>
            </label>

        </div>
        /*<div className="d-flex flex-column align-items-start pt-6">
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

            {/!*<button
                className="w-primary-btn mx-auto mt-2 d-block d-lg-none"
                type="button"
                onClick={ handleCheckoutClick }
            >
                Оформить заказ
            </button>*!/}

            <button
                className="w-action-btn w-100 c-main__btn btn-primary mt-2"
                type="button"
                onClick={handleCheckoutClick}
            >
                Оформить заказ
            </button>
        </div>*/
    )
}

export default OrderDetailsPion
