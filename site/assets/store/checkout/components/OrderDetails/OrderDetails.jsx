import React, { useState, useEffect } from 'react';
import {useDispatch, useSelector} from "react-redux";
import {getCartInfo} from "../../../redux/actions/cart";
import {checkout as checkoutAction} from "../../../redux/actions/checkout";

const OrderDetails = () => {
    const dispatch = useDispatch();
    const cart = useSelector((state) => state.cart);
    const [isOfferAccept, setOfferAccept] = useState(true);

    useEffect(() => {
        dispatch(
            getCartInfo()
        );
    }, [dispatch]);

    const handleChangeOfferAccept = () => {
        setOfferAccept(!isOfferAccept);
    };

    const handleCheckoutClick = () => {
        dispatch(checkoutAction())
    };

    return (
        <div className="c-main" style={{ width: '100%' }}>
            <div className="c-main__title">Ваш заказ</div>
            <ul className="c-main__list">
                <li>
                    <span>Товары, {cart.amount} шт.</span>
                    <span>{cart.cost} ₽</span>
                </li>
                {
                    (cart.discount > 0) && (
                        <li>
                            <span>Скидка</span>
                            <span>− {cart.discount} ₽</span>
                        </li>
                    )
                }
                <li>
                    <span>Доставка</span>
                    <span>Бесплатно</span>
                </li>
            </ul>
            <div className="c-main__final">
                Сумма заказа <span className="c-main__cost">{cart.discount_cost} ₽</span>
            </div>

            <button className="c-main__btn btn-primary" onClick={ handleCheckoutClick } type="button">Оформить заказ</button>

            <label className="c-main__check f-check">
                <input type="checkbox" checked={isOfferAccept} onChange={handleChangeOfferAccept} hidden />
                <span className="f-check__sq">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fillRule="evenodd" clipRule="evenodd" d="M3.08709 9.08709C2.72097 9.4532 2.72097 10.0468 3.08709 10.4129L6.08709 13.4129C6.4532 13.779 7.0468 13.779 7.41291 13.4129L14.9129 5.91291C15.279 5.5468 15.279 4.9532 14.9129 4.58709C14.5468 4.22097 13.9532 4.22097 13.5871 4.58709L6.75 11.4242L4.41291 9.08709C4.0468 8.72097 3.4532 8.72097 3.08709 9.08709Z" fill="#1A1E24" />
                    </svg>
                </span>
                <span className="f-check__txt">
                    Согласен с условиями <a href="#">Правил пользования торговой площадкой</a>
                </span>
            </label>
        </div>
    );
};

export default OrderDetails;
