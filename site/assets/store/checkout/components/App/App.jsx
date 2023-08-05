// Корневой компонент приложения
import React from 'react';

// Имортируем комопненты
import Customer from "../../components/Customer/Customer";
import Delivery from "../../components/Delivery/Delivery";
import Payment from "../../components/Payment/Payment";
import Order from "../../components/Order/Order";

const App = () => {
    return (
        <div className="checkout">
            <div className="section-top">
                <h2 className="checkout__title title-l">Оформление заказа</h2>
            </div>

            <Customer />
            <Delivery />
            <Payment />

            <Order />
        </div>
    );
};

export default App;
