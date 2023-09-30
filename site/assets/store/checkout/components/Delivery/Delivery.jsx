import React, { useState, useEffect } from 'react';
import {useDispatch, useSelector} from "react-redux";
import DeliveryCDEKPickUpPoint from "./DeliveryCDEKPickUpPoint";
import {setDelivery} from "../../../redux/actions/checkout";

const Delivery = () => {
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

    return (
        <div className="checkout__row">
            <div className="checkout__subtitle">Способ доставки</div>
            {/*<div className="pvz-block">
                <div className="pvz-block__text">
                    <div className="pvz-block__title">Пункт выдачи
                        СДЭК <img src="/img/icons/sdek-check.svg" alt="" width="24" height="24" />
                    </div>
                    <div className="pvz-block__info">
                        <span className="pvz-block__cost">500 ₽</span>
                        <span>На Космонавтов 32</span>
                    </div>
                </div>
                <button className="pvz-block__btn" type="button">Изменить</button>
            </div>*/}

            {
                delivery.address && (
                    <div className="pvz-block">
                        <div className="pvz-block__text">
                            <div className="pvz-block__title">
                                Пункт выдачи СДЭК <img src="/img_/icons/sdek-check.svg" alt="" width="24" height="24" />
                            </div>
                            <div className="pvz-block__info">
                                {/*<span className="pvz-block__cost">500 ₽</span>*/}
                                <span>{delivery.address}</span>
                            </div>
                        </div>
                        <button className="pvz-block__btn" onClick={() => showChoicePickupPoint()} type="button">Изменить</button>
                    </div>
                )
            }
            {
                !delivery.address && (
                    <div className="pvz-block">
                        <div className="pvz-block__text">
                            <div className="pvz-block__title" style={{ marginBottom: 0}}>
                                Пункт выдачи не выбран
                            </div>
                        </div>
                        <button className="btn-black" onClick={() => showChoicePickupPoint()} type="button">Выбрать пункт выдачи</button>
                    </div>
                )
            }

            <>
                <div className={'modal' + (isOpenChoicePickupPoint ? ' active' : '')} id="my-data">
                    <div className="modal__bg"></div>
                    <div className="modal__content" style={{ width: '80%' }}>
                        <button className="modal__close" type="button"  onClick={() => hideChoicePickupPoint()}>
                            <img src="/img_/icons/close-black.svg" alt="" width="24" height="24"/>
                        </button>

                        <h2 className="modal__title">
                            Выберите способ получения
                        </h2>

                        <DeliveryCDEKPickUpPoint
                            defaultCity={'Москва'}
                            onSelectPickupPoint={handleSelectPickupPoint}
                        />
                    </div>
                </div>
            </>
        </div>
    );

};

export default Delivery;
