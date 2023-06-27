<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Edit Campaign #{{ $route.params.campaignId }}
                    </div>
                    <div>
                        <v-btn @click="onBack" color="grey-lighten-1">
                            Back
                        </v-btn>
                    </div>
                    <div class="mt-7">
                        <v-sheet width="400" class="mx-auto">
                            <form @submit.prevent="onSubmit">
                                <v-text-field required type="number" v-model="formData.customerId" label="Customer ID"></v-text-field>
                                <v-text-field required v-model="formData.name" label="Name"></v-text-field>
                                <v-text-field required type="number" v-model="formData.budget" label="Budget"></v-text-field>
                                <v-select disabled required v-model="formData.advertisingChannelType" item-title="name" item-value="value" label="Channel type" :items="advertisingChannelType"></v-select>
                                <v-select required v-model="formData.status" item-title="name" item-value="value" label="Status" :items="status"></v-select>
                                <v-text-field disabled required type="date" v-model="formData.start_date" label="Start date"></v-text-field>
                                <v-text-field disabled required type="date" v-model="formData.end_date" label="End date"></v-text-field>
                                <v-btn color="indigo-darken-3" type="submit">
                                    submit
                                </v-btn>
                            </form>
                        </v-sheet>
                    </div>
                </div>
            </v-card-item>
        </v-card>
    </div>
</template>
  
<script>
import { mapActions } from 'vuex'

export default {
    data() {
        return {
            formData: {
                customerId: '',
                name: '',
                budget: '',
                advertisingChannelType: null,
                status: null,
                start_date: '',
                end_date: '',
                campaignBudgetId: ''
            },
            status: [
                {
                    name: 'Enabled',
                    value: 2
                },
                {
                    name: 'Paused',
                    value: 3
                }
            ],
            advertisingChannelType: [
                {
                    name: 'Search',
                    value: 2
                },
                // {
                //     name: 'Display',
                //     value: 3
                // },
                // {
                //     name: 'Shopping',
                //     value: 4
                // }
            ]
        };
    },
    created() {
        this.fetchDetail()
    },
    methods: {
        ...mapActions('campaign', [
            'store',
            'detail',
            'update',
        ]),
        onSubmit() {
            this.update({campaignId: this.$route.params.campaignId, data: this.formData})
                .then(data => {
                    console.log('updated');
                    this.$router.push({ name: 'campaign_list' })
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        fetchDetail() {
            this.detail(this.$route.params.campaignId)
                .then(data => {
                    console.log('fetched');
                    console.log(data);
                    data = data.data;
                    this.formData = {
                        ...this.formData,
                        customerId: data.customer_id,
                        name: data.name,
                        budget: data.budget,
                        advertisingChannelType: data.advertising_channel_type,
                        status: data.status,
                        start_date: data.start_date,
                        end_date: data.end_date,
                        campaignBudgetId: data.campaign_budget_id,
                    }
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onBack() {
            this.$router.push({ name: 'campaign_list' })
        }
    }
}
</script>