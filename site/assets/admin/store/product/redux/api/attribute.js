import { createAsyncThunk } from '@reduxjs/toolkit'

export const fetchAttributes = createAsyncThunk(
    'attributes/fetchAttributes',
    async function (id, { rejectWithValue }) {
        try {
            const response = await fetch(`/api/v1/product/${id}/attribute`, {
                method: 'GET',
            })
            if (!response.ok) {
                throw new Error('Server error')
            }
            return await response.json()
        } catch (error) {
            return rejectWithValue(error.message)
        }
    }
)

export const pushChangedAttributeProduct = createAsyncThunk(
    'attributes/pushChangedAttributeProduct',
    async function (productId, { rejectWithValue, dispatch, getState }) {
        const attributes = getState().attributes.items

        const result = {
            product_id: productId,
            attributes: attributes.map((item) =>
                item.values.map((value) =>  ({
                    attribute_id: item.attribute_id,
                    variant_id: value.value
                }))).flat()
            }

        try {
            const response = await fetch(`/api/v1/product/${productId}/attribute/edit`, {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json',
                },
                body: JSON.stringify(result),
            })

            if (!response.ok) {
                throw new Error('Can\t changed attributes. Server error')
            }

            const data = await response.json()
            console.log(data)

            return await response.json()
        } catch (e) {
            return rejectWithValue(e.message)
        }
    }
)

export const getAttributeVariants = async (id) => {
    const response = await fetch(`/api/v1/attribute/${id}/variants/`, {
        method: 'GET',
    })
    return await response.json()
}