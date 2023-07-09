// Корневой компонент приложения
import React from 'react';
import {Provider} from 'react-redux';

// Импортируем store
import store from './redux/store';

// Имортируем комопненты
import Customer from "./components/Сustomer";
import Delivery from "./components/Delivery";
import Payment from "./components/Payment";
import Order from "./components/Order";

const App = () => {

    return (
        <Provider store={store}>

            <div className="checkout">
                <div className="section-top">
                    <h2 className="checkout__title title-l">Оформление заказа</h2>
                </div>

                <Customer />
                <Delivery />
                <Payment />

                <Order />
            </div>

        </Provider>
    );
};

export default App;
