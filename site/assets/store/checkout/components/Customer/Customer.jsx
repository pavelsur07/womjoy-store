import React, {useEffect} from "react";
import InputMask from 'react-input-mask';
import {useDispatch, useSelector} from "react-redux";
import {setCustomer} from "../../../redux/actions/checkout";

const Customer = ({ heading, errors }) => {
    const dispatch = useDispatch();
    const customer = useSelector((state) => state.checkout.customer);


    const handleInputChange = (field, value) => {
        let values = { ...customer, [field]: value };

        dispatch(
            setCustomer(values.name, values.phone, values.email, values.comment)
        );
    };

    const getInputErrorClass = (name) => {
        return (errors[name]?._errors) ? 'error border-danger' : '';
    };

    const renderInputError = (name) => {
        let error = null;

        if (errors[name]) {
            error = errors[name]?._errors;
        }

        return <div className="w-field__error text-danger">{error}&nbsp;</div>;
    };

    return (

            <div className="checkout__row checkout__customer-info">
                <div className="checkout__subtitle">{heading}</div>

                <div className="field-list">
                    <div className={"field " + getInputErrorClass('name')}>
                        <span className="field__ph">Ваше имя</span>
                        <input
                            type="text"
                            className="field__inp"
                            name="name"
                            placeholder="Иванов Иван Иванович"
                            value={customer.name}
                            onChange={(e) => handleInputChange('name', e.target.value)}
                        />
                    </div>
                    {
                        // отрисовка блока ошибки
                        renderInputError('name')
                    }

                    <div className="field">
                        <span className="field__ph">Email для счёта</span>
                        <input
                            type="email"
                            name="email"
                            className="field__inp"
                            placeholder="email@example.com"
                            value={customer.email}
                            onChange={(e) => handleInputChange('email', e.target.value)}
                        />
                    </div>
                    {
                        // отрисовка блока ошибки
                        renderInputError('email')
                    }

                    <div className="field">
                        <span className="field__ph">Номер телефона</span>
                        <InputMask
                            mask="+7 (999) 999-99-99"
                            onChange={(e) => handleInputChange('phone', e.target.value.replace('_', ''))}
                            value={customer.phone}
                        >
                            {
                                _ => (
                                    <input
                                        type="tel"
                                        name='phone'
                                        className="field__inp"
                                        placeholder="+7 (000) 000-00-00"
                                    />
                                )
                            }
                        </InputMask>
                    </div>


                    <div className={"field field-text" + getInputErrorClass('comment')}>
                        <span className="field__ph">Комментарий к заказу</span>
                        <textarea
                            className="field__inp"
                            value={customer.comment}
                            onChange={(e) => handleInputChange('comment', e.target.value)}
                            placeholder="Текст комментария"
                        />
                    </div>
                    {
                        // отрисовка блока ошибки
                        renderInputError('comment')
                    }
                </div>
            </div>

    )
}

export default Customer
