import attributes from './reducers/attributeSlice'
import { combineReducers, configureStore } from '@reduxjs/toolkit'

const reducers = combineReducers({
  attributes,
})

export default configureStore({ reducer: reducers })
