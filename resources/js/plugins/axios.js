import axios from 'axios'
import store from '../store'

// Request interceptor
axios.interceptors.request.use(request => {
    const token = store.getters['auth/access_token']
    if (token) {
        request.headers.common['Authorization'] = `Bearer ${token}`
    }

    return request
})
