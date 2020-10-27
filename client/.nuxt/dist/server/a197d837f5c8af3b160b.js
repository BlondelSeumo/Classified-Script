exports.ids=[111],exports.modules={119:function(t,e,r){"use strict";r.r(e);var o={data:()=>({form:{host:null,database:null,username:null,password:null,port:3306},errors:[],item:3,items:[{text:"t_welcome",icon:"mdi-emoticon-happy-outline"},{text:"t_verify_purchase",icon:"mdi-barcode-scan"},{text:"t_server_requiremnts",icon:"mdi-server"},{text:"t_database_config",icon:"mdi-database"},{text:"t_website_config",icon:"mdi-sitemap"},{text:"t_setup_account",icon:"mdi-account"},{text:"t_finish",icon:"mdi-timer"}],visible:!1,loading:!1}),head(){return{title:this.$t("t_database_config"),titleTemplate:`%s - ${this.$t("t_installation")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$homeApi("storage/app/uploads/settings/favicon/favicon.png")}]}},methods:{database(){this.form.host&&this.form.database&&this.form.username&&this.form.port&&(this.loading=!0,this.$axios.post("installation/database",{host:this.form.host,database:this.form.database,username:this.form.username,password:this.form.password,port:this.form.port}).then(t=>{this.$toasted.show(this.$t("t_toasted_database_connected"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1,this.$router.push("/installation/website")}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}))}}},l=r(1),component=Object(l.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",{staticClass:"px-12"},[r("v-container",{staticClass:"fill-height",attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs3:""}},[r("v-card",{staticClass:"mx-auto m-card px-3 pb-3"},[r("v-list",{attrs:{dense:"",rounded:""}},[r("v-subheader",[t._v(t._s(t.$t("t_installation")))]),t._v(" "),r("v-list-item-group",{attrs:{color:"success"},model:{value:t.item,callback:function(e){t.item=e},expression:"item"}},t._l(t.items,(function(e,i){return r("v-list-item",{key:i,on:{click:function(t){e=e}}},[r("v-list-item-icon",[r("v-icon",{staticClass:"mt-1",domProps:{textContent:t._s(e.icon)}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.text,expression:"item.text"}]})],1),t._v(" "),r("v-list-item-action",[0===i||1===i||2===i?r("v-icon",{attrs:{color:"success"}},[t._v("mdi-checkbox-marked-outline")]):r("v-icon",{attrs:{color:"grey"}},[t._v("mdi-checkbox-blank-outline")])],1)],1)})),1)],1)],1)],1),t._v(" "),r("v-flex",{attrs:{xs9:""}},[r("v-card",{staticClass:"m-card pa-4"},[r("v-toolbar",{attrs:{color:"white",dense:"",flat:""}},[r("v-toolbar-title",{staticClass:"body-2"},[t._v(t._s(t.$t("t_database_config")))])],1),t._v(" "),r("v-card-text",[r("v-container",{staticClass:"pa-0",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_hostname"),placeholder:t.$t("t_enter_hostname"),rules:t.errors.host,error:!!t.errors.host},model:{value:t.form.host,callback:function(e){t.$set(t.form,"host",e)},expression:"form.host"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_database_name"),placeholder:t.$t("t_enter_database_name"),rules:t.errors.database,error:!!t.errors.database},model:{value:t.form.database,callback:function(e){t.$set(t.form,"database",e)},expression:"form.database"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_database_username"),placeholder:t.$t("t_enter_database_username"),rules:t.errors.username,error:!!t.errors.username},model:{value:t.form.username,callback:function(e){t.$set(t.form,"username",e)},expression:"form.username"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{"append-icon":t.visible?"visibility":"visibility_off",type:t.visible?"text":"password",color:"grey lighten-1",label:t.$t("t_database_password"),placeholder:t.$t("t_enter_database_password"),rules:t.errors.password,error:!!t.errors.password},on:{"click:append":function(e){t.visible=!t.visible}},model:{value:t.form.password,callback:function(e){t.$set(t.form,"password",e)},expression:"form.password"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_database_port"),placeholder:t.$t("t_enter_database_port"),rules:t.errors.port,error:!!t.errors.port},model:{value:t.form.port,callback:function(e){t.$set(t.form,"port",e)},expression:"form.port"}})],1)],1)],1)],1),t._v(" "),r("v-card-actions",{staticClass:"follow-btn px-4"},[r("v-spacer"),t._v(" "),r("v-btn",{staticClass:"px-5",attrs:{text:"",color:"primary",depressed:"",disabled:!(t.form.host&&t.form.database&&t.form.username&&t.form.port)},on:{click:t.database}},[t._v("\n                                    "+t._s(t.$t("t_next_step"))+"\n                                ")])],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"66ca3fa8");e.default=component.exports}};