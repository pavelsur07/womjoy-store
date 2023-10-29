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
                {/*<Delivery />*/}

                {
                    delivery.address && (
                        <>
                            <div className="pvz-block__title">
                                Пункт выдачи СДЭК <img src="/img_/icons/sdek-check.svg" alt="" width="24" height="24" />
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
                                    className="w-primary-btn"
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
                    <div className={'modal' + (isOpenChoicePickupPoint ? ' active' : '')} id="my-data">
                        <div className="modal__bg"></div>
                        <div className="modal__content" style={{ width: '80%' }}>
                            <button className="modal__close" type="button"  onClick={() => hideChoicePickupPoint()}>
                                <img src="/img_/icons/close-black.svg" alt="close black" width="24" height="24"/>
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
        </div>
    )
}

export default DeliveryPion