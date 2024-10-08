import React from 'react';
import {useDispatch, useSelector} from "react-redux";
import {changePayment} from "../../../redux/actions/checkout";
import {PaymentYandex} from "./PaymentYandex";

const Payment = ({heading}) => {
    const dispatch = useDispatch();
    const payment = useSelector((state) => state.checkout.payment);
    const payment_list = useSelector((state) => state.checkout.payment_list);

    const changePaymentValue = (e) => {
        dispatch(
            changePayment(e.target.value)
        )
    };

    const paymentCodChecked = payment_list.cod.value === payment;
    const paymentOnlineChecked = payment_list.online.value === payment;
    const paymentYandexSplitChecked = payment_list.yandex_split.value === payment;

    return(
        <>
            <div className="checkout__row">
                <div className="checkout__subtitle">Способ оплаты</div>

                <label className="pay-method">
                    <input
                        type="radio"
                        checked={paymentOnlineChecked}
                        value={payment_list.online.value}
                        onChange={changePaymentValue}
                        hidden
                    />
                    <span className="pay-method__rd"></span>
                    <div className="pay-method__main">
                        <div className="pay-method__top">
                            <span className="pay-method__name">
                                {payment_list.online.name}
                            </span>
                            <div className="pay-method__imgs">
                                <img src="/default/img/icons/mc.svg" alt="" width="45" height="32"/>
                                <img src="/default/img/icons/mir.svg" alt="" width="45" height="32"/>
                                <img src="/default/img/icons/visa.svg" alt="" width="45" height="32"/>
                            </div>
                        </div>
                        <span className="gray">Без комиссии</span>
                    </div>
                </label>

                <label className="pay-method">
                    <input
                        type="radio" checked={paymentYandexSplitChecked}
                        value={payment_list.yandex_split.value}
                        onChange={changePaymentValue}
                        hidden
                    />
                    <span className="pay-method__rd"></span>
                    <div className="pay-method__main">
                        <div className="pay-method__top">
                            <span className="pay-method__name">
                                {payment_list.yandex_split.name}
                            </span>
                            <div className="pay-method__imgs">
                                <img src="/default/img/icons/split-logo.svg" alt="" height="32"/>

                            </div>
                        </div>
                        <span className="gray">Без переплаты</span>
                    </div>
                </label>
            </div>
        </>
    )
}

export default Payment
