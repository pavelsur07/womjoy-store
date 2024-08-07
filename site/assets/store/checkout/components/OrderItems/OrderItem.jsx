// Корневой компонент приложения
import React from 'react';


const OrderItemPion = ({item}) => {

    const demoPoint = {
        background: '#0b0a12',
    }

    return(

        <>
            <div className="c-item">
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
                        <a href={item.href} className="c-item__name">{item.name}</a>
                        <span className="c-item__size">Размер: {item.value}</span>
                    </div>
                    <div className="c-item__actions">
                        <p>{item.quantity} шт.</p>
                    </div>
                    <div className="c-item__price">
                        <span className="c-item__cost">
                             {new Intl.NumberFormat('ru-RU').format(item.price_list)} {item.currency}
                        </span>
                        <span className="c-item__cost">
                            {new Intl.NumberFormat('ru-RU').format(item.price_list * item.quantity )} {item.currency}
                        </span>
                    </div>
                    <div className="c-item__remove">
                    </div>
                </div>
            </div>
        </>

        /*<div className="cart__row d-flex position-relative align-items-center justify-content-between flex-wrap">
            <div className="cart__left mb-3 mb-md-0 flex-column flex-lg-row align-items-start align-items-lg-center">
                <a href={item.href} className="cart__img d-block mb-0 mb-md-3 mb-lg-0 me-0 me-lg-3">
                    <img
                        src={item.thumbnail}
                        alt={item.name}
                        width="106" height="160"
                    />
                </a>
                <div className="cart__name">
                    <span className="cart__art">арт. {item.article}</span>
                    <a href={item.href}>Велосипедки удлиненные в рубчик с широким поясом</a>
                </div>
            </div>
            <div className="cart__cell">
                <div className="cart__color" style={{ background: item.color_value }}></div>
            </div>
            <div className="cart__cell">
                <div className="cart__size">
                    <span className="w-text-sm d-inline-block d-md-none opacity-75">Размер: </span>
                    {item.value}
                </div>
            </div>
            <div className="cart__cell">
                <div className="cart__cost">
                    {new Intl.NumberFormat('ru-RU').format(item.price_list)} {item.currency}
                </div>
            </div>
            <div className="cart__cell">
                <span className="w-text-sm d-inline-block d-md-none  opacity-75">Количество: </span>
                {item.quantity} шт
            </div>
            <div className="cart__cell">
                <div className="cart__cost">
                    <span className="w-text-sm d-inline-block d-md-none  opacity-75">Сумма: </span>
                    {new Intl.NumberFormat('ru-RU').format(item.price_list * item.quantity )} {item.currency}
                </div>
            </div>
        </div>*/
    )
}

export default OrderItemPion
