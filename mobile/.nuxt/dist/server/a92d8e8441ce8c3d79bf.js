exports.ids=[93],exports.modules={199:function(t,e,r){"use strict";r.r(e);var o={layout:"default/admin",middleware:"administrator",asyncData:async({app:t,$axios:e,redirect:r})=>(t.$owgate.allow(t.$auth.user,"payments","settings")||r("/administrator"),{form:{paypal:(await e.get("administrator/settings/payments")).data.isPaypal}}),data:function(){return{errors:[],loading:!1}},head(){return{title:this.$t("t_payment_gateways_settings"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){this.$owgate.allow(this.$auth.user,"payments","settings")?(this.loading=!0,this.$axios.post("administrator/settings/payments",{paypal:this.form.paypal}).then(t=>{this.errors=[],this.$toasted.show(this.$t("t_toasted_payments_settings_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")}}},n=r(1),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_payment_gateways_settings")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_payment_gateways_settings_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[t.errors.paypal?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:t.form.paypal,callback:function(e){t.$set(t.form,"paypal",e)},expression:"form.paypal"}}),t._v(" "),r("span",[t._v(t._s(t.$t("t_enable_paypal")))])],1),t._v(" "),t.errors.paypal?r("small",{staticClass:"red--text"},[t._v(t._s(t.errors.paypal[0]))]):t._e()]),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.update(e)}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"44d3518b");e.default=component.exports}};