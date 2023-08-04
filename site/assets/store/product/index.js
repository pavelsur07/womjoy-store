import React from 'react'
import store from './store/index'
import { fetchCart } from './store/cartSlice'
import { createRoot } from 'react-dom/client'

let isRenderedCartCount = false

const addToCartBtn = document.getElementById('add_to_cart_btn')
const variants = document.querySelectorAll('input[name="card-size"]')

function renderCartCount(count) {
  const container = document.getElementById('cart_count_id')

  if (container && !isRenderedCartCount) {
    isRenderedCartCount = true
    const root = createRoot(container)

    root.render(<span className="cart-currentcnt">{count}</span>)
  }
}

addToCartBtn.addEventListener('click', () => {
  for (const f of variants) {
    if (f.checked) {
      console.log(f.value)

      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          variant_id: f.value,
          quantity: 1,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          // Обработка ответа от сервера
          if (data.message) {
            console.log(data)
          } else {
            console.log(data.success)
          }
        })
        .catch((error) => {
          // Обработка ошибки
          console.error(error)
        })
    }
  }
})

store.subscribe(() => {
  const state = store.getState()
  const cart = state.cart.cart

  if (cart !== null) {
    if (cart.amount > 0) {
      renderCartCount(cart.amount.toString())
    }
  }
})

store.dispatch(fetchCart())
