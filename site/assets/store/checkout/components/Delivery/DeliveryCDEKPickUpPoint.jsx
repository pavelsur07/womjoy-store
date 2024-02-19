import React, { useState, useEffect } from 'react';

const DeliveryCDEKPickUpPoint = (props) => {
    const [ISDEKWidgetReady, setISDEKWidget] = useState(false);

    if (!window.ISDEKWidjet) {
        return (
            <div className="delivery-cdek-unavailable">
                Не удалось обнаружить объект window.ISDEKWidjet. Проверьте подключения скрипта <code>&lt;script
                src="https://ваш.сайт/widget/widjet.js"&gt;&lt;/script&gt;</code>
            </div>
        );
    }

    if (!ISDEKWidgetReady) {
        new ISDEKWidjet({
            /*defaultCity: props.defaultCity, //какой город отображается по умолчанию*/
            defaultCity: 'Москва', //какой город отображается по умолчанию
            country: 'Россия', // можно выбрать страну, для которой отображать список ПВЗ
            link: 'delivery-cdek-pickup-point', // id элемента страницы, в который будет вписан виджет
            path: '/widget/scripts/', //директория с библиотеками
            servicepath: '/widget/scripts/service.php', //ссылка на файл service.php на вашем сайте
            hidedelt: true,
            onChoose: (choice) => {
                if (props.onSelectPickupPoint) {
                    props.onSelectPickupPoint({
                        cdek: {
                            id: choice.id,
                            address: choice.PVZ.Address,
                            addressComment: choice.PVZ.AddressComment,
                            cash: choice.PVZ.Cash,
                            cityName: choice.cityName,
                            cityCode: choice.PVZ.CityCode,
                            dressing: choice.PVZ.Dressing,
                            metro: choice.PVZ.Metro,
                            name: choice.PVZ.Name,
                            note: choice.PVZ.Note,
                            phone: choice.PVZ.Phone,
                            postamat: choice.PVZ.Postamat,
                            site: choice.PVZ.Site,
                            station: choice.PVZ.Station,
                            workTime: choice.PVZ.WorkTime,
                            cX: choice.PVZ.cX,
                            cY: choice.PVZ.cY,
                        }
                    })
                }
            },
        });
    }

    useEffect(() => {
        setISDEKWidget(true);
    })

    return (
        <div id="delivery-cdek-pickup-point" style={{ height: '100vh' }} />
    );
}

export default DeliveryCDEKPickUpPoint;
