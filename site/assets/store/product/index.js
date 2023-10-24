// Корневой компонент приложения
import React from 'react'

// Импортируем store
import store from '../redux/store'

// Импортируем действие
import { addCartItem } from '../redux/actions/cart'

const buttonNodeList = document.querySelectorAll('.add-to-cart-action')
const variantsNodeList = document.querySelectorAll(
  '.i-card__size_items input[name=card-size]'
)

variantsNodeList.forEach((node) => {
  // проверякм выбранный элемент
  if (node.checked) {
    // устанавливаем ID товара на кнопку
    document.querySelector('.add-to-cart-action').dataset.variantId = node.value
  }

  node.addEventListener('change', ({ currentTarget }) => {
    // устанавливаем ID товара на кнопку
    document.querySelector('.add-to-cart-action').dataset.variantId =
      currentTarget.value

    buttonNodeList.forEach((buttonNode) => {
      buttonNode.querySelector('span').innerText = 'В корзину'

      if (buttonNode.classList.contains('active')) {
        buttonNode.classList.remove('active')
      }
    })
  })
})

buttonNodeList.forEach((node) => {

  console.log(node)
  node.addEventListener('click', ({ currentTarget }) => {
    // создаём действие
    const action = addCartItem(currentTarget.dataset.variantId, 1)

    // выполняем действие через стор
    store.dispatch(action)

    node.querySelector('span').innerText = 'В корзине'

    if (!node.classList.contains('active')) {
      node.classList.add('active')
    }
  })
})
