(window.webpackJsonp=window.webpackJsonp||[]).push([[93],{728:function(t,e,r){"use strict";r.r(e);r(47);var o,n=r(27),l={layout:"default/admin",middleware:"administrator",asyncData:(o=Object(n.a)(regeneratorRuntime.mark((function t(e){var r,o,n,l;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.app,o=e.$axios,n=e.redirect,r.$owgate.allow(r.$auth.user,"sms","services")||n("/administrator"),t.next=4,o.get("administrator/services/sms/nexmo");case 4:return l=t.sent,t.abrupt("return",{form:{key:l.data.key,secret:l.data.secret,from:l.data.from}});case 6:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{errors:[],loading:!1}},head:function(){return{title:this.$t("t_nexmo_gateway"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){var t=this;this.$owgate.allow(this.$auth.user,"sms","services")?(this.loading=!0,this.$axios.post("administrator/services/sms/nexmo",{key:this.form.key,secret:this.form.secret,from:this.form.from}).then((function(e){t.errors=[],t.$toasted.show(t.$t("t_toasted_nexmo_updated"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1}))):this.$router.push("/administrator")}}},c=r(52),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_nexmo")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_nexmo_gateway_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_nexmo_key"),placeholder:t.$t("t_enter_nexmo_key"),rules:t.errors.key,error:!!t.errors.key},model:{value:t.form.key,callback:function(e){t.$set(t.form,"key",e)},expression:"form.key"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_nexmo_secret"),placeholder:t.$t("t_enter_nexmo_secret"),rules:t.errors.secret,error:!!t.errors.secret},model:{value:t.form.secret,callback:function(e){t.$set(t.form,"secret",e)},expression:"form.secret"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_nexmo_sms_from"),placeholder:t.$t("t_enter_nexmo_sms_from"),rules:t.errors.from,error:!!t.errors.from},model:{value:t.form.from,callback:function(e){t.$set(t.form,"from",e)},expression:"form.from"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.update(e)}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);