import Vue from 'vue';
import UploadButton from 'vuetify-upload-button';
import VueNumerals from 'vue-numerals';
import truncate from 'vue-truncate-collapsed';

Vue.component('upload-btn', UploadButton);
Vue.use(VueNumerals);
Vue.component('truncate', truncate);