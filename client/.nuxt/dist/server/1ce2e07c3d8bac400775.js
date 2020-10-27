exports.ids=[38],exports.modules={232:function(t,e,r){"use strict";r.r(e);var d={layout:"default/admin",middleware:"administrator",async asyncData({app:t,$axios:e,redirect:r}){t.$owgate.allow(t.$auth.user,"access","advertisements")||r("/administrator");let d=await e.get("administrator/advertisements");return{form:{ad_deal_sidebar:d.data.ad_deal_sidebar,ad_deal_top_related:d.data.ad_deal_top_related,ad_deal_bottom_related:d.data.ad_deal_bottom_related}}},data:function(){return{errors:[],loading:!1}},head(){return{title:this.$t("t_advertisements"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){this.$owgate.allow(this.$auth.user,"access","advertisements")?(this.loading=!0,this.$axios.post("administrator/advertisements",{ad_deal_sidebar:this.form.ad_deal_sidebar,ad_deal_top_related:this.form.ad_deal_top_related,ad_deal_bottom_related:this.form.ad_deal_bottom_related}).then(t=>{this.errors=[],this.$toasted.show(this.$t("t_toasted_advertisements_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")}}},o=r(1),component=Object(o.a)(d,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_advertisements")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_adsense_paragraph")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-textarea",{attrs:{color:"grey lighten-1",label:t.$t("t_deal_sidebar_ad"),placeholder:this.$t("t_put_ad_code"),"no-resize":"",rules:t.errors.ad_deal_sidebar,error:!!t.errors.ad_deal_sidebar},model:{value:t.form.ad_deal_sidebar,callback:function(e){t.$set(t.form,"ad_deal_sidebar",e)},expression:"form.ad_deal_sidebar"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-textarea",{attrs:{color:"grey lighten-1",label:t.$t("t_related_deals_top_ad"),placeholder:this.$t("t_put_ad_code"),"no-resize":"",rules:t.errors.ad_deal_top_related,error:!!t.errors.ad_deal_top_related},model:{value:t.form.ad_deal_top_related,callback:function(e){t.$set(t.form,"ad_deal_top_related",e)},expression:"form.ad_deal_top_related"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-textarea",{attrs:{color:"grey lighten-1",label:t.$t("t_related_deals_bottom_ad"),placeholder:this.$t("t_put_ad_code"),"no-resize":"",rules:t.errors.ad_deal_bottom_related,error:!!t.errors.ad_deal_bottom_related},model:{value:t.form.ad_deal_bottom_related,callback:function(e){t.$set(t.form,"ad_deal_bottom_related",e)},expression:"form.ad_deal_bottom_related"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.update(e)}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"72f11c36");e.default=component.exports}};