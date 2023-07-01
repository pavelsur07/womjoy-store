import React from 'react'
function ModalSuccess({active = false, setActive,}) {
    return (
        <>
            {/*<div className={`modal ${active === true ? 'active':'' }`} id="success">
                <div className="modal__bg" onClick={()=>setActive(false)}></div>
                <div className="modal__content modal__content-sm" onClick={e => e.stopPropagation() }>
                    <button
                        className="modal__close"
                        type="button"
                        onClick={()=> setActive(false)}
                    >
                        <img
                            src='../img/icons/close-black.svg'
                            alt="Success"
                            width="24"
                            height="24"
                        />
                    </button>
                    <div className="success">
                        <img src="../img/icons/success.svg" alt="" width="70" height="70"/>
                        <div className="success__title">Успешное действие</div>
                        <p className="success__descr">Тут место для описания выполненного действия</p>
                        {children}
                    </div>
                </div>
            </div>*/}


            <div className={`modal ${active === true ? 'active':'' }`} id="card-success">
                <div className="modal__bg"></div>
                <div className="modal__content">
                    <button
                        className="modal__close"
                        type="button"
                        onClick={()=> setActive(false)}
                    >
                        <img
                            src="../img/icons/close-black.svg"
                            alt="close black"
                            width="24"
                            height="24"
                        />
                    </button>
                    <div className="success">
                        <img
                            src="../img/icons/success.svg"
                            alt="success"
                            width="70"
                            height="70"
                        />
                            <div className="success__title">Добавлено в корзину</div>
                            <div className="success__btns">
                                <a className="success__btn btn-black" href="#">Перейти в корзину</a>
                                <button
                                    className="success__btn btn-empty"
                                    type="button"
                                    onClick={()=> setActive(false)}
                                >
                                    Продолжить покупки
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default ModalSuccess