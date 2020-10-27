(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{522:function(t,e,r){},523:function(t,e,r){},524:function(t,e,r){"use strict";var o=r(522);r.n(o).a},525:function(t,e,r){"use strict";var o=r(523);r.n(o).a},526:function(t,e,r){"use strict";r(45);var o,n=r(26),l={name:"sidebar",middleware:"auth",layout:"default/main",data:function(){return{items:[{icon:"mdi-settings",title:"t_account_settings",to:"/account/settings",enabled:!0},{icon:"mdi-image-multiple",title:"t_my_deals",to:"/account/deals",enabled:!0},{icon:"mdi-message",title:"t_message_center",to:"/account/messages",enabled:this.$megate.allow(this.$auth.user,"receive","messages")},{icon:"mdi-tag",title:"t_received_offers",to:"/account/offers",enabled:this.$megate.allow(this.$auth.user,"receive","offers")},{icon:"mdi-folder-search",title:"t_saved_search",to:"/account/search",enabled:this.$megate.allow(this.$auth.user,"save","search")},{icon:"mdi-store",title:"t_following_shops",to:"/account/following",enabled:this.$megate.allow(this.$auth.user,"follow","shops")},{icon:"mdi-wallet-membership",title:"t_membership",to:"/account/subscription",enabled:!0},{divider:!0},{icon:"mdi-two-factor-authentication",title:"t_two_factor_auth",to:"/account/2fa",enabled:!0}]}},methods:{logout:(o=Object(n.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,this.$auth.logout();case 2:this.$router.push({name:"mainIndex"});case 3:case"end":return t.stop()}}),t,this)}))),function(){return o.apply(this,arguments)})}},c=(r(524),r(525),r(52)),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-flex",{staticClass:"user-area",attrs:{xs3:""}},[r("v-navigation-drawer",{staticClass:"m-card pb-3",staticStyle:{height:"auto"},attrs:{width:"auto"}},[r("v-list",{staticClass:"user-sidebar",attrs:{dense:""}},[t._l(t.items,(function(e,o){return[e.divider?r("v-divider",{key:e.to}):t._e(),t._v(" "),e.divider?t._e():r("nuxt-link",{key:o,attrs:{to:e.to,tag:"v-list-item","active-class":"active-item"}},[r("v-list-item",[r("v-list-item-action",[r("v-icon",{staticClass:"v-icon theme--light mdi",class:e.icon})],1),t._v(" "),r("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.title,expression:"item.title"}]})],1)],1)]})),t._v(" "),r("v-list-item",{staticClass:"user-signout",on:{click:function(e){return e.preventDefault(),t.logout()}}},[r("v-list-item-action",[r("v-icon",{staticClass:"v-icon theme--light mdi mdi-logout"})],1),t._v(" "),r("v-list-item-title",[t._v(t._s(t.$t("t_sign_out")))])],1)],2)],1)],1)}),[],!1,null,"7a304184",null);e.a=component.exports},651:function(t,e,r){"use strict";r.r(e);r(45);var o,n=r(26),l={middleware:"auth",layout:"default/main",components:{"v-sidebar":r(526).a},asyncData:(o=Object(n.a)(regeneratorRuntime.mark((function t(e){var r,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.$axios,t.next=3,r.post("account/2fa");case 3:return o=t.sent,t.abrupt("return",{account:o.data.user,google2fa_url:o.data.google2fa_url,seo:{title:o.data.seo.title,separator:o.data.seo.separator,description:o.data.seo.description,favicon:o.data.seo.favicon}});case 5:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{form:{verifyCode:"",password:""},errors:[],loading:!1}},head:function(){return{title:this.$t("t_2fa_verification"),titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.title),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},methods:{generate:function(){var t=this;this.loading=!0,this.$axios.post("account/2fa/generate").then((function(e){t.account.password_security=e.data.password_security,t.google2fa_url=e.data.google2fa_url,t.$toasted.show(t.$t("t_toasted_2fa_secret_generated"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1})).catch((function(e){t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1}))},enable:function(){var t=this;""!==this.form.verifyCode&&(this.loading=!0,this.$axios.post("account/2fa/enable",{verifyCode:this.form.verifyCode}).then((function(e){e.data.isFailed?(t.$toasted.error(t.$t("t_toasted_2fa_code_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1):(t.account.password_security=e.data,t.$toasted.show(t.$t("t_toasted_2fa_enabled"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1)})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1})))},disable:function(){var t=this;""!==this.form.password&&(this.loading=!0,this.$axios.post("account/2fa/disable",{password:this.form.password}).then((function(e){e.data.isFailed?(t.$toasted.error(t.$t("t_toasted_wrong_password"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1):(t.form.verifyCode=null,t.account.password_security=e.data,t.$toasted.show(t.$t("t_toasted_2fa_disabled"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1)})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1})))}}},c=r(52),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:"","fill-height":""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pb-5",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title pb-3"},[t._v(t._s(t.$t("t_2fa_verification")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_2fa_verification_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-row",{attrs:{justify:"center"}},[r("v-flex",{staticClass:"text-center",attrs:{xs10:""}},[t.account.password_security?t.account.password_security.google2fa_enable?r("div",[r("h1",{staticClass:"headline font-weight-black text-uppercase my-5",domProps:{innerHTML:t._s(t.$t("t_2fa_currently_enabled"))}}),t._v(" "),r("p",{staticClass:"title pb-4"},[t._v(t._s(t.$t("t_2fa_disable_para")))]),t._v(" "),r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_password"),placeholder:t.$t("t_enter_password"),rules:t.errors.password,error:!!t.errors.password,type:"password"},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}}),t._v(" "),r("v-btn",{staticClass:"white--text",attrs:{block:"",depressed:"",color:t.$vuetify.theme.dark?"#606060":"grey darken-3"},on:{click:function(e){return e.preventDefault(),t.disable()}}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_disable_2fa"))+"\n\t\t\t\t\t\t\t\t\t\t\t\t")])],1):r("div",[r("h1",{staticClass:"headline font-weight-black text-uppercase my-5"},[t._v(t._s(t.$t("t_download_2fa_app")))]),t._v(" "),r("v-row",[r("v-col",{attrs:{cols:"6",md:"4"}},[r("v-btn",{attrs:{color:"black",dark:"",depressed:"",block:"",href:"https://authy.com/download/",target:"_blank"}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAuthy\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t"),r("v-icon",{attrs:{right:""}},[t._v("mdi-download")])],1)],1),t._v(" "),r("v-col",{attrs:{cols:"6",md:"4"}},[r("v-btn",{attrs:{color:"black",dark:"",depressed:"",block:"",href:"https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2",target:"_blank"}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAuthenticator\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t"),r("v-icon",{attrs:{right:""}},[t._v("mdi-google-play")])],1)],1),t._v(" "),r("v-col",{attrs:{cols:"6",md:"4"}},[r("v-btn",{attrs:{color:"black",dark:"",depressed:"",block:"",href:"https://apps.apple.com/us/app/google-authenticator/id388497605",target:"_blank"}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\tAuthenticator\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t"),r("v-icon",{attrs:{right:""}},[t._v("mdi-apple")])],1)],1)],1),t._v(" "),r("h1",{staticClass:"headline font-weight-black text-uppercase my-5"},[t._v(t._s(t.$t("t_scan_this_qrcode")))]),t._v(" "),r("img",{attrs:{src:t.google2fa_url,alt:""}}),t._v(" "),r("h1",{staticClass:"headline font-weight-black text-uppercase my-5"},[t._v(t._s(t.$t("t_enter_6_digit_generated")))]),t._v(" "),r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_verification_code"),placeholder:t.$t("t_enter_verification_code"),rules:t.errors.verifyCode,error:!!t.errors.verifyCode},model:{value:t.form.verifyCode,callback:function(e){t.$set(t.form,"verifyCode",e)},expression:"form.verifyCode"}}),t._v(" "),r("v-btn",{staticClass:"mt-4",attrs:{block:"",depressed:"",color:t.$vuetify.theme.dark?"#606060":"grey darken-3",dark:""},on:{click:function(e){return e.preventDefault(),t.enable()}}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_enable_2fa"))+"\n\t\t\t\t\t\t\t\t\t\t\t\t")])],1):r("div",[r("v-btn",{staticClass:"white--text",attrs:{block:"",depressed:"",color:t.$vuetify.theme.dark?"#606060":"grey darken-3"},on:{click:function(e){return e.preventDefault(),t.generate()}}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_generate_2fa_secret"))+"\n\t\t\t\t\t\t\t\t\t\t\t\t")])],1)])],1)],1)],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);