import * as React from 'react';
import {CartItem} from "./CartItem";
import {CartTotal} from "./CartTotal";
import {CartDeliveryMethod} from "./CartDeliveryMethod";
import {CartPayMethod} from "./CartPayMethod";

export function Cart() {

    return (
        <>
            <div className="cart__top">
                <h1 className="cart__title title-xl">Корзина</h1>
                <button className="cart__clear" type="button" onClick={(e) => {
                    console.log(e)} }>
                    <img src='../img/icons/cart-remove.svg' alt="cart remove" width="20" height="20"/>
                        Очистить корзину
                </button>
            </div>
            <div className="cart__content">
                <div className="cart__left">
                    <div className="cart__items">
                        <CartItem />
                    </div>

                    <div className="checkout">
                        <div className="section-top">
                            <h2 className="checkout__title title-l">Оформление заказа</h2>
                        </div>
                        <CartDeliveryMethod />
                        <CartPayMethod />
                        <div className="checkout__row">
                            <div className="checkout__subtitle">Данные получателя</div>
                            <form className="field-list">
                                <div className="field">
                                    <span className="field__ph">Ваше имя</span>
                                    <input type="text" className="field__inp" placeholder="Иванов Иван Иванович"/>
                                </div>
                                <div className="field">
                                    <span className="field__ph">Номер телефона</span>
                                    <input type="text" className="field__inp phone-masked-field"
                                           placeholder="+7 956 654-55-33"/>
                                </div>
                                <div className="field">
                                    <span className="field__ph">Email для счёта</span>
                                    <input type="text" className="field__inp" placeholder="email@example.com"/>
                                </div>
                                <div className="field field-text">
                                    <span className="field__ph">Комментарий к заказу</span>
                                    <textarea className="field__inp" placeholder="Текст комментария"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <CartTotal />
            </div>
        </>
    );
}