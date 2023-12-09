// Корневой компонент приложения
import React from 'react';

// Имортируем комопненты

const App = () => {
    return (
        <>
            <div className="row mt-4">
                <h1 className="fs-3">Форма обратной связи</h1>
                <p className="w-text-lg mb-4">Заполните все поля и нажмите «Отправить», мы обработаем ваше обращение и свяжемся в течение 24 часов.</p>
                <div className="p-3 bg-warning bg-opacity-10  mb-4">ЖЕЛТОЕ УВЕДОМЛЕНИЕ</div>
                <div className="p-3 bg-danger bg-opacity-10 text-danger mb-4">КРАСНОЕ УВЕДОМЛЕНИЕ</div>

                <div className="col col-12 col-lg-6">
                    <div className="w-field mb-4">
                        <label className="w-field__label">Список большой белый</label>
                        <div className="w-sel w-sel-lg w-sel-white">
                            <div className="w-sel__trigger">
                                Россия
                            </div>
                            <div className="w-sel__dropdown">
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" checked hidden/>
                                        <span></span>
                                        Россия
                                </label>
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" hidden/>
                                        <span></span>
                                        Беларусь
                                </label>
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" hidden/>
                                        <span></span>
                                        Казахстан
                                </label>
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" hidden/>
                                        <span></span>
                                        Кыргызстан
                                </label>
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" hidden/>
                                        <span></span>
                                        Кыргызстан
                                </label>
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" hidden/>
                                        <span></span>
                                        Кыргызстан
                                </label>
                                <label className="w-sel__item">
                                    <input type="radio" name="sel-3" hidden/>
                                        <span></span>
                                        Кыргызстан
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="col col-12 col-lg-6">
                    <div className="w-field mb-4">
                        <label className="w-field__label">Ваше имя</label>
                        <div className="w-field__main"><input type="text" className="w-field__inp" placeholder="Введите название"/></div>
                    </div>
                </div>
                <div className="col col-12 col-lg-6">
                    <div className="w-field mb-4">
                        <label className="w-field__label">Телефон</label>
                        <div className="w-field__main"><input type="text" className="w-field__inp" placeholder="Введите название"/></div>
                    </div>
                </div>
                <div className="col col-12 col-lg-6">
                    <div className="w-field mb-4">
                        <label className="w-field__label">Email*</label>
                        <div className="w-field__main"><input type="text" className="w-field__inp" placeholder="Введите название"/></div>
                    </div>
                </div>

                <div className="col col-12">
                    <div className="w-field mb-4">
                        <label className="w-field__label">Сообщение</label>
                        <div className="w-field__main"><textarea className="w-field__inp" rows="5" placeholder="Введите название"/></div>
                    </div>
                </div>

                <p className="w-text-lg mb-4"> Нажимая на кнопку «Отправить», вы даете согласие на обработку и хранение своих персональных данных в соответствии
                    <a className="w-black-link" href="#">спользовательским соглашением</a>
                </p>
                <div className="col col-12 col-lg-3">
                    <button className="w-primary-btn" type="submit">Отправить</button>
                </div>

            </div>
        </>
    );
};

export default App;
