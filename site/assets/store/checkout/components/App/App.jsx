// Корневой компонент приложения
import React from 'react';

// Имортируем комопненты
import Customer from "../../components/Customer/Customer";
import Delivery from "../../components/Delivery/Delivery";
import Payment from "../../components/Payment/Payment";
import OrderDetails from "../../components/OrderDetails/OrderDetails";

const App = () => {
    return (
        <div className="checkout">
            <div className="section-top">
                <h2 className="checkout__title title-l">Оформление заказа</h2>
            </div>

            <Customer />
            <Delivery />
            <Payment />

            <OrderDetails />
        </div>
    );
};

export default App;
