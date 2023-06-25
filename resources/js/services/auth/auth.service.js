class AuthService {
  getGoogleOauthUrl() {
    return axios
        .get('auth/google/url')
        .then(response => {
            return response.data.url
        });
  }

  getGoogleOauthCallback(queryString) {
    return axios
        .get(`auth/google/callback${queryString}`)
        .then(response => {
            return response.data
        })
  }
}

export default new AuthService();