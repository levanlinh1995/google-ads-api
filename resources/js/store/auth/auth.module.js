import AuthService from '@/services/auth/auth.service';

const user = JSON.parse(localStorage.getItem('user'));
const token = localStorage.getItem('accessToken');

const initialState = token
  ? { accessToken: token, status: { loggedIn: true }, user }
  : { accessToken: null, status: { loggedIn: false }, user: null };

const auth = {
  namespaced: true,
  state: initialState,
  actions: {
    getGoogleOauthUrl() {
      return AuthService.getGoogleOauthUrl().then(
        url => {
          return Promise.resolve(url);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    googleLogin({ commit }, queryString) {
      return AuthService.getGoogleOauthCallback(queryString).then(
        data => {
          commit('loginSuccess', { user: data.user, token: data.accessToken });
          return Promise.resolve(data);
        },
        error => {
          commit('loginFailure');
          return Promise.reject(error);
        }
      );
    },
  },
  mutations: {
    loginSuccess(state, { user, token }) {
      state.status.loggedIn = true;
      state.user = user;
      state.accessToken = token;
      localStorage.setItem('accessToken', token);
    },
    loginFailure(state) {
      state.status.loggedIn = false;
      state.user = null;
      state.accessToken = null;
      localStorage.removeItem('accessToken');
    },
  }
};

export default auth;