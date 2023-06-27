<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Create New Ad
                    </div>
                    <div>
                        <v-btn @click="onBack" color="grey-lighten-1">
                            Back
                        </v-btn>
                    </div>
                    <div class="mt-7">
                        <v-sheet width="400" class="mx-auto">
                            <form @submit.prevent="onSubmit">
                                <v-select required v-model="formData.adsGroupId" item-title="ads_group_name" item-value="ads_group_id" label="Select Ad Group" :items="adsgroups"></v-select>
                                <v-text-field required v-model="formData.name" label="Name"></v-text-field>
                                <v-select required v-model="formData.status" item-title="name" item-value="value" label="Status" :items="status"></v-select>
                                <v-text-field required v-model="formData.headline1" label="Headline 1"></v-text-field>
                                <v-text-field required v-model="formData.headline2" label="Headline 2"></v-text-field>
                                <v-text-field required v-model="formData.headline3" label="Headline 3"></v-text-field>
                                <v-text-field required v-model="formData.description1" label="Description 1"></v-text-field>
                                <v-text-field required v-model="formData.description2" label="Description 2"></v-text-field>
                                <v-text-field required type="url" pattern="https://.*" size="30" v-model="formData.url" label="Url (https://www.example.com)"></v-text-field>
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
                adsGroupId: null,
                name: '',
                status: null,
                headline1: '',
                headline2: '',
                headline3: '',
                description1: '',
                description2: '',
                url: '',
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
            adsgroups: [],
        };
    },
    created() {
        this.getAdsGroupList()
    },
    methods: {
        ...mapActions('ads', [
            'store',
        ]),
        ...mapActions('adsgroup', [
            'list',
        ]),
        onSubmit() {
            this.store(this.formData)
                .then(data => {
                    this.$router.push({ name: 'ads_list' })
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
        onBack() {
            this.$router.push({ name: 'ads_list' })
        },
        getAdsGroupList() {
            this.list()
                .then(data => {
                    console.log(data)
                    this.adsgroups = data.data;
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        },
    }
}
</script>