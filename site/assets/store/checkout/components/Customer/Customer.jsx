import React, { useState, useEffect } from 'react';
import {useDispatch, useSelector} from "react-redux";
import {setCustomer} from "../../../redux/actions/checkout";

const Customer = () => {
    const dispatch = useDispatch();
    const customer = useSelector((state) => state.checkout.customer);

    const handleInputChange = (field, value) => {
        const values = { ...customer, [field]: value };

        dispatch(
            setCustomer(values.name, values.phone, values.email, values.comment)
        );
    };


    return (
        <div className="checkout__row">
            <div className="checkout__subtitle">Данные получателя</div>
            <form className="field-list">
                <div className="field">
                    <span className="field__ph">Ваше имя</span>
                    <input type="text" className="field__inp" placeholder="Иванов Иван Иванович"
                           value={customer.name}
                           onChange={(e) => handleInputChange('name', e.target.value)}
                    />
                </div>
                <div className="field">
                    <span className="field__ph">Номер телефона</span>
                    <input type="tel" className="field__inp phone-masked-field" placeholder="+7 000 000-00-00"
                           value={customer.phone}
                           onChange={(e) => handleInputChange('phone', e.target.value)}
                    />
                </div>
                <div className="field">
                    <span className="field__ph">Email для счёта</span>
                    <input type="text" className="field__inp" placeholder="email@example.com"
                           value={customer.email}
                           onChange={(e) => handleInputChange('email', e.target.value)}
                    />
                </div>
                <div className="field field-text">
                    <span className="field__ph">Комментарий к заказу</span>
                    <textarea className="field__inp" style={{ height: '60px' }} placeholder="Текст комментария"
                          value={customer.comment}
                          onChange={(e) => handleInputChange('comment', e.target.value)}
                    />
                </div>
            </form>
        </div>
    );
};

export default Customer;
