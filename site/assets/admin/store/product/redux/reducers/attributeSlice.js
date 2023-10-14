import { createSlice } from '@reduxjs/toolkit'
import {fetchAttributes, pushChangedAttributeProduct} from '../api/attribute'

const setError = (state, action) => {
    state.status = 'rejected'
    state.error = action.payload
}

const setMessage = (state, action) => {
    state.status = 'resolved'
    state.message = action.payload.message
}

const attributeSlice  = createSlice({
    name: 'attributes',
    initialState: {
        status: null,
        error: null,
        id: null,
        name: '',
        items: [],
    },
    reducers: {
        changeValueCharacteristic(state, action) {
            const { attributeId, values } = action.payload

            const item = state.items.find(
                (item) => item.attribute_id === attributeId
            )
            item.values = values

            console.log(state)
        },
    },
    extraReducers: {
        [fetchAttributes.pending]: (state) => {
            state.status = 'loading'
            state.error = null
        },
        [fetchAttributes.fulfilled]: (state, action) => {
            state.status = 'resolved'
            state.id = action.payload.id
            state.items = action.payload.items
        },
        [fetchAttributes.rejected]: setError,
        [pushChangedAttributeProduct.pending]: (state) => {
            state.status = 'loading'
            state.error = null
            state.message = null
        },
        [pushChangedAttributeProduct.fulfilled]: (state, action) => {
            state.status = 'resolved'
            state.id = action.payload.id
            state.items = action.payload.items
        },
        [pushChangedAttributeProduct.rejected]: setError,
    }
})

export const { changeValueCharacteristic } = attributeSlice.actions
export const selectAttributes = (state) => state.attributes
export default attributeSlice.reducer
