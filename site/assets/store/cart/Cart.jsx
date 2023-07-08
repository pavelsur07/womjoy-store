import React, { useState, useEffect } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import { fetchCart, updateQuantity, clearCart, removeFromCart } from './store/cartSlice';

// Компонент корзины
const Cart = () => {
    const dispatch = useDispatch();
    const cart = useSelector((state) => state.cart);
    const [contactInfo, setContactInfo] = useState({ email: '', phone: '', name: '', address: '' });
    const [paymentMethod, setPaymentMethod] = useState('');
    const [deliveryMethod, setDeliveryMethod] = useState('');

    useEffect(() => {
        dispatch(fetchCart());
    }, [dispatch]);

    const handleQuantityChange = (productId, quantity) => {
        dispatch(updateQuantity({ productId, quantity }));
    };

    const handleClearCart = () => {
        dispatch(clearCart());
    };

    const handleRemoveFromCart = (productId) => {
        dispatch(removeFromCart(productId));
    };

    const handleCheckout = () => {
        // Отправка данных на бэкэнд для оформления заказа
        const orderData = {
            cartItems: cart.items,
            contactInfo,
            paymentMethod,
            deliveryMethod,
        };
        // Отправка данных с использованием fetch()
        fetch('/cart/checkout', {
            method: 'POST',
            body: JSON.stringify(orderData),
            headers: { 'Content-Type': 'application/json' },
        })
            .then((response) => response.json())
            .then((data) => {
                // Обработка ответа от сервера
                console.log(data);
                // Дополнительные действия после оформления заказа
            })
            .catch((error) => {
                console.error('Ошибка при оформлении заказа:', error);
                // Обработка ошибки
            });
    };

    // Проверка, заполнены ли контактные данные и адрес доставки
    const isOrderReady = contactInfo.email && contactInfo.phone && contactInfo.name && contactInfo.address;

    return (
        <>


            {cart.items.length === 0 ? (
                <p>Корзина пуста.</p>
            ) : (
                <>
                    <div className="cart__top">
                        <h1 className="cart__title title-xl">Корзина</h1>
                        <button className="cart__clear" type="button" onClick={handleClearCart}>
                            <img src="../img/icons/cart-remove.svg" alt="cart remove" width="20" height="20"/>
                            Очистить корзину
                        </button>
                    </div>
                    <div className="cart__content">
                        <div className="cart__left">
                            <div className="cart__items">
                                {cart.items.map((item) => (
                                    <div key={item.id} className="c-item">
                                        <a href={item.href} className="c-item__img">
                                            <span className="c-item__img_in">
                                                <img
                                                    src="../img/cart-item-img.png"
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
                                                    <img src="../img/icons/minus.svg" alt="minus" width="24" height="24"/>
                                                </button>
                                                <input type="text" className="c-item__inp" value={item.quantity}/>
                                                <button
                                                    className="c-item__action c-item__plus"
                                                    type="button"
                                                    onClick={() => handleQuantityChange(item.id, item.quantity + 1)}
                                                >
                                                    <img src="../img/icons/plus.svg" alt="plus" width="24" height="24"/>
                                                </button>
                                            </div>
                                            <div className="c-item__price">
                                                <span className="c-item__cost">{ item.price_list } {item.currency}</span>
                                                <del className="c-item__disc">{ item.price_old } {item.currency}</del>
                                            </div>
                                        </div>
                                    </div>

                                    /*<div key={item.id} className='c-item'>

                                    </div>*/


                                ))}
                            </div>

                            {/*<div>
                                <button onClick={handleClearCart}>Очистить корзину</button>
                                <hr />
                                <h2>Данные о покупателе</h2>
                                <input
                                    type="text"
                                    placeholder="Email"
                                    value={contactInfo.email}
                                    onChange={(e) => setContactInfo({ ...contactInfo, email: e.target.value })}
                                />
                                <input
                                    type="text"
                                    placeholder="Телефон"
                                    value={contactInfo.phone}
                                    onChange={(e) => setContactInfo({ ...contactInfo, phone: e.target.value })}
                                />
                                <input
                                    type="text"
                                    placeholder="Имя"
                                    value={contactInfo.name}
                                    onChange={(e) => setContactInfo({ ...contactInfo, name: e.target.value })}
                                />
                                <input
                                    type="text"
                                    placeholder="Адрес доставки"
                                    value={contactInfo.address}
                                    onChange={(e) => setContactInfo({ ...contactInfo, address: e.target.value })}
                                />
                                <h2>Способ оплаты</h2>
                                <select value={paymentMethod} onChange={(e) => setPaymentMethod(e.target.value)}>
                                    <option value="">Выберите способ оплаты</option>
                                    <option value="card">Кредитная карта</option>
                                    <option value="cash">Наличные</option>
                                </select>
                                <h2>Способ доставки</h2>
                                <select value={deliveryMethod} onChange={(e) => setDeliveryMethod(e.target.value)}>
                                    <option value="">Выберите способ доставки</option>
                                    <option value="courier">Курьерская доставка</option>
                                    <option value="pickup">Самовывоз</option>
                                </select>
                                <button onClick={handleCheckout} disabled={!isOrderReady}>
                                    Оформить заказ
                                </button>
                            </div>*/}
                        </div>

                        <div className="c-main">
                            <div className="c-main__title">Ваш заказ</div>
                            <ul className="c-main__list">
                                <li><span>Товары, {cart.amount} шт.</span><span>{cart.cost} р</span></li>
                                <li><span>Скидка</span><span>− {cart.discount} р</span></li>
                                <li><span>Доставка</span><span>Бесплатно</span></li>
                            </ul>
                            <div className="c-main__final">
                                Сумма заказа
                                <span className="c-main__cost">{cart.costDiscount} р</span>
                            </div>
                            <button className="c-main__btn btn-primary" type="button">Оформить заказ</button>
                            <label className="c-main__check f-check">
                                <input type="checkbox" checked hidden/>
                            <span className="f-check__sq">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M3.08709 9.08709C2.72097 9.4532 2.72097 10.0468 3.08709 10.4129L6.08709 13.4129C6.4532 13.779 7.0468 13.779 7.41291 13.4129L14.9129 5.91291C15.279 5.5468 15.279 4.9532 14.9129 4.58709C14.5468 4.22097 13.9532 4.22097 13.5871 4.58709L6.75 11.4242L4.41291 9.08709C4.0468 8.72097 3.4532 8.72097 3.08709 9.08709Z"
                                          fill="#1A1E24"/>
                                </svg>
                            </span>
                                    <span className="f-check__txt">Согласен с условиями <a href="#">Правил пользования торговой площадкой</a></span>
                            </label>
                        </div>
                    </div>
                </>
            )}

        </>
    );
};

export default Cart;
