import React from "react";
import {useSelector} from "react-redux";
import OrderItemPion from "./OrderItemPion";

const OrderItemsPion = ({ heading }) => {
    const cart = useSelector((state) => state.cart);

    return (
        <div className="checkout__items order-4 order-md-1">
            <div className="checkout__item">
                <div className="py-4 w-text-lg">{heading}</div>
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
                                    <OrderItemPion key={index} item={item} />
                                )
                            )
                        }
                    </div>
                </div>
            </div>
        </div>
    );

};

export default OrderItemsPion;
