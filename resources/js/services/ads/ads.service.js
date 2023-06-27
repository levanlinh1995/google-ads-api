class AdsService {
    list() {
      return axios
          .get('ads')
            .then(response => {
                return response.data
            });
    }
  
    detail(adsId) {
      return axios
          .get(`ads/detail/${adsId}`)
            .then(response => {
                return response.data
            });
    }
  
    store(data) {
      return axios
          .post('ads/store', data)
            .then(response => {
                return response.data
            })
    }
  
    update(adsId, data) {
      return axios
          .post(`ads/update/${adsId}`, data)
            .then(response => {
                return response.data
            })
    }
  
    delete(adsId) {
      return axios
          .post(`ads/delete/${adsId}`)
            .then(response => {
                return response.data
            })
    }
  }
  
  export default new AdsService();