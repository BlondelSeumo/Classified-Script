exports.ids=[54],exports.modules={220:function(t,e,n){"use strict";n.r(e);var l={layout:"default/admin",middleware:"administrator",async asyncData({app:t,$axios:e,redirect:n}){t.$owgate.allow(t.$auth.user,"access","plans")||n("/administrator");let l=await e.get("administrator/deals/packages");return{packagesData:l.data,packages:l.data.data}},data:function(){return{selected:[],headers:[{text:this.$t("t_title"),value:"title",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_price"),value:"price",sortable:!1,align:"center"},{text:this.$t("t_discount"),value:"discount",sortable:!1,align:"center"},{text:this.$t("t_type"),value:"type",sortable:!1,align:"center"},{text:this.$t("t_head_days"),value:"days",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}],dialogs:{delete:!1},loading:!1}},head(){return{title:this.$t("t_packages"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{getNextPage(t=1){this.loading=!0,this.$axios.get("administrator/deals/packages?page="+t).then(t=>{this.selected=[],this.packagesData=t.data,this.packages=t.data.data,void 0!==typeof window&&window.scrollTo(0,0),this.loading=!1})},remove:function(t=this.selected,e=null){if(!this.$owgate.allow(this.$auth.user,"delete","plans"))return void this.$router.push("/administrator");let n=this;n.loading=!0,this.$axios.post("administrator/membership/plans/options/delete",{plans:t}).then(t=>{null!==e?this.plans[e].deleted_at=!0:n.selected.forEach((function(t,e){n.plans.forEach((function(e,l){t.unique_id===e.unique_id&&(n.plans[l].deleted_at=!0)}))})),n.$toasted.show(this.$t("t_toasted_plans_deleted"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),n.dialogs.delete=!1,n.loading=!1}).catch(t=>{console.log(t),n.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})},restore:function(t=this.selected,e=null){if(!this.$owgate.allow(this.$auth.user,"delete","plans"))return void this.$router.push("/administrator");let n=this;n.loading=!0,this.$axios.post("administrator/membership/plans/options/restore",{plans:t}).then(t=>{null!==e?this.plans[e].deleted_at=null:n.selected.forEach((function(t,e){n.plans.forEach((function(e,l){t.unique_id===e.unique_id&&(n.plans[l].deleted_at=null)}))})),n.$toasted.show(this.$t("t_toasted_plans_restored"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}).catch(t=>{console.log(t),n.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})}}},o=n(1),component=Object(o.a)(l,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-app",{attrs:{id:"inspire"}},[n("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),t.$owgate.allow(t.$auth.user,"delete","plans")?n("v-dialog",{attrs:{"max-width":"400",persistent:""},model:{value:t.dialogs.delete,callback:function(e){t.$set(t.dialogs,"delete",e)},expression:"dialogs.delete"}},[n("v-card",{staticClass:"py-2"},[n("v-card-text",[n("div",{staticClass:"text-center mb-5"},[n("v-icon",{attrs:{size:"100px",color:"error"}},[t._v("mdi-alert-octagon-outline")])],1),t._v(" "),n("div",{staticClass:"mb-4 text-center headline font-weight-black text-uppercase"},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_are_you_sure"))+"\n\t\t\t\t\t")])]),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{color:"grey darken-1",text:""},on:{click:function(e){t.dialogs.delete=!1}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_cancel"))+"\n\t\t\t\t\t")]),t._v(" "),n("v-btn",{attrs:{color:"error",text:""},on:{click:function(e){return t.remove()}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.selected.length>1?t.$t("t_delete_plans"):t.$t("t_delete_plan"))+"\n\t\t\t\t\t")])],1)],1)],1):t._e(),t._v(" "),n("v-container",[n("div",{staticClass:"m-card"},[n("v-toolbar",{attrs:{flat:""}},[n("v-toolbar-title",[t._v(t._s(t.$t("t_packages")))]),t._v(" "),n("v-spacer"),t._v(" "),t.$owgate.allow(t.$auth.user,"delete","plans")?n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:"",color:t.$vuetify.theme.dark?"white":"grey darken-3"},on:{click:function(e){t.dialogs.delete=!0}}},l),[n("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,228501886)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1):t._e(),t._v(" "),t.$owgate.allow(t.$auth.user,"delete","plans")?n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:"",color:t.$vuetify.theme.dark?"white":"grey darken-3"},on:{click:function(e){return t.restore()}}},l),[n("v-icon",[t._v("mdi-delete-restore")])],1)]}}],null,!1,1586185791)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_restore")))])]):t._e()],1):t._e(),t._v(" "),t.$owgate.allow(t.$auth.user,"create","plans")?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n("v-btn",t._g({attrs:{to:"/administrator/deals/packages/create",text:"",icon:""}},l),[n("v-icon",[t._v("add")])],1)]}}],null,!1,3606121988)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_create")))])]):t._e()],1),t._v(" "),n("v-data-table",{attrs:{headers:t.headers,items:t.packages,"item-key":"id","hide-default-footer":"",loading:t.loading,"show-select":"","disable-pagination":"","mobile-breakpoint":1,"no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.title",fn:function(e){var l=e.item;return[n("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[n("v-list-item",[n("v-list-item-content",{staticClass:"table-wrap-title"},[n("v-list-item-title",{staticClass:"table-wrap-title pb-2",domProps:{innerHTML:t._s(l.title)}}),t._v(" "),n("v-list-item-subtitle",{staticClass:"pb-1 text-uppercase text-small font-weight-regular d-block",domProps:{innerHTML:t._s(l.slug)}})],1)],1)],1)]}},{key:"item.price",fn:function(e){var l=e.item;return[n("span",{staticClass:"font-weight-bold"},[t._v(t._s(t.$getCurrency(l.price,l.currency,l.locale)))])]}},{key:"item.discount",fn:function(e){var l=e.item;return[l.discount?n("div",{staticClass:"font-weight-black"},[t._v("\n\t\t\t\t\t\t\t"+t._s(l.discount)+" %\n\t\t\t\t\t\t")]):t._e()]}},{key:"item.type",fn:function(e){var l=e.item;return["featured"===l.type?n("v-btn",{attrs:{text:"",color:"#44add5"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_featured"))+"\n\t\t\t\t\t\t")]):t._e(),t._v(" "),"urgent"===l.type?n("v-btn",{attrs:{text:"",color:"#f85050"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_urgent"))+"\n\t\t\t\t\t\t")]):t._e(),t._v(" "),"highlight"===l.type?n("v-btn",{attrs:{text:"",color:"#fcc62c"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_highlight"))+"\n\t\t\t\t\t\t")]):t._e()]}},{key:"item.days",fn:function(e){var l=e.item;return[n("div",{staticClass:"font-weight-black"},[t._v(t._s(t._f("numeralFormat")(l.days)))])]}},{key:"item.options",fn:function(e){var l=e.item;return[n("v-menu",{attrs:{bottom:"",left:!t.$vuetify.rtl,right:!!t.$vuetify.rtl,origin:"center center","max-height":"300px"},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n("v-btn",t._g({attrs:{small:"",icon:""}},l),[n("v-icon",{attrs:{size:"20px",color:"grey darken-1"}},[t._v("mdi-dots-vertical")])],1)]}}],null,!0)},[t._v(" "),n("v-list",{attrs:{dense:""}},[t.$owgate.allow(t.$auth.user,"edit","plans")?n("v-list-item",{attrs:{to:"/administrator/deals/packages/edit/"+l.unique_id}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-square-edit-outline")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_edit")))])],1)],1):t._e(),t._v(" "),null===l.deleted_at&&t.$owgate.allow(t.$auth.user,"delete","plans")?n("v-list-item",{on:{click:function(e){t.remove([l],t.packages.indexOf(l))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-delete")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_delete")))])],1)],1):t._e(),t._v(" "),null!==l.deleted_at&&t.$owgate.allow(t.$auth.user,"delete","plans")?n("v-list-item",{on:{click:function(e){t.restore([l],t.packages.indexOf(l))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-delete-restore")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_restore")))])],1)],1):t._e()],1)],1)]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),n("div",{staticClass:"text-center pt-2"},[n("pagination",{attrs:{data:t.packagesData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[n("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),n("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,"ef0bd728");e.default=component.exports}};