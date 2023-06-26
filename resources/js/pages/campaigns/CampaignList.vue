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