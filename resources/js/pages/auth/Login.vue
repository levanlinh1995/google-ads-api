<template>
    <h1>
        Login Page</h1>
    <div>
        <v-btn :href="googleOauthUrl">
            Sign in with Google
        </v-btn>
    </div>
</template>
  
<script>
import { mapActions } from 'vuex'

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
        ...mapActions('auth',[
          'getGoogleOauthUrl',
        ]),
        fetchData() {
          this.getGoogleOauthUrl()
                .then(url => {
                    this.googleOauthUrl = url
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        }
    }
}
</script>