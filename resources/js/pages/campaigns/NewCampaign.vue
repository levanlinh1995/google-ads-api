<template>
    <h1>Create new campaign</h1>
    <div>
        to do
        <v-btn @click="onCreateNew">
            Create new campaign
        </v-btn>
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