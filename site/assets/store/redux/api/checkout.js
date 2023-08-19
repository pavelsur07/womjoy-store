export const execCheckoutRequest = (data) => {
  const init = {
    method: 'POST',
    body: JSON.stringify(data),
    headers: { 'Content-Type': 'application/json' },
  }

  return fetch('/api/v1/checkout/', init).then((response) => response.json())
}
