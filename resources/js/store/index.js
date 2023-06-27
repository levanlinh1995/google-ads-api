import { createStore } from 'vuex'

import auth from '@/store/auth/auth.module'
import campaign from '@/store/campaign/campaign.module'
import adsgroup from '@/store/adsgroup/adsgroup.module'
import ads from '@/store/ads/ads.module'

const store = createStore({
    modules: {
        auth,
        campaign,
        adsgroup,
        ads
    }
})

export default store