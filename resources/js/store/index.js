import { createStore } from 'vuex'

import auth from '@/store/auth/auth.module'
import campaign from '@/store/campaign/campaign.module'

const store = createStore({
    modules: {
        auth,
        campaign
    }
})

export default store