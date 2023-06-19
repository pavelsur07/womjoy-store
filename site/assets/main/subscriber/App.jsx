import React, {useState} from 'react';
import { useForm } from "react-hook-form";
import ModalSuccess from "../components/Modal/ModalSuccess";
import {postNewSubscriber} from "./api/PostNewSubscriber";

function App() {
    const {
        register,
        handleSubmit,
        watch,
        formState: { errors }
    } = useForm();

    const [modalActive, setModalActive] = useState(false)
    const onSubmit = data => {
        setModalActive(true)
        const res = postNewSubscriber(data).then(resp => {
            console.log(resp)})

        console.log(res.data)
        console.log(data)
    };

    return (
        <>
            <section className="subscribe section">
                <div className="wrapper">
                    <div className="subscribe__content">
                        <div className="subscribe__text">
                            <h2 className="subscribe__title section-title">Выгода с доставкой</h2>
                            <p className="subscribe__descr">Подпишитесь и получайте промокоды, акции и подборки товаров</p>
                        </div>
                        <form className="subscribe__form" onSubmit={handleSubmit(onSubmit)}>
                            <div className="subscribe__field field">
                                <span className="field__ph">Электронная почта</span>
                                <input
                                    type="email"
                                    className="field__inp"
                                    placeholder='email@example.com'
                                    {...register("email", { required: true })}
                                />
                                {errors.exampleRequired && <span className="field__inp">This field is required</span>}
                            </div>
                            <input type="submit" className="subscribe__btn btn-primary" value='Подписаться'/>
                        </form>
                    </div>
                </div>
                <ModalSuccess active={modalActive} setActive={setModalActive}/>
            </section>
        </>
    );
}

export default App;