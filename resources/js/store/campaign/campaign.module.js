import CampaignService from '@/services/campaign/campaign.service'

const campaign = {
  namespaced: true,
  state: {},
  actions: {
    list() {
      return CampaignService.list().then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    detail({ commit }, campaignId) {
      return CampaignService.detail(campaignId).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    store({ commit }, data) {
      return CampaignService.store(data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },
    update({ commit }, { campaignId, data }) {
      return CampaignService.update(campaignId, data).then(
        data => {
          return Promise.resolve(data);
        },
        error => {
          return Promise.reject(error);
        }
      );
    },

    delete({ commit }, campaignId) {
      return CampaignService.delete(campaignId).then(
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

export default campaign;