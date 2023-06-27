import AdsService from '@/services/ads/ads.service.js'

const ads = {
  namespaced: true,
  state: {},
  actions: {
    list() {
      return AdsService.list().then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    detail({ commit }, adsId) {
      return AdsService.detail(adsId).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    store({ commit }, data) {
      return AdsService.store(data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    update({ commit }, { adsId, data }) {
      return AdsService.update(adsId, data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },

    delete({ commit }, data) {
      return AdsService.delete(data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
  },
  mutations: {
  }
};

export default ads;