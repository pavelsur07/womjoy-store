import * as types from '../constants/ActionTypes'
import {
    addToCartRequest,
    clearCartRequest,
    fetchCartRequest,
    removeFromCartRequest,
    updateQuantityRequest
} from "../api/cart";


export const getCartInfo = () => dispatch => {
    fetchCartRequest().then(cart => {
        dispatch(
            receiveCartInfo(cart)
        )
    })
};

export const addCartItem = (productId, quantity) => dispatch => {
    addToCartRequest(productId, quantity).then(() => {
        dispatch(
            getCartInfo()
        )
    });
};

export const updateQuantityCartItem = (productId, quantity) => dispatch => {
    updateQuantityRequest(productId, quantity).then((cart) => {
        dispatch(
            receiveCartInfo(cart)
        )
    })
};

export const removeCartItem = (productId) => dispatch => {
    removeFromCartRequest(productId).then((cart) => {
        dispatch(
            receiveCartInfo(cart)
        )
    })
};

export const clearCart = () => dispatch => {
    clearCartRequest().then((cart) => {
        dispatch(
            receiveCartInfo(cart)
        )
    })
};

const receiveCartInfo = (cart) => ({
    type: types.RECEIVE_CART_INFO,
    cart: {
        amount: cart.amount,
        cost: cart.cost,
        discount: cart.discount,
        discount_cost: cart.costDiscount,
        items: cart.items
    }
});

