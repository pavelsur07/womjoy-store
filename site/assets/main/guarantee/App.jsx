import React, {useState} from 'react';
import { useForm } from "react-hook-form";
import ModalSuccess from "../components/Modal/ModalSuccess";

function App() {
    const {
        register,
        handleSubmit,
        watch,
        formState: { errors }
    } = useForm();

    const [modalActive, setModalActive] = useState(true)
    const onSubmit = data => {
        setModalActive(true)
        console.log(data)
    };

    return (
        <>
            <form className="g-form">
                <div className="g-form__title title-l">Напишите нам в гарантийный отдел</div>
                <p className="g-form__please">
                    Пожалуйста, заполните форму ниже, и мы с радостью окажем вам помощь по
                    вопросам, связанным с гарантией.
                </p>
                {errors.exampleRequired && <span className="field__inp">This field is required</span>}
                <div className="g-form__main">
                    <textarea
                        className="g-form__textarea"
                        placeholder="Текст сообщения..."
                        {...register("message", { required: true })}
                    />
                    <div className="g-form__row">
                        <div className="g-form__sel field field-sel">
                            <span className="field__ph">Место покупки</span>
                            <select className="field__sel" {...register("service", { required: true })}>
                                <option value="ozon" selected>Ozon</option>
                                <option value="ynadex">Я. Маркет</option>
                                <option value="wildberries">Wildberries</option>
                            </select>
                        </div>
                        <div className="g-form__field field">
                            <span className="field__ph">Электронная почта</span>
                            <input
                                type="text"
                                className="field__inp"
                                placeholder="email@example.com"
                                {...register("email", { required: true })}
                            />
                        </div>
                        <div className="g-form__field field">
                            <span className="field__ph">Телефон</span>
                            <input
                                type="text"
                                className="field__inp phone-masked-field"
                                placeholder="+7 911 658-74-85"
                                {...register("phone", { required: true })}
                            />
                        </div>
                        {/*<button className="g-form__submit btn-primary" type="submit">Отправить</button>*/}
                        <input type="submit" className="g-form__submit btn-primary" value='Отправить'/>
                    </div>
                    <div className="g-form__checks">
                        <label className="g-form__check">
                            <input
                                type="checkbox"
                                hidden checked
                                {...register("is_i_agree")}
                            />
                            <span><img src="../img/icons/check-white.svg" alt="check white"/></span>
                            Согласен с условиями Правил пользования торговой площадкой
                        </label>
                        <label className="g-form__check">
                            <input
                                type="checkbox"
                                hidden checked
                                {...register("is_subscribe")}
                            />
                            <span><img src="../img/icons/check-white.svg" alt="check white"/></span>
                            Подпишитесь и получайте промокоды, акции и подборки товаров
                        </label>
                    </div>
                </div>
            </form>
            <ModalSuccess active={modalActive} setActive={setModalActive}/>
        </>
    )

}

export default App