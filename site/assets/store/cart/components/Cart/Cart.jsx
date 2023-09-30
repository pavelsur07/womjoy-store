import React, {useEffect} from 'react'
import {useDispatch, useSelector} from 'react-redux'
import {clearCart, getCartInfo} from "../../../redux/actions/cart"
import CartItem from "./CartItem";
import { useTranslation } from 'react-i18next'

// Компонент корзины
const Cart = () => {
    const { t } = useTranslation();
    const dispatch = useDispatch();
    const cart = useSelector((state) => state.cart);

    useEffect(() => {
        dispatch(getCartInfo());
    }, [dispatch]);

    const handleClearCart = () => {
        dispatch(clearCart());
    };

    const handleCheckoutClick = () => {
        window.location = '/cart/checkout/';
    };

    if (cart.items.length < 1) {
        return (
            <p>Корзина пуста.</p>
        );
    }

    return (
        <>
            <div className="cart__top">
                <h1 className="cart__title title-xl">{t('Cart')}</h1>

                <button className="cart__clear" type="button" onClick={handleClearCart}>
                    <img src="/img_/icons/cart-remove.svg" alt="cart remove" width="20" height="20" />
                    Очистить корзину
                </button>
            </div>
            <div className="cart__content">
                <div className="cart__left">
                    <div className="cart__items">
                        {
                            cart.items.map(
                                (item, index) => (
                                    <CartItem key={index} item={item} />
                                )
                            )
                        }
                    </div>
                </div>

                <div className="c-main">
                    <div className="c-main__title">Ваш заказ</div>
                    <ul className="c-main__list">
                        <li>
                            <span>Товары, {cart.amount} шт.</span>
                            <span>{new Intl.NumberFormat('ru-RU').format(cart.cost)} р.</span>
                        </li>
                        {
                            (cart.discount > 0) && (
                                <li>
                                    <span>Скидка</span>
                                    <span>− {new Intl.NumberFormat('ru-RU').format(cart.discount)} р.</span>
                                </li>
                            )
                        }
                        <li>
                            <span>Доставка</span>
                            <span>Бесплатно</span>
                        </li>
                    </ul>
                    <div className="c-main__final">
                        Сумма заказа
                        <span className="c-main__cost">{new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} р.</span>
                    </div>
                    <button
                        type="button"
                        onClick={() => handleCheckoutClick()}
                        className="c-main__btn btn-primary">
                        {t('Checkout')}
                    </button>
                </div>
            </div>
        </>
    );
};

export default Cart;
