import Vue from 'vue';
import numeral from 'numeral';
import VueTheMask from 'vue-the-mask'
import VueChatScroll from 'vue-chat-scroll'

Vue.filter("numeralFormat", function (value) {
    return numeral(value).format("0,0");
});

Vue.use(VueTheMask)
Vue.use(VueChatScroll)

