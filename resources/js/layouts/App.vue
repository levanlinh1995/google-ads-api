<template>
  <div>
    <div v-if="!isLoggedIn">
      <router-link class="" :to="{ name: 'login' }">
        Login
      </router-link>
    </div>
    <div v-else>
      <router-link :to="{ name: 'home' }" class="">Dashboard</router-link>
    </div>

    <div class="nav"></div>

    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>
  
<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters('auth',[
      'isLoggedIn',
    ]),
  }
  
}
</script>