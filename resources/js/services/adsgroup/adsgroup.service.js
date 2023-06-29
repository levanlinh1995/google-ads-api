class AdsGroupService {
    list() {
      return axios
          .get('ads-groups')
            .then(response => {
                return response.data
            });
    }
  
    store(data) {
      return axios
          .post('ads-groups/store', data)
            .then(response => {
                return response.data
            })
    }

    delete(adsgroupId) {
      return axios
          .delete(`ads-groups/delete/${adsgroupId}`)
            .then(response => {
                return response.data
            })
    }
  }
  
  export default new AdsGroupService();