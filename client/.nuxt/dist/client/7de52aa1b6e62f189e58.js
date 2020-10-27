(window.webpackJsonp=window.webpackJsonp||[]).push([[47],{710:function(t,e,o){"use strict";o.r(e);var n,l=o(97),r=(o(47),o(27)),c={layout:"default/admin",middleware:"administrator",asyncData:(n=Object(r.a)(regeneratorRuntime.mark((function t(e){var o,n,l,r;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return o=e.app,n=e.$axios,l=e.redirect,o.$adgate.allow(o.$auth.user,"access","blog")||l("/administrator"),t.next=4,n.get("administrator/blog");case 4:return r=t.sent,t.abrupt("return",{blogData:r.data,blog:r.data.data});case 6:case"end":return t.stop()}}),t)}))),function(t){return n.apply(this,arguments)}),data:function(){return{selected:[],headers:[{text:this.$t("t_cover"),value:"cover",sortable:!1,align:"center"},{text:this.$t("t_title"),value:"title",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_author"),value:"author",sortable:!1,align:"center"},{text:this.$t("t_created"),value:"created",sortable:!1,align:"center"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}],dialogs:{delete:!1},loading:!1}},head:function(){return{title:this.$t("t_blog"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{getNextPage:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,this.$axios.get("administrator/blog?page="+e).then((function(e){t.selected=[],t.blogData=e.data,t.blog=e.data.data,void 0!==("undefined"==typeof window?"undefined":Object(l.a)(window))&&window.scrollTo(0,0),t.loading=!1}))},cover:function(article){return null===article.cover?this.$homeApi("storage/app/uploads/default/article/no-image.png"):this.$homeApi("storage/app/"+article.cover)},remove:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$adgate.allow(this.$auth.user,"delete","blog")){var n=this;n.loading=!0,this.$axios.post("administrator/blog/options/delete",{articles:e}).then((function(e){null!==o?t.blog[o].deleted_at=!0:n.selected.forEach((function(t,e){n.blog.forEach((function(e,o){t.id===e.id&&(n.blog[o].deleted_at=!0)}))})),n.$toasted.show(t.$t("t_toasted_articles_moved_to_trash"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),n.dialogs.delete=!1,n.loading=!1})).catch((function(e){console.log(e),n.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}))}else this.$router.push("/administrator")},restore:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,o=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null;if(this.$adgate.allow(this.$auth.user,"delete","blog")){var n=this;n.loading=!0,this.$axios.post("administrator/blog/options/restore",{articles:e}).then((function(e){null!==o?t.blog[o].deleted_at=null:n.selected.forEach((function(t,e){n.blog.forEach((function(e,o){t.id===e.id&&(n.blog[o].deleted_at=null)}))})),n.$toasted.show(t.$t("t_toasted_articles_restored"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})).catch((function(e){console.log(e),n.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}))}else this.$router.push("/administrator")}}},d=o(52),component=Object(d.a)(c,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-app",{attrs:{id:"inspire"}},[o("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[o("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","blog")?o("v-dialog",{attrs:{"max-width":"400",persistent:""},model:{value:t.dialogs.delete,callback:function(e){t.$set(t.dialogs,"delete",e)},expression:"dialogs.delete"}},[o("v-card",{staticClass:"py-2"},[o("v-card-text",[o("div",{staticClass:"text-center mb-5"},[o("v-icon",{attrs:{size:"100px",color:"error"}},[t._v("mdi-alert-octagon-outline")])],1),t._v(" "),o("div",{staticClass:"mb-4 text-center headline font-weight-black text-uppercase"},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_are_you_sure"))+"\n\t\t\t\t\t")])]),t._v(" "),o("v-card-actions",[o("v-spacer"),t._v(" "),o("v-btn",{attrs:{color:"grey darken-1",text:""},on:{click:function(e){t.dialogs.delete=!1}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_cancel"))+"\n\t\t\t\t\t")]),t._v(" "),o("v-btn",{attrs:{color:"error",text:""},on:{click:function(e){return t.remove()}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.selected.length>1?this.$t("t_delete_articles"):this.$t("t_delete_article"))+"\n\t\t\t\t\t")])],1)],1)],1):t._e(),t._v(" "),o("v-container",[o("div",{staticClass:"m-card"},[o("v-toolbar",{staticClass:"elevation-0",attrs:{color:"white"}},[o("v-toolbar-title",[t._v(t._s(t.$t("t_articles")))]),t._v(" "),o("v-spacer"),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","blog")?o("v-fade-transition",[t.selected.length>0?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){t.dialogs.delete=!0}}},n),[o("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,3064050874)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","blog")?o("v-fade-transition",[t.selected.length>0?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){return t.restore()}}},n),[o("v-icon",[t._v("mdi-delete-restore")])],1)]}}],null,!1,2460523131)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_restore")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"create","blog")?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{to:"/administrator/blog/create",text:"",icon:""}},n),[o("v-icon",[t._v("add")])],1)]}}],null,!1,3814717627)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_create")))])]):t._e()],1),t._v(" "),o("v-data-table",{attrs:{headers:t.headers,items:t.blog,"item-key":"id","hide-default-footer":"",loading:t.loading,"show-select":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.cover",fn:function(e){var n=e.item;return[o("v-avatar",{attrs:{size:"36px"}},[o("img",{attrs:{src:t.cover(n)}})])]}},{key:"item.title",fn:function(e){var n=e.item;return[o("router-link",{staticClass:"font-weight-medium",attrs:{target:"_blank",to:"/blog/"+n.slug}},[t._v(t._s(n.title))])]}},{key:"item.author",fn:function(e){var o=e.item;return[t._v(t._s(o.author.username))]}},{key:"item.created",fn:function(e){var o=e.item;return[t._v(t._s(t.$ago(o.created_at)))]}},{key:"item.status",fn:function(e){var n=e.item;return[null===n.deleted_at?o("v-btn",{attrs:{text:"",color:"#26c281"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t")]):t._e(),t._v(" "),null!==n.deleted_at?o("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t")]):t._e()]}},{key:"item.options",fn:function(e){var n=e.item;return[o("v-menu",{attrs:{bottom:"",left:!t.$vuetify.rtl,right:!!t.$vuetify.rtl,origin:"center center","max-height":"300px"},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{small:"",icon:""}},n),[o("v-icon",{attrs:{size:"18px",color:"grey darken-1"}},[t._v("mdi-dots-vertical")])],1)]}}],null,!0)},[t._v(" "),o("v-list",{attrs:{dense:""}},[t.$adgate.allow(t.$auth.user,"edit","blog")?o("v-list-item",{attrs:{to:"/administrator/blog/edit/"+n.unique_id}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-square-edit-outline")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_edit")))])],1)],1):t._e(),t._v(" "),null===n.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","blog")?o("v-list-item",{on:{click:function(e){t.remove([n],t.blog.indexOf(n))}}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-delete")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_delete")))])],1)],1):t._e(),t._v(" "),null!==n.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","blog")?o("v-list-item",{on:{click:function(e){t.restore([n],t.blog.indexOf(n))}}},[o("v-list-item-action",[o("v-icon",[t._v("mdi-delete-restore")])],1),t._v(" "),o("v-list-item-content",[o("v-list-item-title",[t._v(t._s(t.$t("t_restore")))])],1)],1):t._e()],1)],1)]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),o("div",{staticClass:"text-center pt-2"},[o("pagination",{attrs:{data:t.blogData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[o("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[o("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),o("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[o("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,null);e.default=component.exports}}]);