import React from 'react'
import {useDispatch, useSelector} from "react-redux";
import {AttributeItem} from "./AttributeItem";
import {pushChangedAttributeProduct} from "../redux/api/attribute";


export function AttributeList() {
    const dispatch = useDispatch()
    const attributes = useSelector((state) => state.attributes)

    return (
        <>
            <div className="list-group list-group-flush list-group-hoverable">
                { attributes.items.map((item) => (
                    <div className="list-group-item" key={item.attribute_id}>
                        <AttributeItem
                            id={item.attribute_id}
                            name={item.name}
                            type={item.type}
                            defaultValue={item.values}
                        />
                    </div>
                ))
                }
            </div>
            <button
                className="btn btn-bitbucket mt-3"
                onClick={()=> dispatch(pushChangedAttributeProduct(attributes.id))}
            >
                save
            </button>
        </>
    )
}