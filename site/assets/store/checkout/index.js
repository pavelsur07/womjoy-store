// Корневой компонент приложения
import React from 'react'
import ReactDOM from 'react-dom/client'
import { Provider } from 'react-redux'

// Импортируем store
import store from '../redux/store'

// Имортируем комопненты
//import App from './components/App/App'
import AppPion from "./components/App/AppPion";

const element = document.getElementById('checkout-id')

if (element) {
  ReactDOM.createRoot(element).render(
    <Provider store={store}>
      <AppPion />
    </Provider>
  )
}
