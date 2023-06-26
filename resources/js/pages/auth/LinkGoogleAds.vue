<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="344" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Google Ads
                    </div>
                    <div class="text-caption">Please use Google Ads Manager Account</div>
                    <div class="text-center mt-4">
                        <v-btn :href="googleAdsLoginUrl" block color="indigo-darken-3">
                            Link with Google Ads
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
            googleAdsLoginUrl: '',
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        ...mapActions('auth', [
            'getGoogleAdsLoginUrl',
        ]),
        fetchData() {
            this.getGoogleAdsLoginUrl()
                .then(url => {
                    this.googleAdsLoginUrl = url
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        }
    }
}
</script>