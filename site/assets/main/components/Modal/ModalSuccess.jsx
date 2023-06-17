import React from 'react'
function ModalSuccess({active, setActive, children}) {
    return (
        <>
            <div className={`modal ${active === true ? 'active':'' }`} id="success">
                <div className="modal__bg" onClick={()=>setActive(false)}></div>
                <div className="modal__content modal__content-sm" onClick={e => e.stopPropagation() }>
                    <button
                        className="modal__close"
                        type="button"
                        onClick={()=> setActive(false)}
                    >
                        <img
                            src='./img/icons/close-black.svg'
                            alt="Success"
                            width="24"
                            height="24"
                        />
                    </button>
                    <div className="success">
                        <img src="./img/icons/success.svg" alt="" width="70" height="70"/>
                        <div className="success__title">Успешное действие</div>
                        <p className="success__descr">Тут место для описания выполненного действия</p>
                        {children}
                    </div>
                </div>
            </div>
        </>
    )
}

export default ModalSuccess