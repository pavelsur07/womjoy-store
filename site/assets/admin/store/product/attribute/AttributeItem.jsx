import React, {useState} from 'react'
import Select from 'react-select'
import {getAttributeVariants} from "../redux/api/attribute";
import {useDispatch} from "react-redux";
import {changeValueCharacteristic} from "../redux/reducers/attributeSlice";


export function AttributeItem({id, name, type,defaultValue }) {

    const [selectOptions, setSelectOptions] = useState()
    const dispatch = useDispatch()

    const onMenuOpenHandler = async (attributeId) => {
        if (selectOptions) {
            return
        }
        const response = await getAttributeVariants(attributeId)

        setSelectOptions(
            response.variants.map((item) => {
                return { value: item.id, label: item.value }
            })
        );
    }

    const onChangeSelectHandler = (attributeId, values) => {
        dispatch(changeValueCharacteristic({attributeId: attributeId, values: values }))

        console.log(attributeId + '  ' + values)
    }


    return (
        <>
            <label> {name} - {type} </label>
            {
                type === 'multi_choice' &&

                <Select
                    defaultValue={defaultValue}
                    isMulti
                    onMenuOpen={() => onMenuOpenHandler(id)}
                    onChange={(values)=>onChangeSelectHandler(id, values)}
                    name={id}
                    id={id}
                    options={selectOptions}
                    className="basic-multi-select"
                    classNamePrefix="select"
                />
            }

            {
                type === 'single_choice' &&

                <Select
                    className="basic-single"
                    classNamePrefix="select"
                    defaultValue={defaultValue}
                    onMenuOpen={() => onMenuOpenHandler(id)}
                    onChange={(values)=>onChangeSelectHandler(id, values)}
                    name={id}
                    id={id}
                    options={selectOptions}
                />
            }

            {
                type === 'type_brand' &&

                <Select
                    className="basic-single"
                    classNamePrefix="select"
                    defaultValue={defaultValue}
                    onMenuOpen={() => onMenuOpenHandler(id)}
                    onChange={(values)=>onChangeSelectHandler(id, values)}
                    name={id}
                    id={id}
                    options={selectOptions}
                />
            }

            {
                type === 'type_color' &&

                <Select
                    className="basic-single"
                    classNamePrefix="select"
                    defaultValue={defaultValue}
                    onMenuOpen={() => onMenuOpenHandler(id)}
                    onChange={(values)=>onChangeSelectHandler(id, values)}
                    name={id}
                    id={id}
                    options={selectOptions}
                />
            }

        </>
    )
}