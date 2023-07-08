import { createSlice } from '@reduxjs/toolkit';

const cartSlice = createSlice({
    name: 'cart',
    initialState: {
        items: [],
    },
    reducers: {
        setCartItems: (state, action) => {
            state.items = action.payload.items;
            state.amount = action.payload.amount;
            state.cost = action.payload.cost;
            state.costDiscount = action.payload.costDiscount;
            state.discount = action.payload.discount;
        },
    },
});

export const { setCartItems } = cartSlice.actions;

export const fetchCart = () => (dispatch) => {
    // Получение данных с бэкэнда с использованием fetch()
    fetch('/api/v1/cart/')
        .then((response) => response.json())
        .then((data) => {
            // Обработка полученных данных
            dispatch(setCartItems(data));
        })
        .catch((error) => {
            console.error('Ошибка при получении корзины:', error);
            // Обработка ошибки
        });
};

export const updateQuantity = (payload) => (dispatch) => {
    // Отправка данных на бэкэнд с использованием fetch()
    fetch('/api/v1/cart/quantity', {
        method: 'POST',
        body: JSON.stringify(payload),
        headers: { 'Content-Type': 'application/json' },
    })
        .then((response) => response.json())
        .then((data) => {
            // Обработка ответа от сервера
            dispatch(setCartItems(data));
        })
        .catch((error) => {
            console.error('Ошибка при обновлении количества товара:', error);
            // Обработка ошибки
        });
};

export const clearCart = () => (dispatch) => {
    // Отправка запроса на бэкэнд для очистки корзины
    fetch('/api/v1/cart/clear', { method: 'POST' })
        .then((response) => response.json())
        .then((data) => {
            //Обработка ответа от сервера
            dispatch(setCartItems([]));
        })
        .catch((error) => {
            console.error('Ошибка при очистке корзины:', error);
            // Обработка ошибки
        });
};

export const removeFromCart = (productId) => (dispatch) => {
    // Отправка данных на бэкэнд с использованием fetch()
    fetch(`/cart/remove/${productId}`, { method: 'POST' })
        .then((response) => response.json())
        .then((data) => {
            // Обработка ответа от сервера
            dispatch(setCartItems(data));
        })
        .catch((error) => {
            console.error('Ошибка при удалении товара из корзины:', error);
            // Обработка ошибки
        });
};

export default cartSlice.reducer;