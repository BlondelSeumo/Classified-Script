(window.webpackJsonp=window.webpackJsonp||[]).push([[95],{592:function(t,r,e){"use strict";e.r(r);e(45);var o,l=e(26),n={layout:"default/admin",middleware:"administrator",asyncData:(o=Object(l.a)(regeneratorRuntime.mark((function t(r){var e,o,l,n;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=r.app,o=r.$axios,l=r.redirect,e.$owgate.allow(e.$auth.user,"footer","settings")||l("/administrator"),t.next=4,o.get("administrator/settings/footer");case 4:return n=t.sent,t.abrupt("return",{form:{copyrights:n.data.config.copyrights,terms:n.data.config.terms,privacy:n.data.config.privacy,firstColumn:n.data.config.rows.first,secondColumn:n.data.config.rows.second,thirdColumn:n.data.config.rows.third,fourthColumn:n.data.config.rows.fourth,mailchimp_key:n.data.newsletter.apiKey,mailchimp_id:n.data.newsletter.lists.subscribers.id,facebook:n.data.config.links.facebook,twitter:n.data.config.links.twitter,google:n.data.config.links.google,instagram:n.data.config.links.instagram,linkedin:n.data.config.links.linkedin,vk:n.data.config.links.vk,tumblr:n.data.config.links.tumblr,youtube:n.data.config.links.youtube},pages:n.data.pages});case 6:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{errors:[],loading:!1}},head:function(){return{title:this.$t("t_footer_settings"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){var t=this;this.$owgate.allow(this.$auth.user,"footer","settings")?(this.loading=!0,this.$axios.post("administrator/settings/footer",{copyrights:this.form.copyrights,terms:this.form.terms,privacy:this.form.privacy,firstColumn:this.form.firstColumn,secondColumn:this.form.secondColumn,thirdColumn:this.form.thirdColumn,fourthColumn:this.form.fourthColumn,mailchimp_key:this.form.mailchimp_key,mailchimp_id:this.form.mailchimp_id,facebook:this.form.facebook,twitter:this.form.twitter,google:this.form.google,instagram:this.form.instagram,linkedin:this.form.linkedin,vk:this.form.vk,tumblr:this.form.tumblr,youtube:this.form.youtube}).then((function(r){t.errors=[],t.$toasted.show(t.$t("t_toasted_footer_settings_updated"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1})).catch((function(r){r.response.data.errors&&(t.errors=r.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1}))):this.$router.push("/administrator")}}},c=e(52),component=Object(c.a)(n,(function(){var t=this,r=t.$createElement,e=t._self._c||r;return e("v-app",{attrs:{id:"inspire"}},[e("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(r){t.loading=r},expression:"loading"}},[e("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),e("v-container",[e("v-card",{staticClass:"m-card",attrs:{flat:""}},[e("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[e("div",[e("div",{staticClass:"title"},[t._v(t._s(t.$t("t_footer_settings")))]),t._v(" "),e("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_footer_settings_para")))])])]),t._v(" "),e("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[e("v-layout",{attrs:{wrap:""}},[e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_footer_copyrights"),placeholder:t.$t("t_enter_footer_copyrights"),rules:t.errors.copyrights,error:!!t.errors.copyrights},model:{value:t.form.copyrights,callback:function(r){t.$set(t.form,"copyrights",r)},expression:"form.copyrights"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-select",{attrs:{items:t.pages,"item-text":"title","item-value":"slug",color:"grey lighten-1",label:t.$t("t_terms_of_use_page"),placeholder:t.$t("t_choose_terms_of_use_page"),rules:t.errors.terms,error:!!t.errors.terms,dense:""},model:{value:t.form.terms,callback:function(r){t.$set(t.form,"terms",r)},expression:"form.terms"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-select",{attrs:{items:t.pages,"item-text":"title","item-value":"slug",color:"grey lighten-1",label:t.$t("t_privacy_policy_page"),placeholder:t.$t("t_choose_privacy_policy_page"),rules:t.errors.privacy,error:!!t.errors.privacy,dense:""},model:{value:t.form.privacy,callback:function(r){t.$set(t.form,"privacy",r)},expression:"form.privacy"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_first_footer_column"),placeholder:t.$t("t_enter_first_footer_column"),rules:t.errors.firstColumn,error:!!t.errors.firstColumn},model:{value:t.form.firstColumn,callback:function(r){t.$set(t.form,"firstColumn",r)},expression:"form.firstColumn"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_second_footer_column"),placeholder:t.$t("t_enter_second_footer_column"),rules:t.errors.secondColumn,error:!!t.errors.secondColumn},model:{value:t.form.secondColumn,callback:function(r){t.$set(t.form,"secondColumn",r)},expression:"form.secondColumn"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_third_footer_column"),placeholder:t.$t("t_enter_third_footer_column"),rules:t.errors.thirdColumn,error:!!t.errors.thirdColumn},model:{value:t.form.thirdColumn,callback:function(r){t.$set(t.form,"thirdColumn",r)},expression:"form.thirdColumn"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_fourth_footer_column"),placeholder:t.$t("t_enter_fourth_footer_column"),rules:t.errors.fourthColumn,error:!!t.errors.fourthColumn},model:{value:t.form.fourthColumn,callback:function(r){t.$set(t.form,"fourthColumn",r)},expression:"form.fourthColumn"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_mailchimp_api"),placeholder:t.$t("t_enter_mailchimp_api"),rules:t.errors.mailchimp_key,error:!!t.errors.mailchimp_key},model:{value:t.form.mailchimp_key,callback:function(r){t.$set(t.form,"mailchimp_key",r)},expression:"form.mailchimp_key"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_mailchimp_list_id"),placeholder:t.$t("t_enter_mailchimp_list_id"),rules:t.errors.mailchimp_id,error:!!t.errors.mailchimp_id},model:{value:t.form.mailchimp_id,callback:function(r){t.$set(t.form,"mailchimp_id",r)},expression:"form.mailchimp_id"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_facebook"),placeholder:t.$t("t_your_facebook_page"),rules:t.errors.facebook,error:!!t.errors.facebook},model:{value:t.form.facebook,callback:function(r){t.$set(t.form,"facebook",r)},expression:"form.facebook"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_twitter"),placeholder:t.$t("t_your_twitter_page"),rules:t.errors.twitter,error:!!t.errors.twitter},model:{value:t.form.twitter,callback:function(r){t.$set(t.form,"twitter",r)},expression:"form.twitter"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_google"),placeholder:t.$t("t_your_google_page"),rules:t.errors.google,error:!!t.errors.google},model:{value:t.form.google,callback:function(r){t.$set(t.form,"google",r)},expression:"form.google"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_instagram"),placeholder:t.$t("t_your_instagram_page"),rules:t.errors.instagram,error:!!t.errors.instagram},model:{value:t.form.instagram,callback:function(r){t.$set(t.form,"instagram",r)},expression:"form.instagram"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_linkedin"),placeholder:t.$t("t_your_linkedin_page"),rules:t.errors.linkedin,error:!!t.errors.linkedin},model:{value:t.form.linkedin,callback:function(r){t.$set(t.form,"linkedin",r)},expression:"form.linkedin"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_tumblr"),placeholder:t.$t("t_your_tumblr_page"),rules:t.errors.tumblr,error:!!t.errors.tumblr},model:{value:t.form.tumblr,callback:function(r){t.$set(t.form,"tumblr",r)},expression:"form.tumblr"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_vk"),placeholder:t.$t("t_your_vk_page"),rules:t.errors.vk,error:!!t.errors.vk},model:{value:t.form.vk,callback:function(r){t.$set(t.form,"vk",r)},expression:"form.vk"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_youtube"),placeholder:t.$t("t_your_youtube_channel"),rules:t.errors.youtube,error:!!t.errors.youtube},model:{value:t.form.youtube,callback:function(r){t.$set(t.form,"youtube",r)},expression:"form.youtube"}})],1),t._v(" "),e("v-flex",{attrs:{xs12:""}},[e("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(r){return r.preventDefault(),t.update(r)}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);r.default=component.exports}}]);