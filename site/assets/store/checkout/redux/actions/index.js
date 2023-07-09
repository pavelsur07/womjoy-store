import * as types from '../constants/ActionTypes'
import {execCheckoutRequest, fetchCart} from "../api/cart";
import {CHECKOUT_FAILURE} from "../constants/ActionTypes";

export const setCustomer = (name, phone, email, comment) => dispatch => {
    dispatch({
        type: types.SET_CUSTOMER,
        customer: {
            name: name, phone: phone, email: email, comment: comment
        }
    })
};

export const setDelivery = (price, address) => dispatch => {
    dispatch({
        type: types.SET_DELIVERY,
        delivery: {
            price: price, address: address
        }
    })
};


export const changePayment = (value) => dispatch => {
    dispatch({
        type: types.CHANGE_PAYMENT,
        payment: value
    })
};

const receiveCartInfo = (amount, cost, discount, discount_cost) => ({
    type: types.RECEIVE_CART_INFO,
    cart: {
        amount: amount, cost: cost, discount: discount, discount_cost: discount_cost
    }
});

export const getCartInfo = () => dispatch => {
    fetchCart().then(cart => {
        dispatch(
            receiveCartInfo(cart.amount, cart.cost, cart.discount, cart.costDiscount)
        )
    })
};

export const checkout = () => (dispatch, getState) => {
    const { checkout } = getState();

    dispatch({ type: types.CHECKOUT_REQUEST })

    // Отправка данных с использованием
    execCheckoutRequest( { customer: checkout.customer, delivery: checkout.delivery, payment: checkout.payment } )
        .then(result => {
            dispatch({ type: types.CHECKOUT_SUCCESS })

            console.log('Ответ при оформлении заказа:', result);

            if (result.redirect_url !== undefined) {
                window.location = result.redirect_url;
            }
        })
        .catch(error => {
            dispatch({ type: types.CHECKOUT_FAILURE })

            console.error('Ошибка при оформлении заказа:', error);
        });
}
