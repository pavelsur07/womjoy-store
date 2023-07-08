import { createAsyncThunk, createSlice } from '@reduxjs/toolkit'

export const fetchCart = createAsyncThunk(
  'cart/fetchCart',
  async function (_, { rejectWithValue }) {
    try {
      const response = await fetch(`/api/v1/cart/`)
      if (!response.ok) {
        throw new Error('Server error')
      }
      return await response.json()
    } catch (error) {
      return rejectWithValue(error.message)
    }
  }
)

const setError = (state, action) => {
  state.status = 'rejected'
  state.error = action.payload
}

const cartSlice = createSlice({
  name: 'cart',
  initialState: {
    cart: null,
    status: null,
    error: null,
  },
  reducers: {},
  extraReducers: (builder) => {
    builder.addCase(fetchCart.pending, (state) => {
      state.status = 'loading'
    })
    builder.addCase(fetchCart.fulfilled, (state, action) => {
      state.status = 'resolved'
      state.cart = action.payload
    })
    builder.addCase(fetchCart.rejected, setError)
  },
})
export const selectCart = (state) => state.cart.cart
export default cartSlice.reducer
