(window.webpackJsonp=window.webpackJsonp||[]).push([[120],{626:function(t,e,r){"use strict";r.r(e);var o={data:function(){return{form:{code:null,username:null},errors:[],item:1,items:[{text:"t_welcome",icon:"mdi-emoticon-happy-outline"},{text:"t_verify_purchase",icon:"mdi-barcode-scan"},{text:"t_server_requiremnts",icon:"mdi-server"},{text:"t_database_config",icon:"mdi-database"},{text:"t_website_config",icon:"mdi-sitemap"},{text:"t_setup_account",icon:"mdi-account"},{text:"t_finish",icon:"mdi-timer"}],loading:!1}},head:function(){return{title:this.$t("t_verify_purchase"),titleTemplate:"%s - ".concat(this.$t("t_installation")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$homeApi("storage/app/uploads/settings/favicon/favicon.png")}]}},methods:{verify:function(){var t=this;this.form.code&&this.form.username&&(this.loading=!0,this.$axios.post("installation/verify",{code:this.form.code,username:this.form.username}).then((function(e){t.$toasted.show(t.$t("t_toasted_purchase_code_added"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1,t.$router.push("/installation/requirements")})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1})))}}},n=r(52),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",{staticClass:"px-12"},[r("v-container",{staticClass:"fill-height",attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs3:""}},[r("v-card",{staticClass:"mx-auto m-card px-3 pb-3"},[r("v-list",{attrs:{dense:"",rounded:""}},[r("v-subheader",[t._v(t._s(t.$t("t_installation")))]),t._v(" "),r("v-list-item-group",{attrs:{color:"success"},model:{value:t.item,callback:function(e){t.item=e},expression:"item"}},t._l(t.items,(function(e,i){return r("v-list-item",{key:i,on:{click:function(t){e=e}}},[r("v-list-item-icon",[r("v-icon",{staticClass:"mt-1",domProps:{textContent:t._s(e.icon)}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.text,expression:"item.text"}]})],1),t._v(" "),r("v-list-item-action",[0===i?r("v-icon",{attrs:{color:"success"}},[t._v("mdi-checkbox-marked-outline")]):r("v-icon",{attrs:{color:"grey"}},[t._v("mdi-checkbox-blank-outline")])],1)],1)})),1)],1)],1)],1),t._v(" "),r("v-flex",{attrs:{xs9:""}},[r("v-card",{staticClass:"m-card pa-4"},[r("v-toolbar",{attrs:{color:"white",dense:"",flat:""}},[r("v-toolbar-title",{staticClass:"body-2"},[t._v(t._s(t.$t("t_verify_purchase")))])],1),t._v(" "),r("v-card-text",[r("v-container",{staticClass:"pa-0",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_purchase_code"),placeholder:t.$t("t_enter_purchase_code"),rules:t.errors.code,error:!!t.errors.code},model:{value:t.form.code,callback:function(e){t.$set(t.form,"code",e)},expression:"form.code"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_envato_username"),placeholder:t.$t("t_enter_envato_username"),rules:t.errors.username,error:!!t.errors.username},model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1)],1)],1)],1),t._v(" "),r("v-card-actions",{staticClass:"follow-btn px-4"},[r("v-spacer"),t._v(" "),r("v-btn",{staticClass:"px-5",attrs:{text:"",color:"primary",depressed:"",disabled:!t.form.code||!t.form.username},on:{click:t.verify}},[t._v("\n                                    "+t._s(t.$t("t_next_step"))+"\n                                ")])],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);