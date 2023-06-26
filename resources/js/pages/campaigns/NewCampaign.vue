<template>
    <div class="mt-10">
        <v-card class="mx-auto" max-width="800" variant="outlined">
            <v-card-item>
                <div>
                    <div class="text-h6 mb-1">
                        Create New Campaign
                    </div>
                    <div class="mt-3 text-right">
                        <v-btn @click="onCreateNew" color="indigo-darken-3">
                            Submit
                        </v-btn>
                    </div>
                    <div>
                        <!-- /todo -->
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
        };
    },
    created() {
    },
    methods: {
        ...mapActions('campaign',[
          'store',
        ]),
        onCreateNew() {
            this.store({})
                .then(data => {
                    console.log(data)
                }).catch(
                    (error) => {
                        // todo
                    }
                )
        }
    }
}
</script>