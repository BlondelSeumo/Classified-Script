(window.webpackJsonp=window.webpackJsonp||[]).push([[13],{519:function(t,e,r){},520:function(t,e,r){},521:function(t,e,r){"use strict";var o=r(519);r.n(o).a},522:function(t,e,r){"use strict";var o=r(520);r.n(o).a},523:function(t,e,r){"use strict";r(47);var o,n=r(27),l={name:"sidebar",middleware:"auth",layout:"default/main",data:function(){return{items:[{icon:"mdi-settings",title:"t_account_settings",to:"/account/settings",enabled:!0},{icon:"mdi-image-multiple",title:"t_my_deals",to:"/account/deals",enabled:!0},{icon:"mdi-message",title:"t_message_center",to:"/account/messages",enabled:this.$megate.allow(this.$auth.user,"receive","messages")},{icon:"mdi-tag",title:"t_received_offers",to:"/account/offers",enabled:this.$megate.allow(this.$auth.user,"receive","offers")},{icon:"mdi-folder-search",title:"t_saved_search",to:"/account/search",enabled:this.$megate.allow(this.$auth.user,"save","search")},{icon:"mdi-store",title:"t_following_shops",to:"/account/following",enabled:this.$megate.allow(this.$auth.user,"follow","shops")},{icon:"mdi-wallet-membership",title:"t_membership",to:"/account/subscription",enabled:!0},{divider:!0},{icon:"mdi-two-factor-authentication",title:"t_two_factor_auth",to:"/account/2fa",enabled:!0}]}},methods:{logout:(o=Object(n.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,this.$auth.logout();case 2:this.$router.push({name:"mainIndex"});case 3:case"end":return t.stop()}}),t,this)}))),function(){return o.apply(this,arguments)})}},c=(r(521),r(522),r(52)),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-flex",{staticClass:"user-area",attrs:{xs3:""}},[r("v-navigation-drawer",{staticClass:"m-card pb-3",staticStyle:{height:"auto"},attrs:{width:"auto"}},[r("v-list",{staticClass:"user-sidebar",attrs:{dense:""}},[t._l(t.items,(function(e,o){return[e.divider?r("v-divider",{key:e.to}):t._e(),t._v(" "),e.divider?t._e():r("nuxt-link",{key:o,attrs:{to:e.to,tag:"v-list-item","active-class":"active-item"}},[r("v-list-item",[r("v-list-item-action",[r("v-icon",{staticClass:"v-icon theme--light mdi",class:e.icon})],1),t._v(" "),r("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.title,expression:"item.title"}]})],1)],1)]})),t._v(" "),r("v-list-item",{staticClass:"user-signout",on:{click:function(e){return e.preventDefault(),t.logout()}}},[r("v-list-item-action",[r("v-icon",{staticClass:"v-icon theme--light mdi mdi-logout"})],1),t._v(" "),r("v-list-item-title",[t._v(t._s(t.$t("t_sign_out")))])],1)],2)],1)],1)}),[],!1,null,"7a304184",null);e.a=component.exports},536:function(t,e,r){},562:function(t,e,r){"use strict";var o=r(536);r.n(o).a},640:function(t,e,r){"use strict";r.r(e);r(47);var o,n=r(27),l={middleware:"auth",layout:"default/main",components:{"v-sidebar":r(523).a},asyncData:(o=Object(n.a)(regeneratorRuntime.mark((function t(e){var r,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.$axios,t.next=3,r.get("account/settings");case 3:return o=t.sent,t.abrupt("return",{countries:o.data.countries,seo:{title:o.data.seo.title,separator:o.data.seo.separator,description:o.data.seo.description,favicon:o.data.seo.favicon}});case 5:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{form:{username:this.$auth.user.username,current_password:"",new_password:"",email:this.$auth.user.email,phone:this.$auth.user.phone,avatar:"",preview:"",country:this.$auth.user.country,state:this.$auth.user.state,city:this.$auth.user.city},states:[],cities:[],errors:[],loading:{dialog:!1,button:!1}}},head:function(){return{title:this.$t("t_account_settings"),titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.title),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},methods:{update:function(){var t=this;this.loading.dialog=!0,this.loading.button=!0;var data=new FormData;data.append("username",this.form.username),data.append("current_password",this.form.current_password),data.append("new_password",this.form.new_password),data.append("email",this.form.email),data.append("avatar",this.form.avatar),this.form.phone&&data.append("phone",this.form.phone),this.form.country&&data.append("country",this.form.country),this.form.state&&data.append("state",this.form.state),this.form.city&&data.append("city",this.form.city),this.$axios.post("account/settings",data,{headers:{"Content-Type":"multipart/form-data"}}).then((function(e){t.errors=[],t.$auth.fetchUser(),t.$toasted.show(t.$t("t_toasted_your_profile_updated"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading.dialog=!1,t.loading.button=!1})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),"error_password"===e.response.data?t.$toasted.error(t.$t("t_toasted_passwords_not_matched"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}):t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading.dialog=!1,t.loading.button=!1}))},avatar:function(t){this.form.preview=URL.createObjectURL(t),this.form.avatar=t},userAvatar:function(){return null!==this.$auth.user.avatar?this.$homeApi("storage/app/"+this.$auth.user.avatar):this.$homeApi("storage/app/uploads/default/avatar/noavatar.png")},fetchStates:function(){var t=this;this.loading.dialog=!0,this.$axios.post("ajax/fetch/states",{country:this.form.country}).then((function(e){t.states=e.data.states,t.cities=e.data.cities,t.loading.dialog=!1})).catch((function(e){t.loading.dialog=!1}))},fetchCities:function(){var t=this;this.loading.dialog=!0,this.$axios.post("ajax/fetch/cities",{state:this.form.state}).then((function(e){t.cities=e.data,t.loading.dialog=!1})).catch((function(e){t.loading.dialog=!1}))}},created:function(){null!==this.$auth.user.country&&this.fetchStates()}},c=(r(562),r(52)),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading.dialog,callback:function(e){t.$set(t.loading,"dialog",e)},expression:"loading.dialog"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:"","fill-height":""}},[r("v-sidebar"),t._v(" "),r("v-flex",{attrs:{xs9:""}},[r("v-card",{staticClass:"m-card ml-4",attrs:{flat:""}},[r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{staticClass:"text-center mb-4",attrs:{xs12:""}},[r("v-avatar",{staticClass:"mb-3",attrs:{size:"110"}},[r("img",{attrs:{src:t.form.preview?t.form.preview:t.userAvatar(),alt:"Avatar"}})]),t._v(" "),r("client-only",[r("upload-btn",{staticClass:"text-small",attrs:{accept:"image/*",title:t.$t("t_change_avatar"),color:"grey lighten-3"},on:{"file-update":t.avatar}})],1)],1),t._v(" "),r("v-flex",{attrs:{xs4:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_username"),placeholder:t.$t("t_enter_username"),rules:t.errors.username,error:!!t.errors.username},model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),t._v(" "),r("v-flex",{attrs:{xs4:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_email"),placeholder:t.$t("t_enter_email"),rules:t.errors.email,error:!!t.errors.email,type:"email"},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}})],1),t._v(" "),r("v-flex",{attrs:{xs4:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_phone"),placeholder:t.$t("t_enter_phone"),rules:t.errors.phone,error:!!t.errors.phone},model:{value:t.form.phone,callback:function(e){t.$set(t.form,"phone",e)},expression:"form.phone"}})],1),t._v(" "),r("v-flex",{attrs:{xs4:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.countries,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_country"),placeholder:t.$t("t_choose_country"),rules:t.errors.country,error:!!t.errors.country,dense:""},on:{change:function(e){return t.fetchStates()}},model:{value:t.form.country,callback:function(e){t.$set(t.form,"country",e)},expression:"form.country"}})],1),t._v(" "),r("v-flex",{attrs:{xs4:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.states,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_state"),placeholder:t.$t("t_choose_state"),rules:t.errors.state,error:!!t.errors.state,dense:""},on:{change:function(e){return t.fetchCities()}},model:{value:t.form.state,callback:function(e){t.$set(t.form,"state",e)},expression:"form.state"}})],1),t._v(" "),r("v-flex",{attrs:{xs4:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.cities,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_city"),placeholder:t.$t("t_choose_city"),rules:t.errors.city,error:!!t.errors.city,dense:""},model:{value:t.form.city,callback:function(e){t.$set(t.form,"city",e)},expression:"form.city"}})],1),t._v(" "),r("v-flex",{attrs:{xs6:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_current_password"),placeholder:t.$t("t_enter_current_password"),"persistent-hint":"",hint:t.$t("t_leave_pass_empty"),rules:t.errors.current_password,error:!!t.errors.current_password,type:"password"},model:{value:t.form.current_password,callback:function(e){t.$set(t.form,"current_password",e)},expression:"form.current_password"}})],1),t._v(" "),r("v-flex",{attrs:{xs6:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_new_password"),placeholder:t.$t("t_enter_new_password"),"persistent-hint":"",hint:t.$t("t_leave_pass_empty"),rules:t.errors.new_password,error:!!t.errors.new_password,type:"password"},model:{value:t.form.new_password,callback:function(e){t.$set(t.form,"new_password",e)},expression:"form.new_password"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mt-0",attrs:{depressed:"",loading:t.loading.button,disabled:t.loading.button,block:"",color:"light-blue lighten-1",dark:""},on:{click:function(e){return e.preventDefault(),t.update()}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);