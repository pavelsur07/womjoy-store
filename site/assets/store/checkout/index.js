import ReactDOM from "react-dom/client";
import App from "../checkout/App";
import React from "react";

const element = document.getElementById('checkout-id')

if (element) {
    const root = ReactDOM.createRoot(element)
    root.render(
        <>
            <App />
        </>
    )
}
