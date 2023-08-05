import * as types from '../constants/ActionTypes'

const defaultState = {
    customer: {
        name: '',
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
            name: 'Картой онлайн',
            description: 'Картой онлайн',
            value: 'online',
        },
        cod: {
            name: 'При получении',
            description: 'Оплата в пункте вывоза',
            value: 'cod', // cash on delivery ()
        },
    }
};

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
