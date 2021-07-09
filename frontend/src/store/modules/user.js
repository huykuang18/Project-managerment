import { login, logout, getInfo } from '@/api/user'
import { getToken, setToken, removeToken } from '@/utils/auth'
import router, { resetRouter } from '@/router'

const state = {
    token: getToken(),
    name: '',
    // avatar: '',
    // introduction: '',
    roles: []
}

const mutations = {
    SET_TOKEN: (state, token) => {
        state.token = token;
        setToken(token);
    },
    // SET_INTRODUCTION: (state, introduction) => {
    //     state.introduction = introduction
    // },
    SET_NAME: (state, name) => {
        state.name = name
    },
    // SET_AVATAR: (state, avatar) => {
    //     state.avatar = avatar
    // },
    SET_ROLES: (state, roles) => {
        state.roles = roles
    }
}

const actions = {
    // user login
    async login({ commit }, userInfo) {
        const { username, password } = userInfo
        const data = await login({ username: username.trim(), password: password });
        commit('SET_TOKEN', data.data.token);
    },

    // get user info
    async getInfo({ commit, state }) {
        const data = await getInfo(state.token);
        if (!data) {
            reject('Verification failed, please Login again.')
        }
        commit('SET_ROLES', data.data.role);
        commit('SET_NAME', data.data.name);
        // commit('SET_AVATAR', avatar);
        // commit('SET_INTRODUCTION', introduction);
    },

    // user logout
    async logout({ commit, state, dispatch }) {
        await logout(state.token)
        removeToken()
        resetRouter()
        commit('SET_TOKEN', '')
        commit('SET_ROLES', [])
        dispatch('tagsView/delAllViews', null, { root: true })
    },

    // remove token
    resetToken({ commit }) {
        return new Promise(resolve => {
            commit('SET_TOKEN', '')
            commit('SET_ROLES', [])
            removeToken()
            resolve()
        })
    },

    // dynamically modify permissions
    async changeRoles({ commit, dispatch }, role) {
        const token = role + '-token'

        commit('SET_TOKEN', token)
        setToken(token)

        const { roles } = await dispatch('getInfo')

        resetRouter()

        // generate accessible routes map based on roles
        const accessRoutes = await dispatch('permission/generateRoutes', roles, { root: true })
        // dynamically add accessible routes
        router.addRoutes(accessRoutes)

        // reset visited views and cached views
        dispatch('tagsView/delAllViews', null, { root: true })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}