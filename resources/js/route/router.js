
import { createRouter, createWebHistory } from 'vue-router';

const Home = () => import('@/pages/dashboard/Home.vue')
const Login = () => import('@/pages/auth/Login.vue')
const CallBackGoogle = () => import('@/pages/auth/CallbackGoogle.vue')
const LinkGoogleAds = () => import('@/pages/auth/LinkGoogleAds.vue')
const CallBackGoogleAds = () => import('@/pages/auth/CallBackGoogleAds.vue')

const CampaignList = () => import('@/pages/campaigns/CampaignList.vue')
const NewCampaign = () => import('@/pages/campaigns/NewCampaign.vue')
const EditCampaign = () => import('@/pages/campaigns/EditCampaign.vue')
const DetailCampaign = () => import('@/pages/campaigns/ShowCampaign.vue')

const AdsGroupList = () => import('@/pages/adsGroups/AdsGroupList.vue')
const NewAdsGroup = () => import('@/pages/adsGroups/NewAdsGroup.vue')

const AdsList = () => import('@/pages/ads/AdsList.vue')
const NewAds = () => import('@/pages/ads/NewAds.vue')
const EditAds = () => import('@/pages/ads/EditAds.vue')
const ShowAds = () => import('@/pages/ads/ShowAds.vue')


const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/dashboard',
        name: 'home',
        component: Home
    },
    {
        path: '/auth/google-login',
        name: 'login',
        component: Login
    },
    {
        path: '/auth/callback/google',
        name: 'callback_google',
        component: CallBackGoogle
    },
    {
        path: '/auth/link-google-ads',
        name: 'link_google_ads',
        component: LinkGoogleAds
    },
    {
        path: '/auth/callback/googleads',
        name: 'callback_google_ads',
        component: CallBackGoogleAds
    },
    {
        path: '/campaign/list',
        name: 'campaign_list',
        component: CampaignList
    },
    {
        path: '/campaign/new',
        name: 'campaign_new',
        component: NewCampaign
    },
    {
        path: '/campaign/detail/:campaignId',
        name: 'campaign_detail',
        component: DetailCampaign
    },
    {
        path: '/campaign/edit/:campaignId',
        name: 'campaign_edit',
        component: EditCampaign
    },
    {
        path: '/adsgroup/list',
        name: 'adsgroup_list',
        component: AdsGroupList
    },
    {
        path: '/adsgroup/new',
        name: 'adsgroup_new',
        component: NewAdsGroup
    },
    {
        path: '/ads/list',
        name: 'ads_list',
        component: AdsList
    },
    {
        path: '/ads/new',
        name: 'ads_new',
        component: NewAds
    },
    {
        path: '/ads/detail/:adsId',
        name: 'ads_detail',
        component: ShowAds
    },
    {
        path: '/ads/edit/:adsId',
        name: 'ads_edit',
        component: EditAds
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach(async (to, from) => {
    const isAuthenticated = localStorage.getItem('accessToken');
    const publicPages = ['login', 'callback_google'];
    const authRequired = !publicPages.includes(to.name);

    if (
        !isAuthenticated && authRequired
    ) {
        return { name: 'login' }
    }

    if (isAuthenticated && to.name === 'login') {
        return { name: 'home' }
    }
});

export default router;