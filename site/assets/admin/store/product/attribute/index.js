import React from 'react'
import ReactDOM from 'react-dom/client'
import { App } from './App'
import { Provider } from 'react-redux'
import store from '../redux/store'

const element = document.getElementById('product_attribute_edit')
const productId = element.dataset.product

if (element) {
  const root = ReactDOM.createRoot(element)

  root.render(
      <Provider store={store}>
        <App id={productId} />
      </Provider>
  )
}
