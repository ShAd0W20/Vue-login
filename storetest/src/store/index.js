import { createStore } from 'vuex'

export default createStore({
  state: {
    authData: {isAuth: false, userName: false, isAdmin: false}
  },
  mutations: {
    set_authData(state, newValue) {
      state.authData.isAuth = newValue.isAuth;
      state.authData.userName = newValue.userName;
      state.authData.isAdmin = newValue.isAdmin;
    }
  },
})
