import React from 'react'
import ReactDOM from 'react-dom/client'
import { Cart } from './components/Cart'

const element = document.getElementById('cart-id')

if (element) {
  const root = ReactDOM.createRoot(element)
  root.render(
    <>
      <Cart />
    </>
  )
}
