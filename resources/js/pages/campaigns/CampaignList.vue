<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="1000" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Campaign List
                    </div>
                    <v-btn @click="onBack" color="grey-lighten-1">
                            Back to Dashboard
                        </v-btn>
                    <div class="mt-3 mb-5 text-right">
                        <v-btn @click="onNavigateToCampaignNew" color="indigo-darken-3">
                            Create new campaign
                        </v-btn>
                    </div>
                    <div>
                        <v-table>
                            <thead>
                                <tr>
                                    <th class="text-left">
                                        Action
                                    </th>
                                    <th class="text-left">
                                        Campaign
                                    </th>
                                    <th class="text-left">
                                        Account
                                    </th>
                                    <th class="text-left">
                                        Status
                                    </th>
                                    <th class="text-left">
                                        Budget
                                    </th>
                                    <th class="text-left">
                                        Optimization Score
                                    </th>
                                    <th class="text-left">
                                        Advertising Channel Type
                                    </th>
                                    <th class="text-left">
                                        Start Date
                                    </th>
                                    <th class="text-left">
                                        End Date
                                    </th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in items" :key="item.id">
                                    <td class="overflow-y-auto">
                                        <v-btn size="x-small" class="mr-2" @click="onDetailPage(item.id)">
                                            Detail
                                        </v-btn>
                                        <v-btn size="x-small" class="mr-2" @click="onEditPage(item.id)">
                                            Edit
                                        </v-btn>
                                        <v-btn size="x-small" @click="onDelete(item)">
                                            Delete
                                        </v-btn>
                                    </td>
                                    <td>{{ item.id }} <br/>{{ item.name }}</td>
                                    <td>{{ item.customer_id }}<br/>{{ item.customer_descriptive_name }}</td>
                                    <td>{{ item.status_name }}</td>
                                    <td>{{ item.budget }}</td>
                                    <td>{{ item.optimization_score }}</td>
                                    <td>{{ item.advertising_channel_type_name }}</td>
                                    <td>{{ item.start_date }}</td>
                                    <td>{{ item.end_date }}</td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                </div>
            </v-card-item>
        </v-card>
    </div>
</template>
    
<script>
import { mapActions } from 'vuex'

export default {
    data: () => ({
        items: [],
    }),
    created() {
        this.getCampaignList();
    },
    mounted() {},
    methods: {
        ...mapActions('campaign', [
            'list',
            'delete'
        ]),
        getCampaignList() {
            this.list()
                .then(data => {
                    console.log(data)
                    this.items = data.data;
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onNavigateToCampaignNew() {
            this.$router.push({ name: 'campaign_new' })
        },
        onEditPage(campaignId) {
            this.$router.push({ name: 'campaign_edit', params: { campaignId: campaignId } })
        },
        onDetailPage(campaignId) {
            this.$router.push({ name: 'campaign_detail', params: { campaignId: campaignId } })
        },
        onDelete(campaign) {
            this.delete(campaign.id)
                .then(data => {
                    console.log('deleted')
                    const index = this.items.indexOf(campaign)
                    this.items.splice(index, 1)
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onBack() {
            this.$router.push({ name: 'home' })
        }

    }
}
</script>