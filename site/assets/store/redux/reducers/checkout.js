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
    payment: 'online',
    payment_list: {
        online: {
            name: 'Оплата онлайн',
            description: 'Оплата онлайн',
            value: 'online',
        },
        cod_online: {
            name: 'Оплата при получении',
            description: 'Оплата при получении',
            value: 'cod_online', // cash on delivery ()
        },
        cod: {
            name: 'Наличными при получении',
            description: 'Оплата в пункте вывоза',
            value: 'cod', // cash on delivery ()
        },
        yandex_split: {
            name: 'Яндекс Сплит',
            description: 'Яндекс Cплит — это сервис оплаты покупок частями.',
            value: 'yandex_split',
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
