import React, {Component} from 'react';

class SubscribeInput extends Component {
    render() {
        return (
            <div className="w-field mb-4">
                <div className="w-field__main">
                    <input type="email" className="w-field__inp" placeholder="Введите email"/>
                </div>
            </div>
        );
    }
}

export default SubscribeInput;