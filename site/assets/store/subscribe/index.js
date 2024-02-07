// Корневой компонент приложения
import React from 'react'
import ReactDOM from 'react-dom/client'

// Имортируем комопненты
import App from './components/App'

const subscribeFormDiv = document.getElementById('subscribe-form');


if (subscribeFormDiv) {
    ReactDOM.createRoot(subscribeFormDiv).render(
            <App />
    )
}

/*

import React from 'react';
import ReactDOM from 'react-dom';
import SubscriptionForm from './SubscriptionForm';

// Поиск элемента с id="subscribe_form"
const subscribeFormDiv = document.getElementById('subscribe_form');

// Проверка, найден ли элемент
if (subscribeFormDiv) {
    // Рендеринг компонента формы в найденный элемент
    ReactDOM.render(<SubscriptionForm />, subscribeFormDiv);*/
