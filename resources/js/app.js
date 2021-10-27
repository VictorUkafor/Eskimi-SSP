import Vue from 'vue';
import Campaigns from './components/Campaigns.vue';

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
 
new Vue({
    el: '#app',
    components: { 
        Campaigns,
    }
});
