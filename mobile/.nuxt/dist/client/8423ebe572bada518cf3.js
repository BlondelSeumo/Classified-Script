(window.webpackJsonp=window.webpackJsonp||[]).push([[31],{536:function(t,e,r){},560:function(t,e,r){"use strict";var o=r(536);r.n(o).a},633:function(t,e,r){"use strict";r.r(e);r(45);var o,n,l,c,d=r(26),_={middleware:"guest",asyncData:(c=Object(d.a)(regeneratorRuntime.mark((function t(e){var r,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.$axios,t.next=3,r.get("auth/login");case 3:return o=t.sent,t.abrupt("return",{settings:{login_type:o.data.login.login_type,register:o.data.login.register,logo:o.data.logo.logo,social:o.data.social,recaptcha:o.data.recaptcha.recaptcha,siteKey:o.data.siteKey},seo:{title:o.data.seo.title,tagline:o.data.seo.tagline,separator:o.data.seo.separator,description:o.data.seo.description,favicon:o.data.seo.favicon}});case 5:case"end":return t.stop()}}),t)}))),function(t){return c.apply(this,arguments)}),data:function(){return{isClient:!1,loading:!1,isRequired2FA:!1,loginRequires2FA:!1,directLogin:!1,form:{credentials:"",password:"",verifyCode:"",remember:""},errors:[]}},head:function(){return{title:this.$t("t_login"),titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.title),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"index, follow"},{property:"og:type",content:"article"},{property:"og:title",content:"".concat(this.$t("t_login")," ").concat(this.seo.separator," ").concat(this.seo.title)},{property:"og:description",content:this.seo.description},{property:"og:image",content:this.logo},{property:"og:url",content:this.$home("auth/login")},{property:"og:site_name",content:this.seo.title},{property:"twitter:title",content:"".concat(this.$t("t_login")," ").concat(this.seo.separator," ").concat(this.seo.title)},{property:"twitter:description",content:this.seo.description},{property:"twitter:image",content:this.logo}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},methods:{login:(l=Object(d.a)(regeneratorRuntime.mark((function t(){var e,data,r=this;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(this.form.credentials&&this.form.password){t.next=2;break}return t.abrupt("return");case 2:if(this.loading=!0,!this.recaptcha){t.next=9;break}return t.next=6,this.$recaptcha.execute("login");case 6:t.t0=t.sent,t.next=10;break;case 9:t.t0=null;case 10:return e=t.t0,data={credentials:this.form.credentials,password:this.form.password,verifyCode:this.form.verifyCode,captchaToken:e},t.next=14,this.$auth.login({data:data}).then((function(){r.loading=!1,r.$router.push(r.$route.query.redirect?r.$route.query.redirect:"/")})).catch((function(t){if("two_factor_required"===t.response.data.state)r.loginRequires2FA=!0,r.$toasted.error(r.$t("t_toasted_2fa_required"),{icon:"error",className:r.$vuetify.rtl?"toasted-rtl":""});else if("two_factor_wrong"===t.response.data.state)r.errors=[],r.$toasted.error(r.$t("t_toasted_2fa_wrong_code"),{icon:"error",className:r.$vuetify.rtl?"toasted-rtl":""});else if("Unauthorized"===t.response.data.error)r.$toasted.error(r.$t("t_toasted_credentials_wrong"),{icon:"error",className:r.$vuetify.rtl?"toasted-rtl":""});else if(429===t.response.status){if(r.$toasted.clear(),t.response.data.errors)var e=t.response.data.errors.credentials[0];else e=r.$t("t_toasted_too_many_attempts");r.$toasted.error(e,{icon:"block",className:r.$vuetify.rtl?"toasted-warning toasted-rtl":"toasted-warning"})}else r.$toasted.error(r.$t("t_toasted_something_went_wrong"),{icon:"error",className:r.$vuetify.rtl?"toasted-rtl":""}),r.errors=[];r.loading=!1}));case 14:case"end":return t.stop()}}),t,this)}))),function(){return l.apply(this,arguments)}),authenticate:(n=Object(d.a)(regeneratorRuntime.mark((function t(e){var r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,this.$axios.get("auth/twitter");case 2:r=t.sent,window.location.href=r.data;case 4:case"end":return t.stop()}}),t,this)}))),function(t){return n.apply(this,arguments)}),socialite:function(t,data){var e=this;this.loading=!0,this.$axios.post("auth/"+t,data).then((function(t){e.$auth.token("default_auth_token",t.data),e.$auth.watch.authenticated=!0,e.$auth.fetch({params:{},success:function(){},error:function(){}}),e.$router.push("/"),e.loading=!1})).catch((function(t){e.loading=!1,console.log(t)}))}},computed:{loginType:function(){return"username"===this.settings.login_type?this.$t("t_username"):"email"===this.settings.login_type?this.$t("t_email"):"phone"===this.settings.login_type?this.$t("t_phone"):"ue"===this.settings.login_type?this.$t("t_username_or_email"):"uep"===this.settings.login_type?this.$t("t_username_or_email_or_phone"):void 0},logo:function(){return null!==this.settings.logo?this.$homeApi("storage/app/"+this.settings.logo):this.$homeApi("storage/app/uploads/default/settings/logo/white.png")}},mounted:(o=Object(d.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,this.$recaptcha.init();case 2:case"end":return t.stop()}}),t,this)}))),function(){return o.apply(this,arguments)})},h=(r(560),r(52)),component=Object(h.a)(_,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-container",{staticClass:"pa-0",attrs:{fluid:""}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n                "+t._s(t.$t("t_loading"))+"\n            ")])],1),t._v(" "),r("v-layout",{attrs:{row:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"hd-fullscreen pa-12 no-border-radius",attrs:{flat:""}},[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{staticClass:"follow-btn",attrs:{xs12:""}},[r("v-btn",{staticClass:"auth-goback",attrs:{text:"",icon:"",nuxt:"",to:"/"}},[r("v-icon",[t._v(t._s(t.$vuetify.rtl?"mdi-arrow-left-thick":"mdi-arrow-right-thick"))])],1),t._v(" "),r("h1",{staticClass:"title font-weight-black mb-1"},[t._v(t._s(t.$t("t_login")))]),t._v(" "),r("span",{staticClass:"caption grey--text lighten-3"},[t._v(t._s(t.$t("t_login_to_your_account")))])],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.loginType,placeholder:t.$t("t_enter_your_credentials"),rules:t.errors.credentials,error:!!t.errors.credentials},model:{value:t.form.credentials,callback:function(e){t.$set(t.form,"credentials",e)},expression:"form.credentials"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_password"),placeholder:t.$t("t_enter_password"),rules:t.errors.password,error:!!t.errors.password,type:"password",autocomplete:"off"},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}})],1),t._v(" "),t.loginRequires2FA?r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_2fa_verification"),hint:t.$t("t_6_numbers"),placeholder:t.$t("t_enter_verification_code"),rules:t.errors.verifyCode,error:!!t.errors.verifyCode},model:{value:t.form.verifyCode,callback:function(e){t.$set(t.form,"verifyCode",e)},expression:"form.verifyCode"}})],1):t._e(),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-2 white--text",attrs:{depressed:"",disabled:t.loading,loading:t.loading,block:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.login(e)}}},[t._v(t._s(t.$t("t_login")))]),t._v(" "),t.settings.register?r("v-btn",{staticClass:"mb-2",attrs:{to:"/auth/register",depressed:"",block:"",dark:"",color:"blue"}},[t._v(t._s(t.$t("t_register")))]):t._e(),t._v(" "),r("v-btn",{staticClass:"mb-2",attrs:{to:"/auth/password/reset",depressed:"",block:"",dark:"",color:"blue"}},[t._v(t._s(t.$t("t_password_reset")))])],1),t._v(" "),r("v-flex",{staticClass:"mt-5"},[r("v-divider")],1),t._v(" "),r("v-flex",{staticClass:"mt-5 follow-btn",attrs:{xs12:""}},[t.settings.social.isFacebook?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#3C5A99"},on:{click:function(e){return t.$auth.loginWith("facebook")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_facebook"))+"\n                                ")]):t._e(),t._v(" "),t.settings.social.isTwitter?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#1DA1F2"},on:{click:function(e){return e.preventDefault(),t.authenticate("twitter")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_twitter"))+"\n                                ")]):t._e(),t._v(" "),t.settings.social.isGoogle?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#DC4E41"},on:{click:function(e){return t.$auth.loginWith("google")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_google"))+"\n                                ")]):t._e(),t._v(" "),t.settings.social.isInstagram?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#E4405F"},on:{click:function(e){return t.authenticate("instagram")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_instagram"))+"\n                                ")]):t._e(),t._v(" "),t.settings.social.isPinterest?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#BD081C"},on:{click:function(e){return t.authenticate("pinterest")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_pinterest"))+"\n                                ")]):t._e(),t._v(" "),t.settings.social.isLinkedin?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#0077B5"},on:{click:function(e){return t.authenticate("linkedin")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_linkedin"))+"\n                                ")]):t._e(),t._v(" "),t.settings.social.isVk?r("v-btn",{staticClass:"px-5 mb-4",attrs:{block:"",depressed:"",dark:"",small:"",text:"",color:t.$vuetify.theme.dark?"#bbbbbb":"#6383A8"},on:{click:function(e){return t.authenticate("vk")}}},[t._v("\n                                    "+t._s(t.$t("t_login_via_vk"))+"\n                                ")]):t._e()],1)],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);