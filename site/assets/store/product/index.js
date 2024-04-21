// Корневой компонент приложения
import React from 'react'

// Импортируем store
import store from '../redux/store'

// Импортируем действие
import { addCartItem } from '../redux/actions/cart'
import ReactDOM from "react-dom/client";
import SubscribeInput from "./components/SubscribeInput";
import {Provider} from "react-redux";
import App from "../cart-badge/components/App/App";
import ButtonAddToCart from "./components/ButtonAddToCart";


const buttonNodeList = document.querySelectorAll('.add-to-cart-action')
const variantsNodeList = document.querySelectorAll(
  '.i-card__size_items input[name=card-size]'
)
const subscribeProduct = document.getElementById('subscribe-product')
const stickyBar = document.getElementById('sticky-bar')


variantsNodeList.forEach((node) => {
  // проверякм выбранный элемент

  if (node.checked) {
    const buttonAdd = document.querySelector('.add-to-cart-action')
    // устанавливаем ID товара на кнопку
    document.querySelector('.add-to-cart-action').dataset.variantId = node.value
    // устанавливаем остатки товаров на кнопку
    buttonAdd.dataset.quantity = node.dataset.quantity


    if (node.dataset.quantity > 0 ){
      buttonAdd.querySelector('span').innerText = 'Добавить в корзину'
    } else {
      buttonAdd.querySelector('span').innerText = 'Уведомить'

      if (subscribeProduct) {
        ReactDOM.createRoot(subscribeProduct).render(
            <>
              <SubscribeInput/>
            </>
        )
      }
      buttonAdd.setAttribute("disabled", "disabled")
    }
  }

  node.addEventListener('change', ({currentTarget}) => {
    // Получаем кнопку и остатки товароа
    const buttonAdd = document.querySelector('.add-to-cart-action')
    const quantity = currentTarget.dataset.quantity

    // устанавливаем ID товара на кнопку
    document.querySelector('.add-to-cart-action').dataset.variantId =
      currentTarget.value

    // устанавливаем остатки товаров на кнопку
    buttonAdd.dataset.quantity = quantity


    buttonNodeList.forEach((buttonNode) => {
      if (quantity > 0) {
        buttonNode.querySelector('span').innerText = 'Добавить в корзину'
        if (subscribeProduct) {
          ReactDOM.createRoot(subscribeProduct).render(
              <></>
          )
        }
        buttonNode.removeAttribute("disabled")
      } else {
        buttonNode.querySelector('span').innerText = 'Уведомить'
        if (subscribeProduct) {
          ReactDOM.createRoot(subscribeProduct).render(
            <>
              <SubscribeInput/>
            </>
          )
        }
        buttonNode.setAttribute("disabled", "disabled")
      }

      if (buttonNode.classList.contains('active')) {
        buttonNode.classList.remove('active')
      }

    })
  })
})

buttonNodeList.forEach((node) => {
  // получить модальное окно
  const modalProduct = document.getElementById('modal-product');


  console.log(node)
  node.addEventListener('click', ({ currentTarget }) => {

    const quantity = currentTarget.dataset.quantity
    console.log(currentTarget)

    // создаём действие
    const action = addCartItem(currentTarget.dataset.variantId, 1)

    // выполняем действие через стор
    store.dispatch(action)

    // показать модальное окно
    if (!modalProduct.classList.contains('active')) {
      modalProduct.classList.add('active')
    }
    node.querySelector('span').innerText = 'В корзине'


    if (!node.classList.contains('active')) {
      node.classList.add('active')
    }
  })
})

// Добавляем обработчик события 'checkoutClicked'
window.addEventListener('addToCartClicked', addToCard);

function addToCard() {
  console.log('Ups!!')
}


if (stickyBar) {
  ReactDOM.createRoot(stickyBar).render(
      <ButtonAddToCart />
  )
}