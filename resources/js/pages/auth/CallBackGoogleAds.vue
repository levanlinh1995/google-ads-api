<template>
</template>
    
<script>
import { mapActions } from 'vuex'

export default {
    data() {
        return {
        };
    },
    created() {
        this.callbackGoogleAds();
    },
    methods: {
        ...mapActions('auth',[
          'generateGoogleAdsRefreshToken',
        ]),
        callbackGoogleAds() {
            const code = this.$route.query.code;
            this.generateGoogleAdsRefreshToken(code)
                .then(response => {
                    this.$router.push({ name: 'home' })
                }).catch(
                    (error) => {
                        console.log('Google Ads Auth failed.')
                        this.$router.push({ name: 'login' })
                    }
                )
        }
    }
}
</script>