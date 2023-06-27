<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="1000" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Ads Group List
                    </div>
                    <v-btn @click="onBack" color="grey-lighten-1">
                            Back to Dashboard
                        </v-btn>
                    <div class="mt-3 mb-5 text-right">
                        <v-btn @click="onNavigateToAdsGroupNew" color="indigo-darken-3">
                            Create new Ads Group
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
                                        <v-btn size="x-small" @click="onDelete(item)">
                                            Delete
                                        </v-btn>
                                    </td>
                                    <td>{{ item.ads_group_id }} <br/>{{ item.ads_group_name }}</td>
                                    <td>{{ item.campaign_id }} {{ item.campaign_name }}</td>
                                    <td>{{ item.customer_id }} <br/> {{ item.customer_descriptive_name }}</td>
                                    <td>{{ item.ads_group_status_name }}</td>
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
        this.getAdsGroupList();
    },
    mounted() {},
    methods: {
        ...mapActions('adsgroup', [
            'list',
            'delete'
        ]),
        getAdsGroupList() {
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
        onNavigateToAdsGroupNew() {
            this.$router.push({ name: 'adsgroup_new' })
        },
        onDelete(adsgroup) {
            this.delete(adsgroup.ads_group_id)
                .then(data => {
                    console.log('deleted')
                    const index = this.items.indexOf(adsgroup)
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