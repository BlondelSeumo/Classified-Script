(window.webpackJsonp=window.webpackJsonp||[]).push([[29],{551:function(t,e,o){},575:function(t,e,o){"use strict";var n=o(551);o.n(n).a},702:function(t,e,o){"use strict";o.r(e);var n,r=o(99),l=(o(45),o(26)),d={layout:"default/admin",middleware:"administrator",asyncData:(n=Object(l.a)(regeneratorRuntime.mark((function t(e){var o,n,r,l;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return o=e.app,n=e.$axios,r=e.redirect,o.$adgate.allow(o.$auth.user,"access","shops")||r("/administrator"),t.next=4,n.get("administrator/shops");case 4:return l=t.sent,t.abrupt("return",{shopsData:l.data,shops:l.data.data});case 6:case"end":return t.stop()}}),t)}))),function(t){return n.apply(this,arguments)}),data:function(){return{selected:[],headers:[{text:this.$t("t_shop"),value:"shop",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_country"),value:"country",sortable:!1,align:"center"},{text:this.$t("t_created"),value:"created",sortable:!1,align:"center"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}],dialogs:{delete:!1,details:!1},store:{details:{shop:{},index:null}},loading:!1}},head:function(){return{title:this.$t("t_shops"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{getNextPage:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,this.$axios.get("administrator/shops?page="+e).then((function(e){t.selected=[],t.shopsData=e.data,t.shops=e.data.data,void 0!==("undefined"==typeof window?"undefined":Object(r.a)(window))&&window.scrollTo(0,0),t.loading=!1}))},details:function(t,e){this.store.details.shop=t,this.store.details.index=e,this.dialogs.details=!0},logo:function(t){return null===t?this.$homeApi("storage/app/uploads/default/store/no-logo.png"):this.$homeApi("storage/app/"+t)},cover:function(t){return null===t?this.$homeApi("storage/app/uploads/default/store/no-cover.png"):this.$homeApi("storage/app/"+t)},activate:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$adgate.allow(this.$auth.user,"approve","shops")){var n=this;n.loading=!0,this.$axios.post("administrator/shops/options/activate",{shops:e}).then((function(e){null!==o?(t.shops[o].isPending=!1,t.shops[o].isActive=!0,t.shops[o].isExpired=!1,t.shops[o].deleted_at=null):n.selected.forEach((function(t,e){n.shops.forEach((function(e,o){t.unique_id===e.unique_id&&(n.shops[o].isActive=!0,n.shops[o].isExpired=!1,n.shops[o].isPending=!1,n.shops[o].deleted_at=null)}))})),n.$toasted.show(t.$t("t_toasted_shops_activated"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),n.dialogs.delete=!1,n.loading=!1})).catch((function(e){console.log(e),n.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}))}else this.$router.push("/administrator")},remove:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$adgate.allow(this.$auth.user,"delete","shops")){var n=this;n.loading=!0,this.$axios.post("administrator/shops/options/delete",{shops:e}).then((function(e){null!==o?(t.shops[o].isPending=!1,t.shops[o].isActive=!1,t.shops[o].deleted_at=!0):n.selected.forEach((function(t,e){n.shops.forEach((function(e,o){t.unique_id===e.unique_id&&1!==e.user_id&&(n.shops[o].isActive=!1,n.shops[o].isPending=!1,n.shops[o].deleted_at=!0)}))})),n.$toasted.show(t.$t("t_toasted_shops_moved_to_trash"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),n.dialogs.delete=!1,n.loading=!1})).catch((function(e){console.log(e),n.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}))}else this.$router.push("/administrator")},restore:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$adgate.allow(this.$auth.user,"delete","shops")){var n=this;n.loading=!0,this.$axios.post("administrator/shops/options/restore",{shops:e}).then((function(e){null!==o?(t.shops[o].isPending=!1,t.shops[o].isActive=!0,t.shops[o].deleted_at=null):n.selected.forEach((function(t,e){n.shops.forEach((function(e,o){t.unique_id===e.unique_id&&(n.shops[o].isActive=!0,n.shops[o].isPending=!1,n.shops[o].deleted_at=null)}))})),n.$toasted.show(t.$t("t_toasted_shops_restored"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})).catch((function(e){console.log(e),n.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}))}else this.$router.push("/administrator")},avatar:function(t){return null===t?this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):this.$homeApi("storage/app/"+t)}}},c=(o(575),o(52)),component=Object(c.a)(d,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-app",{attrs:{id:"inspire"}},[o("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[o("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-dialog",{attrs:{"max-width":"400",persistent:""},model:{value:t.dialogs.delete,callback:function(e){t.$set(t.dialogs,"delete",e)},expression:"dialogs.delete"}},[o("v-card",{staticClass:"py-2"},[o("v-card-text",[o("div",{staticClass:"text-center mb-5"},[o("v-icon",{attrs:{size:"100px",color:"error"}},[t._v("mdi-alert-octagon-outline")])],1),t._v(" "),o("div",{staticClass:"mb-4 text-center headline font-weight-black text-uppercase"},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_are_you_sure"))+"\n\t\t\t\t\t")])]),t._v(" "),o("v-card-actions",[o("v-spacer"),t._v(" "),o("v-btn",{attrs:{color:"grey darken-1",text:""},on:{click:function(e){t.dialogs.delete=!1}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_cancel"))+"\n\t\t\t\t\t")]),t._v(" "),o("v-btn",{attrs:{color:"error",text:""},on:{click:function(e){return t.remove()}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.selected.length>1?this.$t("t_delete_shops"):this.$t("t_delete_shop"))+"\n\t\t\t\t\t")])],1)],1)],1):t._e(),t._v(" "),0!==Object.keys(t.store.details.shop).length&&t.$adgate.allow(t.$auth.user,"access","shops")?o("v-dialog",{attrs:{"max-width":"600",persistent:""},model:{value:t.dialogs.details,callback:function(e){t.$set(t.dialogs,"details",e)},expression:"dialogs.details"}},[o("v-card",{staticClass:"mx-auto no-border-radius"},[o("v-card",{attrs:{dark:"",flat:""}},[o("v-card-title",{staticClass:"pa-2"},[o("v-avatar",[o("v-img",{attrs:{src:t.logo(t.store.details.shop.logo)}})],1),t._v(" "),o("v-spacer"),t._v(" "),o("v-btn",{attrs:{icon:"",text:"",to:"/shop/"+t.store.details.shop.store,target:"_blank",color:t.$vuetify.theme.dark?"white":"#4C4A48"}},[o("v-icon",[t._v("mdi-open-in-new")])],1),t._v(" "),t.$adgate.allow(t.$auth.user,"edit","shops")?o("v-btn",{attrs:{icon:"",text:"",to:"/administrator/shops/edit/"+t.store.details.shop.store,color:t.$vuetify.theme.dark?"white":"#4C4A48"}},[o("v-icon",[t._v("mdi-square-edit-outline")])],1):t._e(),t._v(" "),null===t.store.details.shop.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-btn",{attrs:{icon:"",text:"",color:t.$vuetify.theme.dark?"white":"#4C4A48"},on:{click:function(e){return t.remove([t.store.details.shop],t.store.details.index)}}},[o("v-icon",[t._v("mdi-delete")])],1):t._e(),t._v(" "),null===t.store.details.shop.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-btn",{attrs:{icon:"",text:"",color:t.$vuetify.theme.dark?"white":"#4C4A48"},on:{click:function(e){return t.restore([t.store.details.shop],t.store.details.index)}}},[o("v-icon",[t._v("mdi-delete-restore")])],1):t._e(),t._v(" "),o("v-btn",{attrs:{icon:"",text:"",color:t.$vuetify.theme.dark?"white":"#4C4A48"},on:{click:function(e){t.dialogs.details=!1}}},[o("v-icon",[t._v("mdi-close")])],1)],1),t._v(" "),o("v-img",{attrs:{src:t.cover(t.store.details.shop.cover),gradient:"to top, rgba(0,0,0,.44), rgba(0,0,0,.44)","max-height":"250px"}},[o("v-container",{attrs:{"fill-height":""}},[o("v-layout",{attrs:{"align-center":""}},[o("v-layout",{attrs:{column:"","justify-end":""}},[o("div",{staticClass:"headline font-weight-light"},[t._v(t._s(t.store.details.shop.title))]),t._v(" "),o("div",{staticClass:"text-uppercase font-weight-light"},[t._v(t._s(t.store.details.shop.subtitle))])])],1)],1)],1)],1),t._v(" "),o("v-card-text",{staticClass:"py-5"},[o("p",{staticClass:"font-weight-bold",domProps:{innerHTML:t._s(t.store.details.shop.description)}})])],1)],1):t._e(),t._v(" "),o("v-container",[o("div",{staticClass:"m-card"},[o("v-toolbar",{attrs:{flat:""}},[o("v-toolbar-title",[t._v(t._s(t.$t("t_shops")))]),t._v(" "),o("v-spacer"),t._v(" "),t.$adgate.allow(t.$auth.user,"approve","shops")?o("v-fade-transition",[t.selected.length>0?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){return t.activate()}}},n),[o("v-icon",[t._v("mdi-check-all")])],1)]}}],null,!1,3132673436)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_activate")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-fade-transition",[t.selected.length>0?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:"",color:t.$vuetify.theme.dark?"white":"grey darken-3"},on:{click:function(e){t.dialogs.delete=!0}}},n),[o("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,228501886)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-fade-transition",[t.selected.length>0?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:"",color:t.$vuetify.theme.dark?"white":"grey darken-3"},on:{click:function(e){return t.restore()}}},n),[o("v-icon",[t._v("mdi-delete-restore")])],1)]}}],null,!1,1586185791)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_restore")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"access","shops")?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{to:"/administrator/shops/create",text:"",icon:""}},n),[o("v-icon",[t._v("add")])],1)]}}],null,!1,136393354)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_create")))])]):t._e()],1),t._v(" "),o("v-data-table",{attrs:{headers:t.headers,items:t.shops,"item-key":"id","hide-default-footer":"","show-select":"","disable-pagination":"","mobile-breakpoint":1,"no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.shop",fn:function(e){var n=e.item;return[o("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[o("v-list-item",{attrs:{to:"/shop/"+n.store,target:"_blank"}},[o("v-list-item-avatar",[o("img",{attrs:{src:t.logo(n.logo)}})]),t._v(" "),o("v-list-item-content",{staticClass:"table-wrap-title"},[o("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(n.title)}}),t._v(" "),o("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption",domProps:{innerHTML:t._s(n.store)}})],1)],1)],1)]}},{key:"item.country",fn:function(e){var n=e.item;return[n.country?o("v-avatar",{attrs:{size:"36px"}},[o("img",{attrs:{src:t.$homeApi("public/images/flags/large/"+n.country.code.toUpperCase()+".png")}})]):t._e()]}},{key:"item.created",fn:function(e){var o=e.item;return[t._v(t._s(t.$ago(o.created_at)))]}},{key:"item.status",fn:function(e){var n=e.item;return[null===n.deleted_at?o("div",[n.isActive&&!n.isExpired?o("v-btn",{attrs:{text:"",color:"#26c281"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t\t\t")]):n.isPending?o("v-btn",{attrs:{text:"",color:"warning"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_pending"))+"\n\t\t\t\t\t\t\t\t")]):n.isExpired?o("v-btn",{attrs:{text:"",color:"#7f8c8d"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_expired"))+"\n\t\t\t\t\t\t\t\t")]):t._e()],1):t._e(),t._v(" "),null!==n.deleted_at?o("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t\t")]):t._e()]}},{key:"item.options",fn:function(e){var n=e.item;return[o("v-menu",{attrs:{bottom:"",left:!t.$vuetify.rtl,right:!!t.$vuetify.rtl,origin:"center center","max-height":"300px"},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{small:"",icon:""}},n),[o("v-icon",{attrs:{size:"18px",color:t.$vuetify.theme.dark?"white":"grey darken-1"}},[t._v("mdi-dots-vertical")])],1)]}}],null,!0)},[t._v(" "),o("v-list",{attrs:{dense:""}},[t.$adgate.allow(t.$auth.user,"access","shops")?o("v-list-item",{on:{click:function(e){t.details(n,t.shops.indexOf(n))}}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-eye")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_details")))])],1)],1):t._e(),t._v(" "),(n.isPending||n.isExpired)&&t.$adgate.allow(t.$auth.user,"approve","shops")?o("v-list-item",{on:{click:function(e){t.activate([n],t.shops.indexOf(n))}}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-check-all")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_activate")))])],1)],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"edit","shops")?o("v-list-item",{attrs:{to:"/administrator/shops/edit/"+n.store}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-square-edit-outline")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_edit")))])],1)],1):t._e(),t._v(" "),null===n.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-list-item",{on:{click:function(e){t.remove([n],t.shops.indexOf(n))}}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-delete")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_delete")))])],1)],1):t._e(),t._v(" "),null!==n.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","shops")?o("v-list-item",{on:{click:function(e){t.restore([n],t.shops.indexOf(n))}}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-delete-restore")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_restore")))])],1)],1):t._e()],1)],1)]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),o("div",{staticClass:"text-center pt-2"},[o("pagination",{attrs:{data:t.shopsData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[o("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[o("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),o("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[o("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,null);e.default=component.exports}}]);