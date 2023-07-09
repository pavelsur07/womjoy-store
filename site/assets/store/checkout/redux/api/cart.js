export const fetchCart = () => {
    // Получение данных с бэкэнда с использованием fetch()
    return fetch('/api/v1/cart/')
        .then((response) => response.json())
        .catch((error) => {
            // Обработка ошибки
            console.error('Ошибка при получении корзины:', error)
        })

};

export const execCheckoutRequest = (data) => {
    const init = { method: 'POST', body: JSON.stringify(data), headers: { 'Content-Type': 'application/json' } };

    return fetch('/api/v1/checkout/', init)
        .then((response) => response.json())
};
