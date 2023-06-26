<template>
  <div>
    <h1>Dashboard Page</h1>
    <div>
        <v-btn @click="onNavigateToCampaignList">
            Campaign List
        </v-btn>
    </div>
  </div>
    
  </template>
  
  <script>
  export default {
    methods: {
        onNavigateToCampaignList() {
          this.$router.push({ name: 'campaign_list' })
        }
    }
  }
  </script>