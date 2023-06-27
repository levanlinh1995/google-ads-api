<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Edit Ad #{{ $route.params.adsId }}
                    </div>
                    <div>
                        <v-btn @click="onBack" color="grey-lighten-1">
                            Back
                        </v-btn>
                    </div>
                    <div class="mt-7">
                        <v-sheet width="400" class="mx-auto">
                            <form @submit.prevent="onSubmit">
                                <v-select disabled required v-model="formData.adsGroupId" item-title="ads_group_name" item-value="ads_group_id" label="Select Ad Group" :items="adsgroups"></v-select>
                                <v-text-field required v-model="formData.name" label="Name"></v-text-field>
                                <v-select disabled required v-model="formData.status" item-title="name" item-value="value" label="Status" :items="status"></v-select>
                                <v-text-field required v-model="formData.headline1" label="Headline 1"></v-text-field>
                                <v-text-field required v-model="formData.headline2" label="Headline 2"></v-text-field>
                                <v-text-field required v-model="formData.headline3" label="Headline 3"></v-text-field>
                                <v-text-field required v-model="formData.description1" label="Description 1"></v-text-field>
                                <v-text-field required v-model="formData.description2" label="Description 2"></v-text-field>
                                <v-text-field required type="url" pattern="https://.*" size="30" v-model="formData.url" label="Url (http://www.example.com)"></v-text-field>
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
        this.fetchDetail()
    },
    mounted() {
    },
    methods: {
        ...mapActions('ads', [
            'store',
            'detail',
            'update',
        ]),
        ...mapActions('adsgroup', [
            'list',
        ]),
        onSubmit() {
            this.update({adsId: this.$route.params.adsId, data: this.formData})
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
        fetchDetail() {
            this.detail(this.$route.params.adsId)
                .then(res => {
                    console.log('fetched');
                    console.log(res);
                    this.formData = {
                        ...this.formData,
                        adsGroupId: res.data.ad_group_id,
                        name: res.data.ad_name,
                        status: res.data.ad_group_ad_status,
                        headline1: res.data.headlines[0]['HEADLINE_1'],
                        headline2: res.data.headlines[1]['HEADLINE_2'],
                        headline3: res.data.headlines[2]['HEADLINE_3'],
                        description1: res.data.descriptions[0]['DESCRIPTION_1'],
                        description2: res.data.descriptions[1]['DESCRIPTION_2'],
                        url: '',
                    }
                }).catch(
                    (error) => {
                        // todo
                    }
                )
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