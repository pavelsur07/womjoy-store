import * as types from '../constants/ActionTypes'
import {execCheckoutRequest} from '../api/checkout'
import debounce from "lodash.debounce";
import {updateCartCustomer} from "../api/cart";

export const checkout = () => (dispatch, getState) => {
    const { checkout } = getState()

    dispatch({ type: types.CHECKOUT_REQUEST })

    // Отправка данных с использованием
    execCheckoutRequest({
        customer: checkout.customer,
        delivery: checkout.delivery,
        payment: checkout.payment,
    })
        .then((result) => {
            dispatch({ type: types.CHECKOUT_SUCCESS })

            // Доступные события:
            //
            // impression — просмотр списка товаров;
            // detail — просмотр товара;
            // add — добавление товара в корзину;
            // remove — удаление товара из корзины;
            // purchase — покупка;
            pushStoreMetrika(
                'purchase',
                Array.from(document.querySelectorAll('[data-product-id]')).map((v) =>
                    parseInt(v.dataset.productId)
                ),
                result.order_id
            )

            console.log('Ответ при оформлении заказа:', result)

            if (result.redirect_url !== undefined) {
                window.location = result.redirect_url
            }
        })
        .catch((error) => {
            dispatch({ type: types.CHECKOUT_FAILURE })

            console.error('Ошибка при оформлении заказа:', error)
        })
}

const updateCartCustomerDebounce = debounce((name, email, phone) => {
    updateCartCustomer(name, email, phone).finally();
}, 300);

export const setCustomer =
    (name, phone, email, comment) => (dispatch) => {
        updateCartCustomerDebounce(name, email, phone);

        dispatch({
            type: types.SET_CUSTOMER,
            customer: {
                name,
                phone,
                email,
                comment,
            },
        })
    }

export const setDelivery = (price, address) => (dispatch) => {
    dispatch({
        type: types.SET_DELIVERY,
        delivery: {
            price,
            address,
        },
    })
}

export const changePayment = (value) => (dispatch) => {
    dispatch({
        type: types.CHANGE_PAYMENT,
        payment: value,
    })
}
