import * as types from '../constants/ActionTypes'

const defaultState = {
  customer: {
    name: '',
    lastName: '',
    firstName: '',
    phone: '',
    email: '',
    comment: '',
  },
  delivery: {
    price: 0,
    address: null,
  },
  payment: 'cod',
  payment_list: {
    online: {
      name: 'Банковской картой',
      description: 'Банковской картой',
      value: 'online',
    },
    cod_online: {
      name: 'Банковской картой при получении',
      description: 'Банковской картой при получении',
      value: 'cod_online', // cash on delivery ()
    },
    cod: {
      name: 'Наличными при получении',
      description: 'Оплата в пункте вывоза',
      value: 'cod', // cash on delivery ()
    },
  },
}

export default function checkout(state = defaultState, action) {
  switch (action.type) {
    case types.SET_DELIVERY:
      return {
        ...state,
        delivery: action.delivery,
      }
    case types.CHANGE_PAYMENT:
      return {
        ...state,
        payment: action.payment,
      }
    case types.SET_CUSTOMER:
      return {
        ...state,
        customer: action.customer,
      }
    default:
      return state
  }
}
