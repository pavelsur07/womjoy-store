import React from "react";

const CustomerPion = () => {

    return(
        <>
            <div className="py-4 w-text-lg">2. ПОКУПАТЕЛЬ</div>
            <div className="pb-4">
                <div className="pb-4 w-border-bottom-primary2">
                    <div className="row">
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">ИМЯ*</label>
                                <div className="w-field__main"><input type="text" className="w-field__inp field__inp"/></div>
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">ФАМИЛИЯ*</label>
                                <div className="w-field__main"><input type="text" className="w-field__inp field__inp"/></div>
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">EMAIL*</label>
                                <div className="w-field__main"><input type="text" className="w-field__inp field__inp"/></div>
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">ТЕЛЕФОН*</label>
                                <div className="w-field__main"><input type="text" className="w-field__inp field__inp"/></div>
                            </div>
                        </div>
                        <div className="col col-12">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">КОММЕНТАРИИ К ЗАКАЗУ*</label>
                                <div className="w-field__main"><textarea className="w-field__textarea field__inp"></textarea></div>
                            </div>
                        </div>
                    </div>

                    <div className="w-text">
                        <p><b>Сервис «Womjoy в подарок»</b></p>
                        <p>По вашему запросу мы можем:</p>
                        <ul className="disced">
                            <li>упаковать изделия в несколько разных коробок</li>
                            <li>упаковать изделия в <a href="#">подарочную коробку</a></li>
                            <li>убрать бирки с ценой и товарный чек из упаковки</li>
                            <li>не звонить получателю заранее и сохранить ваш заказ в тайне до момента его вручения</li>
                        </ul>
                        <p>Подарочная упаковка приобретается отдельно.</p>
                        <p>
                            Просто укажите в комментарии к заказу ваши пожелания и мы их исполним! <br/>
                            Не забудьте дополнительно оставить свои контакты в комментарии к заказу.
                        </p>
                    </div>
                </div>
{/*                <div className="pt-4 d-flex justify-content-between">
                    <button className="w-empty-btn" type="button">Назад</button>
                    <button className="w-primary-btn d-none d-lg-block" type="button">Оформить заказ</button>
                </div>*/}
            </div>
        </>
    )
}

export default CustomerPion