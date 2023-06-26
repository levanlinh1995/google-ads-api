<template>
  <div class="mt-10">
        <v-card class="mx-auto" max-width="344" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Dashboard
                    </div>
                    <div class="text-center mt-4">
                        <v-btn @click="onNavigateToCampaignList" block color="grey-lighten-1">
                            Go To Campaign List
                        </v-btn>
                    </div>
                </div>
            </v-card-item>
        </v-card>
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