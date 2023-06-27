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
    detail({ commit }, campaignId) {
      return AdsService.detail(campaignId).then(
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
    update({ commit }, { campaignId, data }) {
      return AdsService.update(campaignId, data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },

    delete({ commit }, campaignId) {
      return AdsService.delete(campaignId).then(
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