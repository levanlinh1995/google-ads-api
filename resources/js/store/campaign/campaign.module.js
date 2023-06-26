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
    store(data) {
        return CampaignService.store(data).then(
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