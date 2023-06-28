import axios from 'axios'

export const postNewSubscriber = async (data) => {
  return axios
    .post('/api/subscriber/', { email: data.email })
    .then((response) => response.data)
    .catch((err) => err)
}
