import * as types from '../constants/ActionTypes'

const defaultState = {
  amount: 0,
  cost: 0,
  discount: 0,
  discount_cost: 0,
  temlate: 'default',
  items: [],
}

export default function cart(state = defaultState, action) {
  switch (action.type) {
    case types.RECEIVE_CART_INFO:
      return {
        ...state,
        ...action.cart,
      }

    default:
      return state
  }
}
