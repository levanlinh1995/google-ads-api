<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Create Ads Group
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
                                <v-select required v-model="formData.campaignId" item-title="name" item-value="id" label="Select Campaign" :items="campaigns"></v-select>
                                <v-select required v-model="formData.status" item-title="name" item-value="value" label="Status" :items="status"></v-select>
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
                campaignId: null,
                name: '',
                status: null,
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
            campaigns: [],

        };
    },
    created() {
        this.getCampaignList();
    },
    methods: {
        ...mapActions('adsgroup', [
            'store',
        ]),
        ...mapActions('campaign', [
            'list'
        ]),
        getCampaignList() {
            this.list()
                .then(data => {
                    console.log(data)
                    this.campaigns = data.data;
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onSubmit() {
            this.store(this.formData)
                .then(data => {
                    this.$router.push({ name: 'adsgroup_list' })
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onBack() {
            this.$router.push({ name: 'adsgroup_list' })
        }
    }
}
</script>