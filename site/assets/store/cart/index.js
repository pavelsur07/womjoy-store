// Корневой компонент приложения
import React from 'react'
import ReactDOM from 'react-dom/client'
import { Provider } from 'react-redux'

// Импортируем store
import store from '../redux/store'
import '../i18n'

// Имортируем комопненты
import App from './components/App/App'
import AppPion from './components/App/AppPion'
import CartPionCheckout from "./components/Cart/CartPionCheckout";

const element = document.getElementById('cart-id')
const elementMobile = document.getElementById('cart-checkout')

if (element) {
  ReactDOM.createRoot(element).render(
    <Provider store={store}>
      <AppPion />
    </Provider>
  )
}

if (elementMobile) {
    ReactDOM.createRoot(elementMobile).render(
        <Provider store={store}>
            <CartPionCheckout />
        </Provider>
    )
}