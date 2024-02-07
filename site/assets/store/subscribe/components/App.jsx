import React, { useState } from 'react';

const App = () => {

    const [email, setEmail] = useState('');
    const [formErrors, setFormErrors] = useState({});
    const [formSubmitted, setFormSubmitted] = useState(false);

    const validateForm = () => {
        const errors = {};
        if (!email.trim() || !isValidEmail(email)) {
            errors.email = 'Введите корректный email';
        }
        return errors;
    };

    const isValidEmail = (email) => {
        // Реализация проверки корректности email
        return /\S+@\S+\.\S+/.test(email);
    };

    const handleChange = (e) => {
        setEmail(e.target.value);
        setFormErrors({})
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const errors = validateForm();
        if (Object.keys(errors).length === 0) {
            try {
                const response = await fetch('/api/subscriber/', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });
                if (!response.ok) {
                    throw new Error('Ошибка при отправке данных');
                }
                setFormSubmitted(true);
            } catch (error) {
                console.error('Ошибка при отправке данных:', error);
                // Обработка ошибки при отправке данных
            }
        } else {
            setFormErrors(errors);
        }
    };

    return (<>

        <span className="d-block mb-3 fw-bold text-uppercase">ПОДПИШИТЕСЬ НА НАШУ РАССЫЛКУ:</span>
        {formSubmitted ? (
            <div className="p-3 bg-warning bg-opacity-10 mb-1">
                Вы успешно подписались на новости!
            </div>
        ) : (
            <form className="d-flex mb-4 flex-column flex-lg-row" onSubmit={handleSubmit}>

                <div className="w-field flex-grow-1 me-0 me-lg-3 mb-3 mb-lg-0">
                    <div className="w-field__main">
                        <input type="text" className="w-field__inp" placeholder="Введите Ваш E-Mail" value={email} onChange={handleChange}/>
                    </div>
                    {formErrors.email && <p>{formErrors.email}</p>}
                </div>
                <button className="w-primary-btn" type="submit" disabled={Object.keys(formErrors).length !== 0}>Подписаться</button>

            </form>
        )}

        <p className="w-text-sm">
            После подписки на рассылку на указанный e-mail придет промокод на скидку 500 рублей на ваш первый заказ
            от 3500 рублей. Предложение действует только для новых клиентов, которые не регистрировались в программе
            лояльности womjoy. <br/><br/>
            Нажимая на кнопку «Подписаться», я соглашаюсь на обработку моих персональных данных и ознакомлен(а) с
            условиями конфиденциальности.
        </p>
    </>)
}

export default App