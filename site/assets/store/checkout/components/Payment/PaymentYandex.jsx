import * as React from 'react';

export const PaymentYandex = () => {
    return (
        <>
            <div className="split mt-4">
                <div className="split__title">
                    <span>Без переплат</span> на 2 месяца
                </div>
                <div className="split__items">
                    <div className="split__item split__item-active">
                        <div className="split__txt">
                            <span>сегодня</span>
                            2500 ₽
                        </div>
                    </div>
                    <div className="split__item">
                        <div className="split__txt">
                            <span>27 янв</span>
                            2500 ₽
                        </div>
                    </div>
                    <div className="split__item">
                        <div className="split__txt">
                            <span>10 фев</span>
                            2500 ₽
                        </div>
                    </div>
                    <div className="split__item">
                        <div className="split__txt">
                            <span>24 фев</span>
                            2500 ₽
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};