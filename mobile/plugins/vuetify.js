import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader version "^2.1.1" ,

Vue.use(Vuetify)

Vue.component('pagination', require('laravel-vue-pagination'));

export default ctx => {
  const vuetify = new Vuetify({
    theme: {
      	dark: ctx.app.$cookies.get('darkMode') || false
	},
	rtl: ctx.app.i18n.locale === 'ar' ? true : false
  })

  ctx.app.vuetify = vuetify
  ctx.$vuetify = vuetify.framework
}