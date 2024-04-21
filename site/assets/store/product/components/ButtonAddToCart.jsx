// Корневой компонент приложения
import React from 'react';

// Имортируем комопненты

const ButtonAddToCart = () => {

    const handleAddToCart = () => {
        //make down Вызываем пользовательское событие 'checkoutClicked'
        const addToCartEvent = new CustomEvent('addToCartClicked');
        window.dispatchEvent(addToCartEvent);
    }

    return (
        <button className="w-action-btn w-100" onClick={handleAddToCart}>Добавить в корзину</button>
    );
};

export default ButtonAddToCart;