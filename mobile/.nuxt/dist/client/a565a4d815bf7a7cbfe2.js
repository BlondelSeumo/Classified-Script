(window.webpackJsonp=window.webpackJsonp||[]).push([[58],{716:function(t,e,r){"use strict";r.r(e);r(60),r(57),r(94),r(84);var o,n=r(99),c=(r(45),r(26)),l={layout:"default/admin",middleware:"administrator",asyncData:(o=Object(c.a)(regeneratorRuntime.mark((function t(e){var r,o,n,c;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.app,o=e.$axios,n=e.redirect,r.$owgate.allow(r.$auth.user,"access","currencies")||n("/administrator"),t.next=4,o.get("administrator/currencies");case 4:return c=t.sent,t.abrupt("return",{currenciesData:c.data,currencies:c.data.data});case 6:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{selected:[],headers:[{text:this.$t("t_currency_name"),value:"name",sortable:!1,align:"center"},{text:this.$t("t_currency_code"),value:"code",sortable:!1,align:"center"},{text:this.$t("t_currency_locale"),value:"locale",sortable:!1,align:"center"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}],forms:{create:{name:"",code:"",locale:""},edit:{index:"",id:"",name:"",code:"",locale:""}},dialogs:{delete:!1,create:!1,edit:!1},config:[],locales:[],errors:[],loading:!1}},head:function(){return{title:this.$t("t_currencies"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{getNextPage:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,this.$axios.get("administrator/currencies?page="+e).then((function(e){t.selected=[],t.currenciesData=e.data,t.currencies=e.data.data,void 0!==("undefined"==typeof window?"undefined":Object(n.a)(window))&&window.scrollTo(0,0),t.loading=!1}))},create:function(){0!==this.config.length&&0!==this.locales.length||this.fetchSettings(),this.dialogs.create=!0},insert:function(){var t=this;this.$owgate.allow(this.$auth.user,"create","currencies")?(this.loading=!0,this.$axios.post("administrator/currencies/options/create",{name:this.forms.create.name,code:this.forms.create.code,locale:this.forms.create.locale}).then((function(e){t.currencies.push(e.data),t.errors=[],t.forms.create={name:"",code:"",locale:""},t.$toasted.show(t.$t("t_toasted_currency_created"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.dialogs.create=!1,t.loading=!1})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1}))):this.$router.push("/administrator")},edit:function(t,e){0!==this.config.length&&0!==this.locales.length||this.fetchSettings(),this.forms.edit.index=e,this.forms.edit.id=t.id,this.forms.edit.name=t.name,this.forms.edit.code=t.code,this.forms.edit.locale=t.locale,this.dialogs.edit=!0},update:function(){var t=this;this.$owgate.allow(this.$auth.user,"edit","currencies")?(this.loading=!0,this.$axios.post("administrator/currencies/options/edit",{id:this.forms.edit.id,name:this.forms.edit.name,code:this.forms.edit.code,locale:this.forms.edit.locale}).then((function(e){t.currencies[t.forms.edit.index].name=e.data.name,t.currencies[t.forms.edit.index].code=e.data.code,t.currencies[t.forms.edit.index].locale=e.data.locale,t.errors=[],t.forms.edit={name:"",code:"",locale:"",id:"",index:""},t.$toasted.show(t.$t("t_toasted_currency_updated"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),t.dialogs.edit=!1,t.loading=!1})).catch((function(e){e.response.data.errors&&(t.errors=e.response.data.errors),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1}))):this.$router.push("/administrator")},remove:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$owgate.allow(this.$auth.user,"delete","currencies")){var o=this;o.loading=!0,this.$axios.post("administrator/currencies/options/delete",{currencies:e}).then((function(e){null!==r?t.currencies[r].deleted_at=!0:o.selected.forEach((function(t,e){o.currencies.forEach((function(e,r){t.id===e.id&&(o.currencies[r].deleted_at=!0)}))})),o.$toasted.show(t.$t("t_toasted_currencies_deleted"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),o.dialogs.delete=!1,o.loading=!1})).catch((function(e){console.log(e),o.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),o.loading=!1}))}else this.$router.push("/administrator")},restore:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$owgate.allow(this.$auth.user,"delete","currencies")){var o=this;o.loading=!0,this.$axios.post("administrator/currencies/options/restore",{currencies:e}).then((function(e){null!==r?t.currencies[r].deleted_at=null:o.selected.forEach((function(t,e){o.currencies.forEach((function(e,r){t.id===e.id&&(o.currencies[r].deleted_at=null)}))})),o.$toasted.show(t.$t("t_toasted_currencies_restored"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),o.loading=!1})).catch((function(e){console.log(e),o.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),o.loading=!1}))}else this.$router.push("/administrator")},fetchSettings:function(){var t=this;this.loading=!0,this.$axios.get("administrator/currencies/options/fetch").then((function(e){var r=t;Object.keys(e.data.config).map((function(t){3===t.length&&r.config.push(t)})),t.locales=e.data.locales,t.loading=!1})).catch((function(e){console.log(e),t.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),t.loading=!1}))}}},d=r(52),component=Object(d.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),t.$owgate.allow(t.$auth.user,"delete","currencies")?r("v-dialog",{attrs:{"max-width":"400",persistent:""},model:{value:t.dialogs.delete,callback:function(e){t.$set(t.dialogs,"delete",e)},expression:"dialogs.delete"}},[r("v-card",{staticClass:"py-2"},[r("v-card-text",[r("div",{staticClass:"text-center mb-5"},[r("v-icon",{attrs:{size:"100px",color:"error"}},[t._v("mdi-alert-octagon-outline")])],1),t._v(" "),r("div",{staticClass:"mb-4 text-center headline font-weight-black text-uppercase"},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_are_you_sure"))+"\n\t\t\t\t\t")])]),t._v(" "),r("v-card-actions",[r("v-spacer"),t._v(" "),r("v-btn",{attrs:{color:"grey darken-1",text:""},on:{click:function(e){t.dialogs.delete=!1}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_cancel"))+"\n\t\t\t\t\t")]),t._v(" "),r("v-btn",{attrs:{color:"error",text:""},on:{click:function(e){return t.remove()}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.selected.length>1?t.$t("t_delete_currencies"):t.$t("t_delete_currency"))+"\n\t\t\t\t\t")])],1)],1)],1):t._e(),t._v(" "),t.$owgate.allow(t.$auth.user,"create","currencies")?r("v-dialog",{attrs:{"max-width":"500px",persistent:""},model:{value:t.dialogs.create,callback:function(e){t.$set(t.dialogs,"create",e)},expression:"dialogs.create"}},[r("v-card",{staticClass:"pa-2"},[r("v-card-title",{staticClass:"pt-1",attrs:{"primary-title":""}},[r("h3",{staticClass:"body-2 mb-0 text-uppercase font-weight-black"},[t._v(t._s(t.$t("t_create_currency")))]),t._v(" "),r("v-spacer"),t._v(" "),r("v-btn",{attrs:{icon:""},on:{click:function(e){t.dialogs.create=!1}}},[r("v-icon",[t._v("mdi-close")])],1)],1),t._v(" "),r("v-card-text",[r("v-container",{staticClass:"pa-0",attrs:{"grid-list-md":""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_currency_name"),placeholder:t.$t("t_enter_currency_name"),counter:"60",maxlength:"60",rules:t.errors.name,error:!!t.errors.name},model:{value:t.forms.create.name,callback:function(e){t.$set(t.forms.create,"name",e)},expression:"forms.create.name"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.config,color:"grey lighten-1",label:t.$t("t_currency_code"),placeholder:t.$t("t_enter_currency_code"),rules:t.errors.code,error:!!t.errors.code,dense:""},model:{value:t.forms.create.code,callback:function(e){t.$set(t.forms.create,"code",e)},expression:"forms.create.code"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.locales,color:"grey lighten-1",label:t.$t("t_currency_locale"),placeholder:t.$t("t_choose_currency_locale"),rules:t.errors.locale,error:!!t.errors.locale,dense:""},model:{value:t.forms.create.locale,callback:function(e){t.$set(t.forms.create,"locale",e)},expression:"forms.create.locale"}})],1)],1)],1)],1),t._v(" "),r("v-card-actions",{staticClass:"pa-2"},[r("v-spacer"),t._v(" "),r("v-btn",{attrs:{text:""},on:{click:function(e){return t.insert()}}},[t._v(t._s(t.$t("t_create")))])],1)],1)],1):t._e(),t._v(" "),t.$owgate.allow(t.$auth.user,"edit","currencies")?r("v-dialog",{attrs:{"max-width":"500px",persistent:""},model:{value:t.dialogs.edit,callback:function(e){t.$set(t.dialogs,"edit",e)},expression:"dialogs.edit"}},[r("v-card",{staticClass:"pa-2"},[r("v-card-title",{staticClass:"pt-1",attrs:{"primary-title":""}},[r("h3",{staticClass:"body-2 mb-0 text-uppercase font-weight-black"},[t._v(t._s(t.$t("t_edit_currency")))]),t._v(" "),r("v-spacer"),t._v(" "),r("v-btn",{attrs:{icon:""},on:{click:function(e){t.dialogs.edit=!1}}},[r("v-icon",[t._v("mdi-close")])],1)],1),t._v(" "),r("v-card-text",[r("v-container",{staticClass:"pa-0",attrs:{"grid-list-md":""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_currency_name"),placeholder:t.$t("t_enter_currency_name"),counter:"60",maxlength:"60",rules:t.errors.name,error:!!t.errors.name},model:{value:t.forms.edit.name,callback:function(e){t.$set(t.forms.edit,"name",e)},expression:"forms.edit.name"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.config,color:"grey lighten-1",label:t.$t("t_currency_code"),placeholder:t.$t("t_enter_currency_code"),rules:t.errors.code,error:!!t.errors.code,dense:""},model:{value:t.forms.edit.code,callback:function(e){t.$set(t.forms.edit,"code",e)},expression:"forms.edit.code"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",items:t.locales,color:"grey lighten-1",label:t.$t("t_currency_locale"),placeholder:t.$t("t_choose_currency_locale"),rules:t.errors.locale,error:!!t.errors.locale,dense:""},model:{value:t.forms.edit.locale,callback:function(e){t.$set(t.forms.edit,"locale",e)},expression:"forms.edit.locale"}})],1)],1)],1)],1),t._v(" "),r("v-card-actions",{staticClass:"pa-2"},[r("v-spacer"),t._v(" "),r("v-btn",{attrs:{text:""},on:{click:function(e){return t.update()}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1):t._e(),t._v(" "),r("v-container",[r("div",{staticClass:"m-card"},[r("v-toolbar",{attrs:{flat:""}},[r("v-toolbar-title",[t._v(t._s(t.$t("t_currencies")))]),t._v(" "),r("v-spacer"),t._v(" "),t.$owgate.allow(t.$auth.user,"delete","currencies")?r("v-fade-transition",[t.selected.length>0&&t.currencies.length>1?r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[r("v-btn",t._g({attrs:{text:"",icon:"",color:t.$vuetify.theme.dark?"white":"grey darken-3"},on:{click:function(e){t.dialogs.delete=!0}}},o),[r("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,228501886)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1):t._e(),t._v(" "),t.$owgate.allow(t.$auth.user,"delete","currencies")?r("v-fade-transition",[t.selected.length>0&&t.currencies.length>1?r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[r("v-btn",t._g({attrs:{text:"",icon:"",color:t.$vuetify.theme.dark?"white":"grey darken-3"},on:{click:function(e){return t.restore()}}},o),[r("v-icon",[t._v("mdi-delete-restore")])],1)]}}],null,!1,1586185791)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_restore")))])]):t._e()],1):t._e(),t._v(" "),t.$owgate.allow(t.$auth.user,"create","currencies")?r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[r("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){return t.create()}}},o),[r("v-icon",[t._v("add")])],1)]}}],null,!1,626366311)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_create")))])]):t._e()],1),t._v(" "),r("v-data-table",{attrs:{headers:t.headers,items:t.currencies,"item-key":"id","hide-default-footer":"",loading:t.loading,"show-select":"","disable-pagination":"","mobile-breakpoint":1,"no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.name",fn:function(e){var r=e.item;return[t._v(t._s(r.name))]}},{key:"item.created",fn:function(e){var r=e.item;return[t._v(t._s(r.code))]}},{key:"item.locale",fn:function(e){var r=e.item;return[t._v(t._s(r.locale))]}},{key:"item.status",fn:function(e){var o=e.item;return[o.deletet_at&&null!==o.deleted_at?t._e():r("v-btn",{attrs:{text:"",color:"#2ecc71"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t")]),t._v(" "),o.deleted_at&&null!==o.deleted_at?r("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t")]):t._e()]}},{key:"item.options",fn:function(e){var o=e.item;return[r("v-menu",{attrs:{bottom:"",left:!t.$vuetify.rtl,right:!!t.$vuetify.rtl,origin:"center center","max-height":"300px"},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[r("v-btn",t._g({attrs:{small:"",icon:""}},o),[r("v-icon",{attrs:{size:"18px",color:t.$vuetify.theme.dark?"white":"grey darken-1"}},[t._v("mdi-dots-vertical")])],1)]}}],null,!0)},[t._v(" "),r("v-list",{attrs:{dense:""}},[t.$owgate.allow(t.$auth.user,"edit","currencies")?r("v-list-item",{on:{click:function(e){t.edit(o,t.currencies.indexOf(o))}}},[r("v-list-item-action",[r("v-icon",[t._v("mdi-square-edit-outline")])],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",[t._v(t._s(t.$t("t_edit")))])],1)],1):t._e(),t._v(" "),null===o.deleted_at&&t.currencies.length>1&&t.$owgate.allow(t.$auth.user,"delete","currencies")?r("v-list-item",{on:{click:function(e){t.remove([o],t.currencies.indexOf(o))}}},[r("v-list-item-action",[r("v-icon",[t._v("mdi-delete")])],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",[t._v(t._s(t.$t("t_delete")))])],1)],1):t._e(),t._v(" "),null!==o.deleted_at&&t.$owgate.allow(t.$auth.user,"delete","currencies")?r("v-list-item",{on:{click:function(e){t.restore([o],t.currencies.indexOf(o))}}},[r("v-list-item-action",[r("v-icon",[t._v("mdi-delete-restore")])],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",[t._v(t._s(t.$t("t_restore")))])],1)],1):t._e()],1)],1)]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),r("div",{staticClass:"text-center pt-2"},[r("pagination",{attrs:{data:t.currenciesData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[r("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),r("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,null);e.default=component.exports}}]);