<template>
</template>
    
<script>
export default {
    data() {
        return {
        };
    },
    created() {
        this.callbackGoogle();
    },
    methods: {
        callbackGoogle() {
            const queryString = '?' + new URLSearchParams(this.$route.query).toString();
            axios
                .get(`/api/auth/google/callback${queryString}`)
                .then(response => {
                    this.googleOauthUrl = response.data.url
                    this.$router.push({ name: 'home' })
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