<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Campaign Detail #{{ $route.params.campaignId }}
                    </div>
                    <div>
                        <v-btn @click="onBack" color="grey-lighten-1">
                            Back
                        </v-btn>
                    </div>
                    <div class="mt-7">
                        <div>
                            Account Id: {{ formData.customerId }} <br/>
                            Campaign Id: {{ formData.id }} <br/>
                            Name: {{ formData.name }} <br/>
                            Status: {{ formData.statusName }} <br/>
                            Budget: {{ formData.budget }} <br/>
                            Optimization Score: {{ formData.optimization_score }} <br/>
                            Channel Type: {{ formData.advertising_channel_type }} <br/>
                            Start Date: {{ formData.startDate }} <br/>
                            End Date: {{ formData.endDate }} <br/>
                        </div>
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
                statusName: null,
                startDate: '',
                endDate: '',
                campaignBudgetId: ''
            },
        };
    },
    created() {
        this.fetchDetail()
    },
    methods: {
        ...mapActions('campaign', [
            'detail',
        ]),
        fetchDetail() {
            this.detail(this.$route.params.campaignId)
                .then(data => {
                    console.log('fetched');
                    console.log(data);
                    data = data.data;
                    this.formData = {
                        ...this.formData,
                        customerId: data.customer_id,
                        id: data.id,
                        name: data.name,
                        budget: data.budget,
                        advertisingChannelType: data.advertising_channel_type,
                        statusName: data.status_name,
                        startDate: data.start_date,
                        endDate: data.end_date,
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