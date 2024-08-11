import React from "react";
import {useSelector} from "react-redux";
import OrderItem from "./OrderItem";

const OrderItemsPion = ({ heading }) => {
    const cart = useSelector((state) => state.cart);

    return (

        <div className="checkout__row">
            <div className="checkout__subtitle">Товары в заказе</div>
                <div className="checkout__items">
                    {
                        cart.items.map(
                            (item, index) => (
                                <OrderItem key={index} item={item}/>
                            )
                        )
                    }
                </div>
        </div>

    );

};

export default OrderItemsPion;
