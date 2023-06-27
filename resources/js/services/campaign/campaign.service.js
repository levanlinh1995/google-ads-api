class CampaignService {
  list() {
    return axios
        .get('campaigns')
          .then(response => {
              return response.data
          });
  }

  detail(campaignId) {
    return axios
        .get(`campaigns/detail/${campaignId}`)
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

  update(campaignId, data) {
    return axios
        .post(`campaigns/update/${campaignId}`, data)
          .then(response => {
              return response.data
          })
  }

  delete(campaignId) {
    return axios
        .post(`campaigns/delete/${campaignId}`)
          .then(response => {
              return response.data
          })
  }
}

export default new CampaignService();