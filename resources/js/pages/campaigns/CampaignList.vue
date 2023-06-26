<template>
    <div>
      <h1>List campaign</h1>
      <div>
          <v-btn @click="onNavigateToCampaignNew">
              Create new campaign
          </v-btn>
      </div>
      <div>

      </div>
    </div>
      
    </template>
    
    <script>
    export default {
        methods: {
            onNavigateToCampaignNew() {
                this.$router.push({ name: 'campaign_new' })
            }
        }
    }
    </script>