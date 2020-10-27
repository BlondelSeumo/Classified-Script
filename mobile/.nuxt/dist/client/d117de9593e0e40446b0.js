(window.webpackJsonp=window.webpackJsonp||[]).push([[86],{685:function(e,t,r){"use strict";r.r(t);r(84);var o={layout:"default/admin",middleware:"administrator",asyncData:function(e){var t=e.app,r=e.redirect;t.$owgate.allow(t.$auth.user,"create","roles")||r("/administrator")},data:function(){return{form:{name:"",write_comments:!1,edit_comments:!1,delete_comments:!1,send_messages:!1,receive_messages:!1,delete_messages:!1,report_classifieds:!1,report_comments:!1,make_offers:!1,receive_offers:!1,save_search:!1,follow_stores:!1,contact_stores:!1,see_advertisements:!1,see_classifieds_stats:!1},errors:[],loading:!1}},head:function(){return{title:this.$t("t_create_member_role"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{create:function(){var e=this;this.$owgate.allow(this.$auth.user,"create","roles")?(this.loading=!0,this.$axios.post("administrator/roles/options/create/member",{name:this.form.name,write_comments:this.form.write_comments,edit_comments:this.form.edit_comments,delete_comments:this.form.delete_comments,send_messages:this.form.send_messages,receive_messages:this.form.receive_messages,delete_messages:this.form.delete_messages,report_classifieds:this.form.report_classifieds,report_comments:this.form.report_comments,make_offers:this.form.make_offers,receive_offers:this.form.receive_offers,save_search:this.form.save_search,follow_stores:this.form.follow_stores,contact_stores:this.form.contact_stores,see_advertisements:this.form.see_advertisements,see_classifieds_stats:this.form.see_classifieds_stats}).then((function(t){e.errors=[],e.form={name:"",write_comments:!1,edit_comments:!1,delete_comments:!1,send_messages:!1,receive_messages:!1,delete_messages:!1,report_classifieds:!1,report_comments:!1,make_offers:!1,receive_offers:!1,save_search:!1,follow_stores:!1,contact_stores:!1,see_advertisements:!1,see_classifieds_stats:!1},e.$toasted.show(e.$t("t_toasted_member_role_created"),{icon:"done_all",className:e.$vuetify.rtl?"toasted-rtl":""}),e.loading=!1})).catch((function(t){e.errors=t.response.data.errors,e.$toasted.error(e.$t("t_toasted_something_went_wrong"),{icon:"error",className:e.$vuetify.rtl?"toasted-rtl":""}),e.loading=!1}))):this.$router.push("/administrator")}}},l=r(52),component=Object(l.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[e._v("\n\t\t\t"+e._s(e.$t("t_loading"))+"\n\t\t")])],1),e._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[e._v(e._s(e.$t("t_create_member_role")))]),e._v(" "),r("span",{staticClass:"card-description"},[e._v(e._s(e.$t("t_create_member_role_para")))])])]),e._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_role_name"),placeholder:e.$t("t_enter_role_name"),counter:"60",maxlength:"60",rules:e.errors.name,error:!!e.errors.name},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.write_comments?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.write_comments,callback:function(t){e.$set(e.form,"write_comments",t)},expression:"form.write_comments"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_write_comments")))]),e._v(" "),e.errors.write_comments?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.write_comments[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.edit_comments?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{disabled:!e.form.write_comments,color:"blue"},model:{value:e.form.edit_comments,callback:function(t){e.$set(e.form,"edit_comments",t)},expression:"form.edit_comments"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_edit_comments")))]),e._v(" "),e.errors.edit_comments?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.edit_comments[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.delete_comments?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{disabled:!e.form.write_comments,color:"blue"},model:{value:e.form.delete_comments,callback:function(t){e.$set(e.form,"delete_comments",t)},expression:"form.delete_comments"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_delete_comments")))]),e._v(" "),e.errors.delete_comments?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.delete_comments[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.send_messages?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.send_messages,callback:function(t){e.$set(e.form,"send_messages",t)},expression:"form.send_messages"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_send_messages")))]),e._v(" "),e.errors.send_messages?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.send_messages[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.receive_messages?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{disabled:0==e.form.send_messages&&0==e.form.receive_messages,color:"blue"},model:{value:e.form.receive_messages,callback:function(t){e.$set(e.form,"receive_messages",t)},expression:"form.receive_messages"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_receive_messages")))]),e._v(" "),e.errors.receive_messages?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.receive_messages[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.delete_messages?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{disabled:0==e.form.send_messages&&0==e.form.receive_messages,color:"blue"},model:{value:e.form.delete_messages,callback:function(t){e.$set(e.form,"delete_messages",t)},expression:"form.delete_messages"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_delete_messages")))]),e._v(" "),e.errors.delete_messages?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.delete_messages[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.report_classifieds?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.report_classifieds,callback:function(t){e.$set(e.form,"report_classifieds",t)},expression:"form.report_classifieds"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_report_deals")))]),e._v(" "),e.errors.report_classifieds?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.report_classifieds[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.report_comments?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.report_comments,callback:function(t){e.$set(e.form,"report_comments",t)},expression:"form.report_comments"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_report_comments")))]),e._v(" "),e.errors.report_comments?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.report_comments[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.make_offers?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.make_offers,callback:function(t){e.$set(e.form,"make_offers",t)},expression:"form.make_offers"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_make_offers")))]),e._v(" "),e.errors.make_offers?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.make_offers[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.receive_offers?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.receive_offers,callback:function(t){e.$set(e.form,"receive_offers",t)},expression:"form.receive_offers"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_receive_offers")))]),e._v(" "),e.errors.receive_offers?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.receive_offers[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.save_search?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.save_search,callback:function(t){e.$set(e.form,"save_search",t)},expression:"form.save_search"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_save_search")))]),e._v(" "),e.errors.save_search?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.save_search[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.follow_stores?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.follow_stores,callback:function(t){e.$set(e.form,"follow_stores",t)},expression:"form.follow_stores"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_follow_shops")))]),e._v(" "),e.errors.follow_stores?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.follow_stores[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.contact_stores?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.contact_stores,callback:function(t){e.$set(e.form,"contact_stores",t)},expression:"form.contact_stores"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_contact_shops")))]),e._v(" "),e.errors.contact_stores?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.contact_stores[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.see_advertisements?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.see_advertisements,callback:function(t){e.$set(e.form,"see_advertisements",t)},expression:"form.see_advertisements"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_see_advertisements")))]),e._v(" "),e.errors.see_advertisements?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.see_advertisements[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[e.errors.see_classifieds_stats?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:e.form.see_classifieds_stats,callback:function(t){e.$set(e.form,"see_classifieds_stats",t)},expression:"form.see_classifieds_stats"}}),e._v(" "),r("span",[e._v(e._s(e.$t("t_role_deals_stats")))]),e._v(" "),e.errors.see_classifieds_stats?r("small",{staticClass:"form-text"},[e._v(e._s(e.errors.see_classifieds_stats[0]))]):e._e()],1)]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:e.loading,loading:e.loading,block:"",depressed:"",color:"blue"},on:{click:function(t){return t.preventDefault(),e.create(t)}}},[e._v(e._s(e.$t("t_create")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);t.default=component.exports}}]);