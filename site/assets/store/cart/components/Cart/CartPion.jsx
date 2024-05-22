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
            <h2 className="mt-6">{t('Cart_not_item')}</h2>
        );
    }

    return (
        <>

            <section className="cart w-section">
                <div className="row position-relative align-items-start">
                    <div className="col col-12 col-lg-8 d-flex flex-column">
                        <h1 className="mb-5 text-uppercase">КОРЗИНА</h1>

                        <div className="border-bottom pb-5 mb-5">
                            <div
                                className="mb-4 d-none d-md-flex position-relative align-items-center justify-content-between">
                                <span className="cart__head text-start text-lg-center">Наименование товара</span>
                                <span className="cart__head">Размер</span>
                                <span className="cart__head">Цена</span>
                                <span className="cart__head">Кол-во</span>
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
                    </div>

                    <div className="col col-12 col-sm-9 col-md-6 col-lg-4 position-sticky mx-auto top-0">
                        <div className="d-flex flex-column align-items-start p-4 p-sm-5 w-bg-primary">
                            <div className="fs-2 lh-1 mb-4">Ваш заказ</div>
                            <ul className="lined-list w-100">
                                <li>
                                    <span>ТОВАРЫ:</span>
                                    <span>
                                        {new Intl.NumberFormat('ru-RU').format(cart.cost)} ₽
                                    </span>
                                </li>
                                <li>
                                    <span>ДОСТАВКА:</span>
                                    {
                                        // цена доставки
                                        cart.delivery_cost > 0 && (
                                            <span>{cart.delivery_cost} ₽</span>
                                        )
                                    }
                                    {
                                        // бесплатно
                                        !cart.delivery_cost && (
                                            <span>БЕСПЛАТНО</span>
                                        )
                                    }
                                </li>
                                <li className="fw-bold"><span>ИТОГО:</span><span>
                                    {new Intl.NumberFormat('ru-RU').format(cart.discount_cost)}
                                </span></li>
                            </ul>
                            <div className="w-field w-100 mb-4">
                                <div className="w-field__main bg-white">
                                    <input type="text" className="w-field__inp" placeholder="Введите промокод"/>
                                    <div className="w-field__append">
                                        <button type="button" className="w-text-action">Применить</button>
                                    </div>
                                </div>
                            </div>
                            <button
                                className="w-action-btn w-100 c-main__btn"
                                onClick={() => handleCheckoutClick()}
                            >
                                {t('Checkout')}
                            </button>
                            {/*<div className="fs-5 mb-2 mt-3">Подарочный сертификат</div>*/}
                            <p className="mt-3">
                                <a href="/login">Войдите</a> или <a href="/registration/">зарегистрируйтесь</a>, чтобы применить купон или
                                начислить баллы за покупку.
                            </p>
                            <p>Если у вас остались вопросы, свяжитесь с нами по бесплатному номеру телефона <a href="tel:8-800-301-67-53" className="w-black-link">8-800-301-67-53</a>
                            </p>
                        </div>
                    </div>
                </div>
            </section>


            {/* <section className="cart w-section pt-2">
                <h1 className="mb-3 text-uppercase">{t('Cart')}</h1>

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
                        <a href="https://womjoy.ru/pages/delivery" className=" d-inline-block mb-3 w-black-link">УСЛОВИЯ ДОСТАВКИ</a>
                        <a href="https://womjoy.ru/pages/payment" className=" d-inline-block mb-3 w-black-link">ОПЛАТА ЗАКАЗОВ</a>
                        <a href="https://womjoy.ru/pages/garantiya-vozvrata-i-obmena" className=" d-inline-block mb-3 w-black-link">ВОЗВРАТ ТОВАРА</a>

                        <p>Если у вас остались вопросы, свяжитесь с нами по бесплатному номеру телефона <a href="#" className="w-black-link">8-800-301-67-53</a></p>
                    </div>
                    <div className="col-12 col-sm-10 col-md-8 col-lg-3 order-1 order-lg-2">
                        <p className="w-text-lg ">ПОДАРОЧНЫЙ СЕРТИФИКАТ </p>
                        <p>
                        <a href="/login">Войдите</a>&nbsp;
                         или <a href="/registration/">зарегистрируйтесь</a>, чтобы применить купон или начислить баллы за покупку.
                        </p>
                    </div>
                    <div className="col-12 col-sm-10 col-md-8 col-lg-3 order-2 order-lg-3 mb-3 mb-lg-0">
                        <ul className="c-main__list">
                            <li className="d-flex mb-4">
                                <span>ТОВАРЫ:</span> <span
                                className="d-block ms-auto">{new Intl.NumberFormat('ru-RU').format(cart.cost)} ₽</span>
                            </li>
                            <li className="d-flex mb-4">
                                <span>ДОСТАВКА:</span>
                                <span className="d-block ms-auto">
                                    {
                                        // цена доставки
                                        cart.delivery_cost > 0 && (
                                            <span>{cart.delivery_cost} ₽</span>
                                        )
                                    }
                                    {
                                        // бесплатно
                                        !cart.delivery_cost && (
                                            <span>БЕСПЛАТНО</span>
                                        )
                                    }
                                </span>
                            </li>
                            <li className="d-flex mb-4">
                                <span>ПРОМОКОД:</span>
                                <span className="d-block ms-auto">
                                    {
                                        // цена доставки
                                        cart.promo_code_discount > 0 && (
                                            <span>{cart.promo_code_discount} ₽</span>
                                        )
                                    }
                                    {
                                        // бесплатно
                                        !cart.promo_code_discount && (
                                            <span>- 0.00 ₽</span>
                                        )
                                    }
                                </span>
                            </li>
                        </ul>
                        <div className="d-flex mb-4 w-text-lg fw-bold c-main__final">
                            ИТОГО: <span
                            className="d-block ms-auto c-main__cost">{new Intl.NumberFormat('ru-RU').format(cart.discount_cost)} ₽</span>
                        </div>

                        <button
                            type="button"
                            onClick={() => handleCheckoutClick()}
                            className="w-action-btn w-100 c-main__btn btn-primary">
                            {t('Checkout')}
                        </button>

                    </div>
                </div>

            </section>*/}
        </>
    );
};

export default Cart;
