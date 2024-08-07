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
                                <img src="/pion/img/icons/split-logo.svg" alt="" height="32"/>

                            </div>
                        </div>
                        <span className="gray">Без переплаты</span>
                    </div>
                </label>


                {/*
                <label className="pay-method">
                    <input type="radio" name="pay_method" hidden/>
                    <span className="pay-method__rd"></span>
                    <div className="pay-method__main">
                        <div className="pay-method__top">
                            <span className="pay-method__name">При получении</span>
                        </div>
                        <span className="gray">Оплата в пункте вывоза</span>
                    </div>
                </label>
                */}

            </div>

            <div className="checkout__item w-border-bottom-primary2">
                <div className="py-4 w-text-lg">{heading}</div>
                <div className="pb-4">
                    <div className="pb-4">
                        <label className="w-check">
                            <input type="radio" checked={paymentOnlineChecked} value={payment_list.online.value}
                                   onChange={changePaymentValue} hidden/>
                            <span className="w-check__sq">
                            <img src="/pion/img/icons/white-check.svg" alt="check" width="18" height="13"/>
                        </span>
                            <div className="w-check__main">
                            <span className="w-check__name">
                                {payment_list.online.name}
                                {/*<img src="/pion/img/icons/mir.svg" alt="" width="60" height="40"/>
                                <img src="/pion/img/icons/visa.svg" alt="" width="60" height="40"/>
                                <img src="/pion/img/icons/mc.svg" alt="" width="60" height="40"/>
                                <img src="/pion/img/icons/union.svg" alt="" width="60" height="40"/>*/}

                            </span>
                                {/* <p>{payment_list.online.description}</p>*/}
                            </div>
                        </label>

                        {/*<label className="w-check mt-4">
                        <input type="radio" checked={paymentCodChecked} value={payment_list.cod.value} onChange={changePaymentValue} hidden />
                        <span className="w-check__sq">
                            <img src="/pion/img/icons/white-check.svg" alt="check" width="18" height="13" />
                        </span>
                        <div className="w-check__main">
                            <span className="w-check__name">{payment_list.cod.name}</span>
                            <p>{payment_list.cod.description}</p>
                        </div>
                    </label>*/}

                        <label className="w-check mt-4">
                            <input type="radio" checked={paymentYandexSplitChecked}
                                   value={payment_list.yandex_split.value}
                                   onChange={changePaymentValue} hidden/>
                            <span className="w-check__sq">
                            <img src="/pion/img/icons/white-check.svg" alt="check" width="18" height="13"/>
                        </span>
                            <div className="w-check__main">
                                <span className="w-check__name">{payment_list.yandex_split.name}</span>
                            </div>
                            <img src="/pion/img/icons/split-logo.svg" alt="" width="148" height="25"
                                 className="ms-auto"/>
                        </label>

                        {/*
                    <PaymentYandex />*
                    /}

                    <p className="mt-4  text-muted">
                        При оплате картами Visa, Mastercard и МИР, выпущенных российскими банками, рекомендуем использовать ручной ввод данных банковской карты на сайте либо воспользоваться сервисом SberPay.
                        Оплата картами Visa и Mastercard зарубежных банков недоступна.
                        Обращаем ваше внимание, что при использовании VPN сервисов могут возникнуть сложности с оплатой покупок.
                    </p>

                    {/*<div className="pay-divide p-2 p-sm-4 w-bg-lightgray mt-4">
                        <div className="d-flex justify-content-between align-items-center mb-3">
                            <span className="fs-3">6778 ₽</span>
                            <a href="#"><img src="/pion/img/icons/divide-logo.svg" alt="" width="200" height="18"/></a>
                        </div>
                        <div className="row mb-4 gx-2">
                            <div className="col col-3">
                                <span className="d-block w-100 rounded w-bg-primary2"></span>
                                <span className="d-block my-2 w-text-gray">Сегодня</span>
                                <span className="d-block">1694.5 ₽</span>
                            </div>
                            <div className="col col-3">
                                <span className="d-block w-100 rounded w-bg-darkgray"></span>
                                <span className="d-block my-2 w-text-gray">2 августа</span>
                                <span className="d-block">1694.5 ₽</span>
                            </div>
                            <div className="col col-3">
                                <span className="d-block w-100 rounded w-bg-darkgray"></span>
                                <span className="d-block my-2 w-text-gray">16 августа</span>
                                <span className="d-block">1694.5 ₽</span>
                            </div>
                            <div className="col col-3">
                                <span className="d-block w-100 rounded w-bg-darkgray"></span>
                                <span className="d-block my-2 w-text-gray">30 августа</span>
                                <span className="d-block">1694.5 ₽</span>
                            </div>
                        </div>
                        <div className="d-inline-flex">
                            Без комиссий и переплат <a href="#" className="d-block ms-2"><img src="/pion/img/icons/discount-info.svg" alt="" width="14" height="14"/></a>
                        </div>
                    </div>*/}

                    </div>
                </div>
            </div>
        </>
    )
}

export default Payment
