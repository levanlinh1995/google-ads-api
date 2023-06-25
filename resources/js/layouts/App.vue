<template>
  <div>
    <router-link :to="{ name: 'home' }" class="">Home</router-link>

    <router-link class="" :to="{ name: 'login' }">
      Login
    </router-link>

    <div class="nav"></div>

    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>
  
<script>
export default {
  watch: {

  },
};
</script>