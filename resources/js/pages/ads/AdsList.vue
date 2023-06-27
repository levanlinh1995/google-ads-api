<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="1000" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Ads List
                    </div>
                    <v-btn @click="onBack" color="grey-lighten-1">
                            Back to Dashboard
                        </v-btn>
                    <div class="mt-3 mb-5 text-right">
                        <v-btn @click="onNavigateToAdsNew" color="indigo-darken-3">
                            Create new ads
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
                                        Ad Name
                                    </th>
                                    <th class="text-left">
                                        Ad Group
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
                                    <td>{{ item.ad_id }} ({{ item.ad_name }})</td>
                                    <td>{{ item.ad_group_id }} ({{ item.ad_group_name }})</td>
                                    <td>{{ item.campaign_id }} ({{ item.campaign_name }})</td>
                                    <td>{{ item.account_id }} ({{ item.account_name }})</td>
                                    <td>{{ item.ad_group_ad_status }}</td>
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
        this.getList();
    },
    mounted() {},
    methods: {
        ...mapActions('ads', [
            'list',
            'delete'
        ]),
        getList() {
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
        onNavigateToAdsNew() {
            this.$router.push({ name: 'ads_new' })
        },
        onEditPage(adsId) {
            this.$router.push({ name: 'ads_edit', params: { adsId: adsId } })
        },
        onDetailPage(adsId) {
            this.$router.push({ name: 'ads_detail', params: { adsId: adsId } })
        },
        onDelete(item) {
            this.delete(item.id)
                .then(data => {
                    console.log('deleted')
                    const index = this.items.indexOf(item)
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