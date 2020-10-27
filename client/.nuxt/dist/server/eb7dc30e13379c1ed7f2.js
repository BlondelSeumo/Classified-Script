exports.ids=[105],exports.modules={184:function(t,e,r){"use strict";r.r(e);var o={layout:"default/admin",middleware:"administrator",async asyncData({app:t,$axios:e,redirect:r}){t.$owgate.allow(t.$auth.user,"access","support")||r("/administrator");let o=await e.get("administrator/support/settings");return{form:{code:o.data.purchaseCode,username:o.data.envatoUsername}}},data:function(){return{errors:[],loading:!1}},head(){return{title:this.$t("t_support_settings"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){this.$owgate.allow(this.$auth.user,"access","support")?(this.loading=!0,this.$axios.post("administrator/support/settings",{code:this.form.code,username:this.form.username}).then(t=>{this.errors=[],this.$toasted.show(this.$t("t_toasted_support_settings_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")}}},n=r(1),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_support_settings")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_support_settings_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_purchase_code"),placeholder:t.$t("t_enter_purchase_code"),rules:t.errors.code,error:!!t.errors.code},model:{value:t.form.code,callback:function(e){t.$set(t.form,"code",e)},expression:"form.code"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_envato_username"),placeholder:t.$t("t_enter_envato_username"),rules:t.errors.username,error:!!t.errors.username},model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.update(e)}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"3d39b809");e.default=component.exports}};