import cart from './reducers/cart';
import checkout from './reducers/checkout';
import {combineReducers, configureStore} from "@reduxjs/toolkit";

const reducers = combineReducers({
    cart,
    checkout,
});

export default configureStore({ reducer: reducers });
