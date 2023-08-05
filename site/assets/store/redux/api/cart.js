export const fetchCartRequest = () => {
    // Получение данных с бэкэнда с использованием fetch()
    return fetch('/api/v1/cart/')
        .then((response) => response.json())
        .catch((error) => {
            // Обработка ошибки
            console.error('Ошибка при получении корзины:', error)
        })
};

export const addToCartRequest = (productId, quantity) => {
    return fetch('/api/v1/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId: productId, quantity: quantity }),
    })
        .then((response) => response.json())
        .catch((error) => {
            // Обработка ошибки
            console.error(error)
        });
}

export const removeFromCartRequest = (productId) => {
    // Отправка данных на бэкэнд с использованием fetch()
    return fetch('/api/v1/cart/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId: productId }),
    })
        .then((response) => response.json())
        .catch((error) => {
            // Обработка ошибки
            console.error('Ошибка при удалении товара из корзины:', error)
        })
}

export const updateQuantityRequest = (productId, quantity) => {
    // Отправка данных на бэкэнд с использованием fetch()
    return fetch('/api/v1/cart/quantity', {
        method: 'POST',
        body: JSON.stringify({ productId: productId, quantity: quantity }),
        headers: { 'Content-Type': 'application/json' },
    })
        .then((response) => response.json())
        .catch((error) => {
            // Обработка ошибки
            console.error('Ошибка при обновлении количества товара:', error)
        })
}

export const clearCartRequest = () => {
    // Отправка запроса на бэкэнд для очистки корзины
    return fetch('/api/v1/cart/clear', { method: 'POST' })
        .then((response) => response.json())
        .catch((error) => {
            // Обработка ошибки
            console.error('Ошибка при очистке корзины:', error)
        })
}
