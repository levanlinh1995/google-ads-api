<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Create New Campaign
                    </div>
                    <div>
                        <v-btn @click="onBack" color="grey-lighten-1">
                            Back
                        </v-btn>
                    </div>
                    <div class="mt-7">
                        <v-sheet width="400" class="mx-auto">
                            <form @submit.prevent="onSubmit">
                                <v-text-field required v-model="formData.name" label="Name"></v-text-field>
                                <v-text-field required type="number" v-model="formData.budget" label="Budget"></v-text-field>
                                <v-select required v-model="formData.advertisingChannelType" item-title="name" item-value="value" label="Channel type" :items="advertisingChannelType"></v-select>
                                <v-select required v-model="formData.status" item-title="name" item-value="value" label="Status" :items="status"></v-select>
                                <v-text-field required type="date" v-model="formData.start_date" label="Start date"></v-text-field>
                                <v-text-field required type="date" v-model="formData.end_date" label="End date"></v-text-field>
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
                name: '',
                budget: '',
                advertisingChannelType: null,
                status: null,
                start_date: '',
                end_date: ''
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
    created() {},
    methods: {
        ...mapActions('campaign', [
            'store',
        ]),
        onSubmit() {
            this.store(this.formData)
                .then(data => {
                    this.$router.push({ name: 'campaign_list' })
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