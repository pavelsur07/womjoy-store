import checkout from './reducers/checkout';
import {combineReducers, configureStore} from "@reduxjs/toolkit";

const reducers = combineReducers({
    checkout,
});

export default configureStore({ reducer: reducers });
