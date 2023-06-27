<template>
  <div class="mt-10">
        <v-card class="mx-auto" max-width="344" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Dashboard
                    </div>
                    <div class="text-center mt-4">
                        <v-btn @click="onNavigateToCampaignList" block class="mb-3" color="grey-lighten-1">
                            Go To Campaigns
                        </v-btn>
                        <v-btn @click="onNavigateToAdsGroupList" block class="mb-3" color="grey-lighten-1">
                            Go To Ads Groups
                        </v-btn>
                        <v-btn @click="onNavigateToAdsList" block class="mb-3" color="grey-lighten-1">
                            Go To Ads
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
        },
        onNavigateToAdsGroupList() {
          this.$router.push({ name: 'adsgroup_list' })
        },
        onNavigateToAdsList() {
            this.$router.push({ name: 'ads_list' })
        }
    }
  }
  </script>