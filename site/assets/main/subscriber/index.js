import * as React from 'react';
import { createRoot } from 'react-dom/client'
import App from "./App";

const container = document.getElementById('subscriber_id')

const root = createRoot(container)

root.render(
        <App />
)
