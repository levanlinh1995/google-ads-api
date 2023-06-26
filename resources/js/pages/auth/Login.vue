<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="344" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Google Login
                    </div>
                    <div class="text-center mt-4">
                        <v-btn :href="googleOauthUrl" block color="indigo-darken-3">
                            Sign in with Google
                        </v-btn>
                    </div>
                </div>
            </v-card-item>
        </v-card>
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
        ...mapActions('auth', [
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