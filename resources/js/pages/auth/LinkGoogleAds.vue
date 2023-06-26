<template>
    <div>
        <v-btn :href="googleAdsLoginUrl">
            Link with Google Ads
        </v-btn>
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
        ...mapActions('auth',[
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