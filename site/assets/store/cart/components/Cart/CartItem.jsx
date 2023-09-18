import React, {useEffect} from 'react';
import {useDispatch, useSelector} from 'react-redux';
import {removeCartItem, updateQuantityCartItem} from "../../../redux/actions/cart";

const CartItem = ({ item }) => {
    const dispatch = useDispatch();

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
        <div key={item.id} className="c-item">
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
                        <img src="/img/icons/minus.svg" alt="minus" width="24" height="24" />
                    </button>
                    <input type="text" className="c-item__inp" readOnly={true} value={item.quantity} />
                    <button
                        className="c-item__action c-item__plus"
                        type="button"
                        onClick={() => handleQuantityChange(item.id, item.quantity + 1)}
                    >
                        <img src="/img/icons/plus.svg" alt="plus" width="24" height="24" />
                    </button>
                </div>
                <div className="c-item__price">
                    <span className="c-item__cost"> {new Intl.NumberFormat('ru-RU').format(item.price_list)} {item.currency}</span>
                    <del className="c-item__disc">{item.price_old} {item.currency}</del>
                </div>
                <div className="c-item__remove">
                    <button type="button" className="cart__clear" onClick={() => handleRemoveFromCart(item.id)}>
                        <img src="/img/icons/remove.svg" alt="remove item" width="24" height="24" />
                    </button>
                </div>
            </div>
        </div>
    );
}

export default CartItem;
