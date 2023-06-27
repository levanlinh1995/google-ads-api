import AdsGroupService from '@/services/adsgroup/adsgroup.service'

const adsgroup = {
  namespaced: true,
  state: {},
  actions: {
    list() {
      return AdsGroupService.list().then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    store({ commit }, data) {
      return AdsGroupService.store(data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    delete({ commit }, adsgroupId) {
      return AdsGroupService.delete(adsgroupId).then(
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

export default adsgroup;