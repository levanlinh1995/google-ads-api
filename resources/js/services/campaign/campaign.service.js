class CampaignService {
  list() {
    return axios
        .get('campaigns')
          .then(response => {
              return response.data
          });
  }

  store(data) {
    return axios
        .post('campaigns/store', data)
          .then(response => {
              return response.data
          })
  }
}

export default new CampaignService();