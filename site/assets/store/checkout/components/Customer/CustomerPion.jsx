import React from "react";
import InputMask from 'react-input-mask';
import {useDispatch, useSelector} from "react-redux";
import {setCustomer} from "../../../redux/actions/checkout";

const CustomerPion = ({ heading, errors }) => {
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
        <>
            <div className="py-4 w-text-lg">{heading}</div>
            <div className="pb-4">
                <div className="pb-4 w-border-bottom-primary2">
                    <div className="row">
                        <div className="col col-12 col-md-12">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph d-none d-md-block">ФИО*</label>
                                <div className={"w-field__main " + getInputErrorClass('name')}>
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
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
                            </div>
                        </div>

                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph d-none d-md-block">EMAIL*</label>
                                <div className={"w-field__main " + getInputErrorClass('email')}>
                                    <input
                                        type="email"
                                        name="email"
                                        className="w-field__inp field__inp"
                                        placeholder="email@example.com"
                                        value={customer.email}
                                        onChange={(e) => handleInputChange('email', e.target.value)}
                                    />
                                </div>
                                {
                                    // отрисовка блока ошибки
                                    renderInputError('email')
                                }
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph d-none d-md-block">ТЕЛЕФОН*</label>
                                <div className={"w-field__main " + getInputErrorClass('phone')}>
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
                                                    className="w-field__inp field__inp"
                                                    placeholder="+7 (000) 000-00-00"
                                                />
                                            )
                                        }
                                    </InputMask>
                                </div>
                                {
                                    // отрисовка блока ошибки
                                    renderInputError('phone')
                                }
                            </div>
                        </div>
                        <div className="col col-12">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph d-none d-md-block">КОММЕНТАРИИ К ЗАКАЗУ</label>
                                <div className={"w-field__main " + getInputErrorClass('comment')}>
                                    <textarea
                                        className="w-field__textarea field__inp"
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
                    </div>

                    {/*
                    <div className="w-text">
                        <p><b>Сервис «Womjoy в подарок»</b></p>
                        <p>По вашему запросу мы можем:</p>
                        <ul className="disced">
                            <li>упаковать изделия в несколько разных коробок</li>
                            <li>упаковать изделия в <a href="#">подарочную коробку</a></li>
                            <li>убрать бирки с ценой и товарный чек из упаковки</li>
                            <li>не звонить получателю заранее и сохранить ваш заказ в тайне до момента его вручения</li>
                        </ul>
                        <p>Подарочная упаковка приобретается отдельно.</p>
                        <p>
                            Просто укажите в комментарии к заказу ваши пожелания и мы их исполним! <br/>
                            Не забудьте дополнительно оставить свои контакты в комментарии к заказу.
                        </p>
                    </div>
                    */}
                </div>
                {/*
                 <div className="pt-4 d-flex justify-content-between">
                    <button className="w-empty-btn" type="button">Назад</button>
                    <button className="w-primary-btn d-none d-lg-block" type="button">Оформить заказ</button>
                </div>
                */}
            </div>
        </>
    )
}

export default CustomerPion
