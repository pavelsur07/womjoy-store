import React, { useState, useEffect } from 'react';
import Delivery from "./Delivery";
import {useDispatch, useSelector} from "react-redux";
import {setDelivery} from "../../../redux/actions/checkout";
import DeliveryCDEKPickUpPoint from "./DeliveryCDEKPickUpPoint";

const DeliveryPion = () => {
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

    return(
        <div className="checkout__item w-border-bottom-primary2">
            <div className="py-4 w-text-lg">4. ДОСТАВКА</div>
            <div className="pb-4">

                <div className="pb-4">
                    <div className="row">
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">Регион*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
                                        name="region"
                                        placeholder="Московская обл"
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">Город*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
                                        name="city"
                                        placeholder="Москва"
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">Улица*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
                                        name="street"
                                        placeholder="Ленина"
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="col col-6 col-md-3">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">Дом*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
                                        name="street"
                                        placeholder="99"
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="col col-6 col-md-3">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">Квартиры*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
                                        name="street"
                                        placeholder="99"
                                    />
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                {/*<div className="pb-4">
                    <div className="row">
                        <div className="col col-12 col-md-6">
                            <div className="w-field mb-4">
                                <label className="w-field__label">СТРАНА *</label>
                                <div className="w-sel w-sel-lg w-sel-dark">
                                    <div className="w-sel__trigger">
                                        Россия
                                    </div>
                                    <div className="w-sel__dropdown">
                                        <label className="w-sel__item">
                                            <input type="radio" name="sel-1" checked="" hidden=""/>
                                            <span></span>
                                            Россия
                                        </label>
                                        <label className="w-sel__item">
                                            <input type="radio" name="sel-1" hidden=""/>
                                            <span></span>
                                            Беларусь
                                        </label>
                                        <label className="w-sel__item">
                                            <input type="radio" name="sel-1" hidden=""/>
                                            <span></span>
                                            Казахстан
                                        </label>
                                        <label className="w-sel__item">
                                            <input type="radio" name="sel-1" hidden=""/>
                                            <span></span>
                                            Кыргызстан
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div className="w-field">
                                <label className="w-field__label">НАСЕЛЕННЫЙ ПУНКТ *</label>
                                <div className="w-field__main"><input type="text" className="w-field__inp"
                                                                      placeholder="Введите название"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>*/}

                {
                    delivery.address && (
                        <>
                            <div className="pvz-block__title">
                                Пункт выдачи СДЭК <img src="/img_/icons/sdek-check.svg" alt="" width="24" height="24"/>
                            </div>
                            <div className="pvz-block__info">
                                <span>{delivery.address}</span>
                            </div>
                            <div className="pt-4 d-flex justify-content-between">
                                <button
                                    className="w-primary-btn"
                                    type="button"
                                    onClick={() => showChoicePickupPoint()}
                                >
                                    Изменить
                                </button>
                            </div>
                        </>

                    )
                }
                {
                    !delivery.address && (
                        <>
                            <div className="p-3 bg-danger bg-opacity-10 text-danger">Пункт выдачи не выбран</div>
                            <div className="pt-4 d-flex justify-content-between">
                                <button
                                    className="w-primary-btn w-100 c-main__btn btn-primary"
                                    type="button"
                                    onClick={() => showChoicePickupPoint()}
                                >
                                    Выбрать пункт выдачи
                                </button>
                            </div>
                        </>
                    )
                }

                <>
                    <div className={'modal modal-lg' + (isOpenChoicePickupPoint ? ' active' : '')} id="my-data">
                        <div className="modal__bg"></div>
                        <div className="modal__content" style={{width: '100%'}}>

                            <div className="modal__top">
                                <buttno className="modal__close ms-auto" type="button" onClick={() => hideChoicePickupPoint()}>
                                    <img src="/pion/img/icons/close.svg" alt="close"/>
                                </buttno>
                            </div>

                            <DeliveryCDEKPickUpPoint
                                defaultCity={'Москва'}
                                onSelectPickupPoint={handleSelectPickupPoint}
                            />
                        </div>
                    </div>
                </>
            </div>
        </div>
    )
}

export default DeliveryPion