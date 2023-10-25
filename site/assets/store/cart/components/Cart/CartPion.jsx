import React, {useEffect} from 'react'
import {useDispatch, useSelector} from 'react-redux'
import {clearCart, getCartInfo} from "../../../redux/actions/cart"
import CartItemPion from "./CartItemPion"
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
            <div className="p-3 bg-danger bg-opacity-10 text-danger">{t('Cart_not_item')}</div>
        );
    }

    return (
        <>
            <section className="cart w-section">
                <h1 className="mb-5 text-uppercase">{t('Cart')}</h1>

                <div className="border-bottom pb-5 mb-5">
                    <div className="mb-4 d-none d-md-flex position-relative align-items-center justify-content-between">
                        <span className="cart__head text-start text-lg-center">Наименование товара</span>
                        <span className="cart__head">Цвет</span>
                        <span className="cart__head">Размер</span>
                        <span className="cart__head">Цена</span>
                        <span className="cart__head">Количество</span>
                        <span className="cart__head">Сумма</span>
                        <span className="cart__head"></span>
                    </div>
                    <div className="cart__rows">

                        {
                            cart.items.map(
                                (item, index) => (
                                    <CartItemPion key={index} item={item} />
                                )
                            )
                        }

                    </div>
                </div>

                <div className="row justify-content-between align-items-center align-items-lg-start flex-column flex-lg-row">
                    <div className="col-12 col-sm-10 col-md-8 col-lg-3 d-flex flex-column align-items-center align-items-lg-start order-3 order-lg-1">
                        <a href="#" className=" d-inline-block mb-3 w-black-link">УСЛОВИЯ ДОСТАВКИ</a>
                        <a href="#" className=" d-inline-block mb-3 w-black-link">ОПЛАТА ЗАКАЗОВ</a>
                        <a href="#" className=" d-inline-block mb-3 w-black-link">ВОЗВРАТ ТОВАРА</a>

                        <p>Если у вас остались вопросы, свяжитесь с нами по бесплатному номеру телефона <a href="#" className="w-black-link">8-800-000-00-00</a></p>
                    </div>
                    <p className="col-12 col-sm-10 col-md-8 col-lg-3 w-text-lg order-1 order-lg-2">
                        ПОДАРОЧНЫЙ СЕРТИФИКАТ <br/>
                        <a href="#">Войдите</a>
                         или зарегистрируйтесь, чтобы применить купон или начислить баллы за покупку.
                    </p>
                    <div className="col-12 col-sm-10 col-md-8 col-lg-3 order-2 order-lg-3 mb-3 mb-lg-0">
                        <ul className="c-main__list">
                            <li className="d-flex mb-4">
                                <span>ТОВАРЫ:</span> <span className="d-block ms-auto">{new Intl.NumberFormat('ru-RU').format(cart.cost)} р.</span>
                            </li>
                        </ul>
                        <div className="d-flex mb-4 w-text-lg fw-bold c-main__final">
                            ИТОГО: <span className="d-block ms-auto c-main__cost">{new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} р.</span>
                        </div>

                        <button
                            type="button"
                            onClick={() => handleCheckoutClick()}
                            className="w-primary-btn w-100 c-main__btn btn-primary">
                            {t('Checkout')}
                        </button>

                    </div>
                </div>

            </section>
        </>
    );
};

export default Cart;
