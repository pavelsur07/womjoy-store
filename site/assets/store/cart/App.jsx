// Корневой компонент приложения
import React from 'react';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import cartReducer from './store/cartSlice';
import Cart from './Cart';

const store = configureStore({
    reducer: {
        cart: cartReducer,
    },
});

const App = () => {
    return (
        <Provider store={store}>
            <Cart />
        </Provider>
    );
};

export default App;