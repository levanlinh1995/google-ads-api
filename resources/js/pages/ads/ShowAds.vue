<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Ad Detail #{{ $route.params.adsId }}
                    </div>
                    <div>
                        <v-btn @click="onBack" color="grey-lighten-1">
                            Back
                        </v-btn>
                    </div>
                    <div class="mt-7">
                        <div>
                            adsGroupId: {{ formData.adsGroupId }} <br/>
                            name: {{ formData.name }}<br/>
                            status: {{ formData.status }}<br/>
                            headline1: {{ formData.headline1 }}<br/>
                            headline2: {{ formData.headline2 }} <br/>
                            headline3: {{ formData.headline3 }}<br/>
                            description1: {{ formData.description1 }}<br/>
                            description2: {{ formData.description2 }}<br/>
                            url: (note: not done),
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
        };
    },
    created() {
        this.fetchDetail()
    },
    methods: {
        ...mapActions('ads', [
            'detail',
        ]),
        fetchDetail() {
            this.detail(this.$route.params.adsId)
                .then(res => {
                    console.log('fetched');
                    console.log(res);
                    this.formData = {
                        ...this.formData,
                        adsGroupId: res.data.ad_group_id,
                        name: res.data.ad_name,
                        status: res.data.ad_group_ad_status_name,
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
        onBack() {
            this.$router.push({ name: 'ads_list' })
        }
    }
}
</script>