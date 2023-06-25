<template>
  <h1>Login Page</h1>
  <div>
    <v-btn :href="googleOauthUrl">
      Sign in with Google
    </v-btn>
  </div>
    
    
  </template>
  
  <script>
  export default {
    data() {
        return {
          googleOauthUrl: '',
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios
                .get('/api/auth/google/url')
                .then(response => {
                    this.googleOauthUrl = response.data.url
                });
        }
    }
  }
  </script>