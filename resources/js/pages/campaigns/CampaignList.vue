<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Campaign List
                    </div>
                    <div class="mt-3 text-right">
                        <v-btn @click="onNavigateToCampaignNew" color="indigo-darken-3">
                            Create new campaign
                        </v-btn>
                    </div>
                    <div>
                        <!-- /todo -->
                    </div>
                </div>
            </v-card-item>
        </v-card>
    </div>
</template>
    
<script>
import { mapActions } from 'vuex'

export default {
    created() {
        this.getCampaignList();
    },
    methods: {
        ...mapActions('campaign', [
            'list',
        ]),
        getCampaignList() {
            this.list({})
                .then(data => {
                    console.log(data)
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onNavigateToCampaignNew() {
            this.$router.push({ name: 'campaign_new' })
        }
    }
}
</script>