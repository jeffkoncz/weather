import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';

import axios from 'axios'

Vue.config.productionTip = false

// Allows axios access throughout the app via this.$http
Vue.prototype.$http = axios.create({
  baseURL: process.env.VUE_APP_API_HOST
})

new Vue({
  vuetify,
  render: h => h(App)
}).$mount('#app')
