import React, {useEffect} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import {removeCartItem, updateQuantityCartItem} from "../../../redux/actions/cart";

const CartItemPion = ({ item }) => {
    const dispatch = useDispatch();

    const demoPoint = {
        background: '#0b0a12',
    }

    const handleQuantityChange = (productId, quantity) => {
        if(quantity < 1) {
            return;
        }

        dispatch(
            updateQuantityCartItem(productId, quantity)
        );
    };

    const handleRemoveFromCart = (productId) => {
        dispatch(removeCartItem(productId));
    };


    return (
        <div className="cart__row d-flex position-relative align-items-center justify-content-between flex-wrap">
            <div className="cart__left mb-3 mb-md-0 flex-column flex-lg-row align-items-start align-items-lg-center">
                <a href={item.href} className="cart__img d-block mb-0 mb-md-3 mb-lg-0 me-0 me-lg-3">
                    <img
                        src={item.thumbnail}
                        alt={item.name}
                        width="106" height="160"
                    />
                </a>
                <div className="cart__name">
                    <span className="cart__art">арт. W4020001</span>
                    <a href={item.href}>{item.name} / {item.value}</a>
                </div>
            </div>

            <div className="cart__cell">
                <div className="cart__color" style={demoPoint}></div>
            </div>

            <div className="cart__cell">
                <div className="cart__size">
                    <div className="w-sel w-sel-lg w-sel-dark">
                        <div className="w-sel__trigger">
                            {item.value}
                        </div>
                        {/*<div className="w-sel__dropdown">
                            <label className="w-sel__item">
                                <input type="radio" name="card-size-1" checked hidden/>
                                <span></span>
                                XS/S
                            </label>
                            <label className="w-sel__item">
                                <input type="radio" name="card-size-1" hidden/>
                                <span></span>
                                M/L
                            </label>
                            <label className="w-sel__item">
                                <input type="radio" name="card-size-1" hidden/>
                                <span></span>
                                XL/XXL
                            </label>
                        </div>*/}
                    </div>
                </div>
            </div>

            <div className="cart__cell">
                <div className="cart__cost c-item__cost">
                    {new Intl.NumberFormat('ru-RU').format(item.price_list)} {item.currency}
                </div>
            </div>

            <div className="cart__cell">
                <div className="cart__cnt c-item__actions">
                    <button
                        className="cart__cnt_action cart__cnt_minus c-item__action c-item__minus"
                        type="button"
                        onClick={() => handleQuantityChange(item.id, item.quantity - 1)}
                    >
                    </button>

                    <input
                        className="cart__cnt_inp c-item__inp"
                        type="text"
                        readOnly={true}
                        value={item.quantity}
                    />
                    <button
                        className="cart__cnt_action cart__cnt_plus c-item__action c-item__plus"
                        type="button"
                        onClick={() => handleQuantityChange(item.id, item.quantity + 1)}
                    >
                    </button>
                </div>
            </div>

            <div className="cart__cell mt-3 mt-md-0">
                <div className="cart__cost c-item__cost">
                    {new Intl.NumberFormat('ru-RU').format(item.price_list * item.quantity )} {item.currency}
                </div>
            </div>

            <div className="cart__cell c-item__remove">
                <button
                    className="cart__remove cart__clear"
                    type="button"
                    onClick={() => handleRemoveFromCart(item.id)}
                >
                    <img src="/pion/img/icons/remove.svg" alt="remove item" width="28" height="30"/>
                </button>
            </div>
        </div>

        /*<div key={item.id} className="c-item" data-product-id={item.product_id}>
            <a href={item.href} className="c-item__img">
                <span className="c-item__img_in">
                    <img
                        src={item.thumbnail}
                        alt={item.name}
                        width="83"
                        height="110"
                    />
                </span>
            </a>
            <div className="c-item__main">
                <div className="c-item__text">
                    <a href={item.href} className="c-item__name"> {item.name} </a>
                    <span className="c-item__size">Размер: {item.value}</span>
                </div>
                <div className="c-item__actions">
                    <button
                        className="c-item__action c-item__minus"
                        type="button"
                        onClick={() => handleQuantityChange(item.id, item.quantity - 1)}
                    >
                        <img src="/img_/icons/minus.svg" alt="minus" width="24" height="24" />
                    </button>
                    <input type="text" className="c-item__inp" readOnly={true} value={item.quantity} />
                    <button
                        className="c-item__action c-item__plus"
                        type="button"
                        onClick={() => handleQuantityChange(item.id, item.quantity + 1)}
                    >
                        <img src="/img_/icons/plus.svg" alt="plus" width="24" height="24" />
                    </button>
                </div>
                <div className="c-item__price">
                    <span className="c-item__cost"> {new Intl.NumberFormat('ru-RU').format(item.price_list)} {item.currency}</span>
                    <del className="c-item__disc">{item.price_old} {item.currency}</del>
                </div>
                <div className="c-item__remove">
                    <button type="button" className="cart__clear" onClick={() => handleRemoveFromCart(item.id)}>
                        <img src="/img_/icons/remove.svg" alt="remove item" width="24" height="24" />
                    </button>
                </div>
            </div>
        </div>*/
    );
}

export default CartItemPion;
