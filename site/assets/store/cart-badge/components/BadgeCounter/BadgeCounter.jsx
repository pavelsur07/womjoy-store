import React, {useEffect} from 'react';
import {useDispatch, useSelector} from "react-redux";
import {getCartInfo} from "../../../redux/actions/cart";

const App = () => {
    const dispatch = useDispatch();
    const cart = useSelector((state) => state.cart);

    useEffect(() => {
        dispatch(
            getCartInfo()
        );
    }, [dispatch]);

    if (cart.amount < 1) {
        return;
    }

    return (
        <span className="cart-currentcnt">{cart.amount}</span>
    );
};

export default App;
