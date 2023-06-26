<template>
    <div>
        <v-container class="mb-6">
            <v-row align="start" no-gutters style="height: 150px;">
                <v-col>
                    <div>
                        <h1 class="text-center">Welcome to Google Ads Management</h1>
                        <h5 class="text-center">Helping you manage your campaign</h5>
                    </div>
                    <div class="container">
                        <router-view></router-view>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
  
<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters('auth', [
            'isLoggedIn',
        ]),
    }

}
</script>