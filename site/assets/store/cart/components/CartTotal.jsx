import * as React from 'react';

export function CartTotal(props) {
    return (
        <div className="c-main">
            <div className="c-main__title">Ваш заказ</div>
            <ul className="c-main__list">
                <li><span>Товары, 3 шт.</span><span>2000 р</span></li>
                <li><span>Скидка</span><span>− 500 р</span></li>
                <li><span>Доставка</span><span>Бесплатно</span></li>
            </ul>
            <div className="c-main__final">
                Сумма заказа
                <span className="c-main__cost">2 000 р</span>
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
    );
}