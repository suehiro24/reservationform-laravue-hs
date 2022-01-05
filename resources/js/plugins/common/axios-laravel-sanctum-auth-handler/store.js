import router from '@/router'
import authService from '@/plugins/common/axios-laravel-sanctum-auth-handler/authService'

const modules = {
}

const state = {
  authUser: null,
}

const getters = {
  authUser: (state) => {
    return state.authUser
  },
  isLoggedIn: (state) => {
    return !!state.authUser
  },
}

const actions = {
  logout ({ commit }) {
    return authService.logout()
      .then(() => {
        commit('setUser', null)
        router.push({ path: '/login' })
      })
  },
  register ({ commit, dispatch }, payload) {
    return authService.registerUser(payload)
      .then(async response => {
        await dispatch('login', payload)
      })
      .catch((e) => {
        // TODO: 画面用エラーメッセージ
        console.error(e)
      })
  },
  getAuthUser ({ commit }) {
    return authService.getAuthUser()
      .then(response => {
        commit('setUser', response.data.authUser)
      })
      .catch(() => {
        commit('setUser', null)
      })
  },
}

const mutations = {
  setUser (state, authUser) {
    state.authUser = authUser
  },
}

export default {
  namespaced: true,
  modules,
  state,
  getters,
  actions,
  mutations,
}