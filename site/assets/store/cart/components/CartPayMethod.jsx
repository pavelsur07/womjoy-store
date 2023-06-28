import * as React from 'react';


export function CartPayMethod(props) {
    return (
        <div className="checkout__row">
            <div className="checkout__subtitle">Способ оплаты</div>
            <label className="pay-method">
                <input type="radio" name="pay_method" hidden checked/>
                <span className="pay-method__rd"></span>
                <div className="pay-method__main">
                    <div className="pay-method__top">
                        <span className="pay-method__name">Картой онлайн</span>
                        <div className="pay-method__imgs">
                            <img src="../img/icons/mc.svg" alt="mc" width="45"
                                 height="32"/>
                            <img src="../img/icons/mir.svg" alt="mir" width="45"
                                 height="32"/>
                            <img src="../img/icons/visa.svg" alt="visa"
                                 width="45" height="32"/>
                        </div>
                    </div>
                    <span className="gray">Без комиссии</span>
                </div>
            </label>
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
        </div>
    );
};