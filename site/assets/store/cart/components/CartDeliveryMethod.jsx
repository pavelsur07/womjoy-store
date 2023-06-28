import * as React from 'react';

export function CartDeliveryMethod(props) {
    return (
        <div className="checkout__row">
            <div className="checkout__subtitle">Способ доставки</div>
            <div className="pvz-block">
                <div className="pvz-block__text">
                    <div className="pvz-block__title">
                        Пункт выдачи СДЭК
                        <img
                            src="../img/icons/sdek-check.svg"
                            alt="sdek check"
                            width="24"
                            height="24"
                        />
                    </div>
                    <div className="pvz-block__info">
                        <span className="pvz-block__cost">500 ₽</span>
                        <span>На Космонавтов 32</span>
                    </div>
                </div>
                <button className="pvz-block__btn" type="button">Изменить</button>
            </div>
            <div className="pvz-block">
                <div className="pvz-block__text">
                    <div className="pvz-block__title">Пункт выдачи не выбран</div>
                    <div className="pvz-block__info">
                        <span>Доставка от 500 ₽</span>
                    </div>
                </div>
                <button className="btn-black" type="button">Выбрать пункт выдачи</button>
            </div>
        </div>
    )
}