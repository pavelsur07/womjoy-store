import React from 'react';
import {useDispatch, useSelector} from "react-redux";
import {changePayment} from "../../../redux/actions/checkout";

const Payment = () => {
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

    return (
        <div className="checkout__row">
            <div className="checkout__subtitle">Способ оплаты</div>
            <label className="pay-method">
                <input type="radio" checked={paymentOnlineChecked} value={payment_list.online.value} onChange={changePaymentValue} hidden />
                <span className="pay-method__rd" />
                <div className="pay-method__main">
                    <div className="pay-method__top">
                        <span className="pay-method__name">{payment_list.online.name}</span>
                        <div className="pay-method__imgs">
                            <img src="/img/icons/mc.svg" alt="" width="45" height="32" />
                            <img src="/img/icons/mir.svg" alt="" width="45" height="32"/>
                            <img src="/img/icons/visa.svg" alt="" width="45" height="32"/>
                        </div>
                    </div>
                    <span className="gray">{payment_list.online.description}</span>
                </div>
            </label>
            <label className="pay-method">
                <input type="radio" checked={paymentCodChecked} value={payment_list.cod.value} onChange={changePaymentValue} hidden />
                <span className="pay-method__rd" />
                <div className="pay-method__main">
                    <div className="pay-method__top">
                        <span className="pay-method__name">{payment_list.cod.name}</span>
                    </div>
                    <span className="gray">{payment_list.cod.description}</span>
                </div>
            </label>
        </div>
    );
};

export default Payment;
