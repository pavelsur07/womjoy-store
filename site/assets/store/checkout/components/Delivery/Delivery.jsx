import React, {useState} from 'react';
import {useDispatch, useSelector} from "react-redux";
import {setDelivery} from "../../../redux/actions/checkout";

const Delivery = ({ heading, errors }) => {
    const dispatch = useDispatch();
    const delivery = useSelector((state) => state.checkout.delivery);
    const [isOpenChoicePickupPoint, setOpenChoicePickupPoint] = useState(false);

    const showChoicePickupPoint = () => {
        setOpenChoicePickupPoint(true);
    }

    const hideChoicePickupPoint = () => {
        setOpenChoicePickupPoint(false);
    }

    const handleSelectPickupPoint = ({ cdek }) => {
        const address = [cdek.cityName, cdek.address].join(', ');
        const action = setDelivery(0, address);

        dispatch(action);

        hideChoicePickupPoint();
    }

    const handleChangeDeliveryAddress = ({ target }) => {
        if (target.value.length < 10) {
            return;
        }

        dispatch(
            setDelivery(0, target.value)
        );
    }

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
            <div className="checkout__row">
                <div className="checkout__subtitle">{heading}</div>
                <div className="field-list">

                    <div className={"field " + getInputErrorClass('address')}>
                        <span className="field__ph">Адрес доставки (Курьер СДЭК)*</span>
                        <input
                            type="text"
                            className="w-field__inp field__inp"
                            name="address"
                            autoComplete="shipping street-address"
                            placeholder="Москва, ул. Ленина, дом. 1. кв. 1"
                            onChange={handleChangeDeliveryAddress}
                        />
                    </div>
                    {
                        // отрисовка блока ошибки
                        renderInputError('address')
                    }
                </div>
            </div>
        </>
    )
}

export default Delivery
