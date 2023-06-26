class AuthService {
  getGoogleOauthUrl() {
    return axios
        .get('auth/google/url')
        .then(response => {
            return response.data.url
        });
  }

  loginGoogle(queryString) {
    return axios
        .get(`auth/google/callback${queryString}`)
        .then(response => {
            return response.data
        })
  }

  getGoogleAdsLoginUrl() {
    return axios
      .get('auth/google-ads-login-url')
      .then(response => {
          return response.data.url
      });
  }

  generateGoogleAdsRefreshToken(token) {
    return axios
        .get(`auth/generate-google-ads-refresh-token?token=${token}`)
        .then(response => {
            return response.data
        })
  }
}

export default new AuthService();