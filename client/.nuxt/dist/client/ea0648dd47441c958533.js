(window.webpackJsonp=window.webpackJsonp||[]).push([[142],{615:function(t,e,n){"use strict";n.r(e);var r,l=n(97),o=(n(47),n(27)),c={layout:"default/manager",middleware:"manager",asyncData:(r=Object(o.a)(regeneratorRuntime.mark((function t(e){var n,r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.app,n=e.$axios,e.redirect,t.next=3,n.get("manager/deals");case 3:return r=t.sent,t.abrupt("return",{dealsData:r.data,deals:r.data.data});case 5:case"end":return t.stop()}}),t)}))),function(t){return r.apply(this,arguments)}),data:function(){return{selected:[],dialogs:{delete:!1},loading:!1}},head:function(){return{title:this.$t("t_my_deals"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},computed:{headers:function(){return[{text:this.$t("t_deal"),value:"deal",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_price"),value:"price",sortable:!1,align:"center"},{text:this.$t("t_category"),value:"category",sortable:!1,align:"center"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"},{text:this.$t("t_created"),value:"created",sortable:!1,align:"center"},{text:this.$t("t_expires_in"),value:"expires",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}]}},methods:{getNextPage:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,this.$axios.get("manager/deals?page="+e).then((function(e){t.selected=[],t.dealsData=e.data,t.deals=e.data.data,void 0!==("undefined"==typeof window?"undefined":Object(l.a)(window))&&window.scrollTo(0,0),t.loading=!1}))},archive:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,r=this;r.loading=!0,this.$axios.post("manager/deals/options/archive",{deals:e}).then((function(e){null!==n?(t.deals[n].isPending=!1,t.deals[n].isActive=!1,t.deals[n].isArchived=!0,t.deals[n].deleted_at=null):r.selected.forEach((function(t,e){r.deals.forEach((function(e,n){t.unique_id===e.unique_id&&(r.deals[n].isArchived=!0,r.deals[n].isActive=!1,r.deals[n].isPending=!1,r.deals[n].deleted_at=null)}))})),r.$toasted.show(t.$t("t_toasted_deals_archived"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),r.loading=!1})).catch((function(e){console.log(e),r.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),r.loading=!1}))},remove:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,r=this;r.loading=!0,this.$axios.post("manager/deals/options/delete",{deals:e}).then((function(e){null!==n?(t.deals[n].isPending=!1,t.deals[n].isActive=!1,t.deals[n].isArchived=!1,t.deals[n].deleted_at=!0):r.selected.forEach((function(t,e){r.deals.forEach((function(e,n){t.unique_id===e.unique_id&&(r.deals[n].isArchived=!1,r.deals[n].isActive=!1,r.deals[n].isPending=!1,r.deals[n].deleted_at=!0)}))})),r.$toasted.show(t.$t("t_toasted_deals_deleted"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),r.loading=!1})).catch((function(e){console.log(e),r.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),r.loading=!1}))},preview:function(t){return 0==t.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+t.unique_id+"/preview_0.jpg")}}},d=n(52),component=Object(d.a)(c,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-app",{attrs:{id:"inspire"}},[n("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),n("v-dialog",{attrs:{"max-width":"400",persistent:""},model:{value:t.dialogs.delete,callback:function(e){t.$set(t.dialogs,"delete",e)},expression:"dialogs.delete"}},[n("v-card",{staticClass:"py-2"},[n("v-card-text",[n("div",{staticClass:"text-center mb-5"},[n("v-icon",{attrs:{size:"100px",color:"error"}},[t._v("mdi-alert-octagon-outline")])],1),t._v(" "),n("div",{staticClass:"mb-4 text-center headline font-weight-black text-uppercase"},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_are_you_sure"))+"\n\t\t\t\t\t")])]),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{color:"grey darken-1",text:""},on:{click:function(e){t.dialogs.delete=!1}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_cancel"))+"\n\t\t\t\t\t")]),t._v(" "),n("v-btn",{attrs:{color:"error",text:""},on:{click:function(e){return t.remove()}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.selected.length>1?t.$t("t_delete_deals"):t.$t("t_delete_deal"))+"\n\t\t\t\t\t")])],1)],1)],1),t._v(" "),n("v-container",[n("div",{staticClass:"m-card"},[n("v-toolbar",{staticClass:"elevation-0",attrs:{color:"white"}},[n("v-toolbar-title",[t._v(t._s(t.$t("t_deals")))]),t._v(" "),n("v-spacer"),t._v(" "),n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var r=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:"",color:"grey darken-3"},on:{click:function(e){t.dialogs.delete=!0}}},r),[n("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,421198673)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1),t._v(" "),n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var r=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:"",color:"grey darken-3"},on:{click:function(e){return t.archive()}}},r),[n("v-icon",[t._v("mdi-archive")])],1)]}}],null,!1,1283527364)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_archive")))])]):t._e()],1),t._v(" "),n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var r=e.on;return[n("v-btn",t._g({attrs:{to:"/manager/deals/create",text:"",icon:""}},r),[n("v-icon",[t._v("add")])],1)]}}])},[t._v(" "),n("span",[t._v(t._s(t.$t("t_create")))])])],1),t._v(" "),n("v-data-table",{attrs:{headers:t.headers,items:t.deals,"item-key":"id","hide-default-footer":"","show-select":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.deal",fn:function(e){var r=e.item;return[n("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[n("v-list-item",{attrs:{to:"/listing/"+r.unique_slug,target:"_blank",nuxt:"",ripple:!1}},[n("v-list-item-avatar",[n("img",{attrs:{src:t.preview(r)}})]),t._v(" "),n("v-list-item-content",{staticClass:"table-wrap-title"},[n("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(r.title)}}),t._v(" "),n("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[n("b",[t._v(t._s(r.unique_id))])])],1)],1)],1)]}},{key:"item.price",fn:function(e){var r=e.item;return[null===r.price&&null===r.currency&&null===r.locale?n("div"):n("div",[t._v(t._s(t.$getCurrency(r.price,r.currency,r.locale)))])]}},{key:"item.category",fn:function(e){var n=e.item;return[t._v(t._s(n.category.title))]}},{key:"item.status",fn:function(e){var r=e.item;return[null===r.deleted_at?n("div",[!r.isUpgraded||r.isPending||r.isArchived?r.isActive?n("v-btn",{attrs:{text:"",color:"#2ecc71"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t\t")]):r.isPending?n("v-btn",{attrs:{text:"",color:"warning"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_pending"))+"\n\t\t\t\t\t\t\t")]):r.isArchived?n("v-btn",{attrs:{text:"",color:"#95a5a6"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_archived"))+"\n\t\t\t\t\t\t\t")]):t._e():n("v-btn",{attrs:{text:"",color:"#19b5fe"}},["urgent"===r.upgradedTo?n("span",{directives:[{name:"t",rawName:"v-t",value:"t_urgent",expression:"'t_urgent'"}]}):t._e(),t._v(" "),"highlight"===r.upgradedTo?n("span",{directives:[{name:"t",rawName:"v-t",value:"t_highlight",expression:"'t_highlight'"}]}):t._e(),t._v(" "),"featured"===r.upgradedTo?n("span",{directives:[{name:"t",rawName:"v-t",value:"t_featured",expression:"'t_featured'"}]}):t._e()])],1):t._e(),t._v(" "),null!==r.deleted_at?n("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t")]):t._e()]}},{key:"item.created",fn:function(e){var n=e.item;return[t._v(t._s(t.$ago(n.created_at)))]}},{key:"item.expires",fn:function(e){var n=e.item;return[t._v(t._s(t.$dateString(n.expires_at)))]}},{key:"item.options",fn:function(e){var r=e.item;return[n("v-menu",{attrs:{bottom:"",left:!t.$vuetify.rtl,right:!!t.$vuetify.rtl,origin:"center center","max-height":"300px"},scopedSlots:t._u([{key:"activator",fn:function(e){var r=e.on;return[n("v-btn",t._g({attrs:{small:"",icon:""}},r),[n("v-icon",{attrs:{size:"18px",color:"grey darken-1"}},[t._v("mdi-dots-vertical")])],1)]}}],null,!0)},[t._v(" "),n("v-list",{attrs:{dense:""}},[n("v-list-item",{attrs:{to:"/manager/deals/statistics/"+r.unique_id}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-chart-bar")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_statistics")))])],1)],1),t._v(" "),r.isActive?n("v-list-item",{on:{click:function(e){t.archive([r],t.deals.indexOf(r))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-archive")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_archive")))])],1)],1):t._e(),t._v(" "),n("v-list-item",{attrs:{to:"/manager/deals/edit/"+r.unique_id}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-square-edit-outline")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_edit")))])],1)],1),t._v(" "),null===r.deleted_at?n("v-list-item",{on:{click:function(e){t.remove([r],t.deals.indexOf(r))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-delete")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_delete")))])],1)],1):t._e()],1)],1)]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),n("div",{staticClass:"text-center pt-2"},[n("pagination",{attrs:{data:t.dealsData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[n("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),n("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,null);e.default=component.exports}}]);