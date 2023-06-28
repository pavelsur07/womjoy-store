import * as React from 'react';

export function CartItem(props) {
    return (
        <div className="c-item">
                <a href="#" className="c-item__img">
                    <span className="c-item__img_in">
                        <img src="../img/cart-item-img.png" alt="" width="83" height="110"/>
                    </span>
                </a>
                <div className="c-item__main">
                    <div className="c-item__text">
                        <a href="#" className="c-item__name">Лонгслив женский спортивный укороченный Womjoy
                            Classic Logo Black</a>
                        <span className="c-item__size">Размер: М</span>
                    </div>
                    <div className="c-item__actions">
                        <button className="c-item__action c-item__minus" type="button">
                            <img
                                src="../img/icons/minus.svg"
                                alt="minus"
                                width="24"
                                height="24"
                            />
                        </button>
                        <input type="text" className="c-item__inp" value="1"/>
                        <button className="c-item__action c-item__plus" type="button">
                            <img
                                src="../img/icons/plus.svg"
                                alt="plus"
                                width="24"
                                height="24"
                            />
                        </button>
                    </div>
                    <div className="c-item__price">
                        <span className="c-item__cost">1 299 ₽</span>
                        <del className="c-item__disc">1 299 ₽</del>
                    </div>
                </div>
        </div>
    );
}