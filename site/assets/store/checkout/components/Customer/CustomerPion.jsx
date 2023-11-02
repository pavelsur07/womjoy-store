import React from "react";
import {useDispatch, useSelector} from "react-redux";
import {setCustomer} from "../../../redux/actions/checkout";

const CustomerPion = () => {
    const dispatch = useDispatch();
    const customer = useSelector((state) => state.checkout.customer);

    const handleInputChange = (field, value) => {
        const values = { ...customer, [field]: value };

        dispatch(
            setCustomer(values.name, values.phone, values.email, values.comment)
        );
    };
    return(
        <>
            <div className="py-4 w-text-lg">2. ПОКУПАТЕЛЬ</div>
            <div className="pb-4">
                <div className="pb-4 w-border-bottom-primary2">
                    <div className="row">
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">ИМЯ*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        className="w-field__inp field__inp"
                                        placeholder="Иван Иванов"
                                        value={customer.name}
                                        onChange={(e) => handleInputChange('name', e.target.value)}
                                    />
                                </div>
                            </div>
                        </div>
                        {/*
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">ФАМИЛИЯ*</label>
                                <div className="w-field__main">
                                    <input type="text" className="w-field__inp field__inp"/>
                                </div>
                            </div>
                        </div>
                        */}
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">EMAIL*</label>
                                <div className="w-field__main">
                                    <input
                                        type="email"
                                        name="email"
                                        className="w-field__inp field__inp"
                                        placeholder="email@example.com"
                                        value={customer.email}
                                        onChange={(e) => handleInputChange('email', e.target.value)}
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="col col-12 col-md-6">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">ТЕЛЕФОН*</label>
                                <div className="w-field__main">
                                    <input
                                        type="text"
                                        name='phone'
                                        className="w-field__inp field__inp"
                                        placeholder="+7 000 000-00-00"
                                        value={customer.phone}
                                        onChange={(e) => handleInputChange('phone', e.target.value)}
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="col col-12">
                            <div className="w-field field mb-4">
                                <label className="w-field__label field__ph">КОММЕНТАРИИ К ЗАКАЗУ*</label>
                                <div className="w-field__main">
                                    <textarea
                                        className="w-field__textarea field__inp"
                                        value={customer.comment}
                                        onChange={(e) => handleInputChange('comment', e.target.value)}
                                        placeholder="Текст комментария"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {/*
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
                    */}
                </div>
                {/*
                 <div className="pt-4 d-flex justify-content-between">
                    <button className="w-empty-btn" type="button">Назад</button>
                    <button className="w-primary-btn d-none d-lg-block" type="button">Оформить заказ</button>
                </div>
                */}
            </div>
        </>
    )
}

export default CustomerPion