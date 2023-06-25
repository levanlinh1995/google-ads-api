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
          'googleLogin',
        ]),
        callbackGoogle() {
            const queryString = '?' + new URLSearchParams(this.$route.query).toString();
            this.googleLogin(queryString)
                .then(response => {
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