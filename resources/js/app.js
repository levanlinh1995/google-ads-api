import './bootstrap';
import '../css/app.css'; 

import { createApp } from 'vue'
import router from './route/router';
import App from './layouts/App.vue';


// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
  ssr: true,
})

createApp(App)
  .use(router)
  .use(vuetify)
  .mount("#app")  
