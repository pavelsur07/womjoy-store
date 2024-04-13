// Корневой компонент приложения
import React from 'react'
import ReactDOM from 'react-dom/client'
import { Provider } from 'react-redux'

// Импортируем store
import store from '../redux/store'

// Имортируем комопненты
import App from './components/App/App'

const element = document.getElementById('cart-badge')
const elementMobile = document.getElementById('cart-badge-mobile')


if (element) {
  ReactDOM.createRoot(element).render(
    <Provider store={store}>
      <App />
    </Provider>
  )
}

if (elementMobile) {
    ReactDOM.createRoot(elementMobile).render(
        <Provider store={store}>
            <App />
        </Provider>
    )
}