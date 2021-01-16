import axios from 'axios'
import Cookies from 'js-cookie'
import * as types from '../mutation-types'

// State
export const state = {
    user: null,
    access_token: Cookies.get('access_token'),
    refresh_token: Cookies.get('refresh_token'),
}

// Getters
export const getters = {
    user: state => state.user,
    access_token: state => state.access_token,
    refresh_token: state => state.refresh_token,
}

// Mutations
export const mutations = {
    [types.SAVE_TOKEN](state, {access_token, refresh_token, expires}) {
        state.access_token = access_token
        state.refresh_token = refresh_token
        Cookies.set('access_token', access_token, {expires})
        Cookies.set('refresh_token', refresh_token, {expires})
    },

    [types.FETCH_USER_SUCCESS](state, {user}) {
        state.user = user
    },

    [types.FETCH_USER_FAILURE](state) {
        state.access_token = null
        state.refresh_token = null
        Cookies.remove('access_token')
        Cookies.remove('refresh_token')
    },

    [types.LOGOUT](state) {
        state.user = null
        state.access_token = null
        state.refresh_token = null

        Cookies.remove('access_token')
        Cookies.remove('refresh_token')
    }
}

// Actions
export const actions = {
    saveToken({commit, dispatch}, payload) {
        commit(types.SAVE_TOKEN, payload)
    },

    async fetchUser({commit}) {
        try {
            const {data} = await axios.get('/user')
            commit(types.FETCH_USER_SUCCESS, {user: data})
        } catch (e) {
            commit(types.FETCH_USER_FAILURE)
        }
    },

    async logout({commit}) {
        try {
            await axios.post('/logout')
        } catch (e) {
        }
        commit(types.LOGOUT)
    }
}
