exports.ids=[115],exports.modules={123:function(t,e,r){"use strict";r.r(e);var o={data(){return{form:{title:"EVEREST",tagline:"TOP PHP SCRIPTS",separator:"|",direction:null},errors:[],item:4,items:[{text:"t_welcome",icon:"mdi-emoticon-happy-outline"},{text:"t_verify_purchase",icon:"mdi-barcode-scan"},{text:"t_server_requiremnts",icon:"mdi-server"},{text:"t_database_config",icon:"mdi-database"},{text:"t_website_config",icon:"mdi-sitemap"},{text:"t_setup_account",icon:"mdi-account"},{text:"t_finish",icon:"mdi-timer"}],directions:[{id:1,name:this.$t("t_right_to_left")},{id:0,name:this.$t("t_left_to_right")}],loading:!1}},head(){return{title:this.$t("t_website_config"),titleTemplate:`%s - ${this.$t("t_installation")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$homeApi("storage/app/uploads/settings/favicon/favicon.png")}]}},methods:{website(){this.form.title&&this.form.tagline&&this.form.separator&&(this.loading=!0,this.$axios.post("installation/website",{title:this.form.title,tagline:this.form.tagline,separator:this.form.separator,direction:this.form.direction}).then(t=>{this.$toasted.show(this.$t("t_toasted_website_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1,this.$router.push("/installation/account")}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}))}}},l=r(1),component=Object(l.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",{staticClass:"px-12"},[r("v-container",{staticClass:"fill-height",attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs3:""}},[r("v-card",{staticClass:"mx-auto m-card px-3 pb-3"},[r("v-list",{attrs:{dense:"",rounded:""}},[r("v-subheader",[t._v(t._s(t.$t("t_installation")))]),t._v(" "),r("v-list-item-group",{attrs:{color:"success"},model:{value:t.item,callback:function(e){t.item=e},expression:"item"}},t._l(t.items,(function(e,i){return r("v-list-item",{key:i,on:{click:function(t){e=e}}},[r("v-list-item-icon",[r("v-icon",{staticClass:"mt-1",domProps:{textContent:t._s(e.icon)}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.text,expression:"item.text"}]})],1),t._v(" "),r("v-list-item-action",[0===i||1===i||2===i||3===i?r("v-icon",{attrs:{color:"success"}},[t._v("mdi-checkbox-marked-outline")]):r("v-icon",{attrs:{color:"grey"}},[t._v("mdi-checkbox-blank-outline")])],1)],1)})),1)],1)],1)],1),t._v(" "),r("v-flex",{attrs:{xs9:""}},[r("v-card",{staticClass:"m-card pa-4"},[r("v-toolbar",{attrs:{color:"white",dense:"",flat:""}},[r("v-toolbar-title",{staticClass:"body-2"},[t._v(t._s(t.$t("t_website_config")))])],1),t._v(" "),r("v-card-text",[r("v-container",{staticClass:"pa-0",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_site_title"),placeholder:t.$t("t_enter_site_title"),rules:t.errors.title,error:!!t.errors.title},model:{value:t.form.title,callback:function(e){t.$set(t.form,"title",e)},expression:"form.title"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_site_tagline"),placeholder:t.$t("t_enter_site_tagline"),rules:t.errors.tagline,error:!!t.errors.tagline},model:{value:t.form.tagline,callback:function(e){t.$set(t.form,"tagline",e)},expression:"form.tagline"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_title_separator"),placeholder:t.$t("t_enter_title_separator"),rules:t.errors.separator,error:!!t.errors.separator},model:{value:t.form.separator,callback:function(e){t.$set(t.form,"separator",e)},expression:"form.separator"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-select",{attrs:{items:t.directions,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_default_direction"),placeholder:t.$t("t_choose_default_direction"),rules:t.errors.direction,error:!!t.errors.direction,dense:""},model:{value:t.form.direction,callback:function(e){t.$set(t.form,"direction",e)},expression:"form.direction"}})],1)],1)],1)],1),t._v(" "),r("v-card-actions",{staticClass:"follow-btn px-4"},[r("v-spacer"),t._v(" "),r("v-btn",{staticClass:"px-5",attrs:{text:"",color:"primary",depressed:"",disabled:!t.form.title||!t.form.tagline||!t.form.separator},on:{click:t.website}},[t._v("\n                                    "+t._s(t.$t("t_next_step"))+"\n                                ")])],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"1a84e228");e.default=component.exports}};