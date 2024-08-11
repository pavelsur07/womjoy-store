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
                    <div className="c-item__xprice">
                        {new Intl.NumberFormat('ru-RU').format(item.price_list)} {item.currency} x {item.quantity} шт.
                    </div>
                    <div className="c-item__price">
                        <span className="c-item__cost">
                             {new Intl.NumberFormat('ru-RU').format(item.price_list * item.quantity )} {item.currency}
                        </span>
                        <del className="c-item__disc">
                            {new Intl.NumberFormat('ru-RU').format(item.price_old * item.quantity )} {item.currency}
                        </del>
                    </div>
                </div>
            </div>
        </>

    )
}

export default OrderItemPion
