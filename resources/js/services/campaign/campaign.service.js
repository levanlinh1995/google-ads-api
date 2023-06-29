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
        .put(`campaigns/update/${campaignId}`, data)
          .then(response => {
              return response.data
          })
  }

  delete(campaignId) {
    return axios
        .delete(`campaigns/delete/${campaignId}`)
          .then(response => {
              return response.data
          })
  }
}

export default new CampaignService();