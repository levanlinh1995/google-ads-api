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
        this.callbackGoogle();
    },
    methods: {
        ...mapActions('auth',[
          'loginGoogle',
        ]),
        callbackGoogle() {
            const queryString = '?' + new URLSearchParams(this.$route.query).toString();
            this.loginGoogle(queryString)
                .then(response => {
                    this.$router.push({ name: 'link_google_ads' })
                }).catch(
                    (error) => {
                        console.log('Auth failed.')
                        this.$router.push({ name: 'login' })
                    }
                )
        }
    }
}
</script>