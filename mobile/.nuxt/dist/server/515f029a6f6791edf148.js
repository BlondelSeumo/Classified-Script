exports.ids=[46],exports.modules={243:function(t,e,n){"use strict";n.r(e);var o={layout:"default/admin",middleware:"administrator",async asyncData({app:t,$axios:e,redirect:n}){t.$adgate.allow(t.$auth.user,"access","comments")||n("/administrator");let o=await e.get("administrator/comments");return{commentsData:o.data,comments:o.data.data}},data:function(){return{selected:[],headers:[{text:this.$t("t_author"),value:"author",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_post"),value:"post",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"},{text:this.$t("t_created"),value:"created",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}],dialogs:{delete:!1,read:!1,edit:!1},readComment:"",editComment:{newComment:"",comment:{},index:null},loading:!1}},head(){return{title:this.$t("t_comments"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{getNextPage(t=1){this.loading=!0,this.$axios.get("administrator/comments?page="+t).then(t=>{this.selected=[],this.commentsData=t.data,this.comments=t.data.data,void 0!==typeof window&&window.scrollTo(0,0),this.loading=!1})},preview:function(t){return 0===t.deal.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+t.deal.unique_id+"/preview_0.jpg")},title:function(t){return null!==t.deal?t.deal.title:null!==t.product?t.product.title:void 0},read:function(t){this.readComment=t,this.dialogs.read=!0},edit:function(t,e){0},update:function(){this.$adgate.allow(this.$auth.user,"edit","comments")?(this.loading=!0,this.$axios.post("administrator/comments/options/update",{id:this.editComment.comment.id,comment:this.editComment.newComment}).then(t=>{this.comments[this.editComment.index].comment=this.editComment.newComment,this.$toasted.show(this.$t("t_toasted_comment_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.dialogs.edit=!1,this.loading=!1}).catch(t=>{this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")},publish:function(t=this.selected,e=null){if(!this.$adgate.allow(this.$auth.user,"approve","comments"))return void this.$router.push("/administrator");let n=this;n.loading=!0,this.$axios.post("administrator/comments/options/publish",{comments:t}).then(t=>{null!==e?(this.comments[e].isPending=!1,this.comments[e].isActive=!0,this.comments[e].isSpam=!1,this.comments[e].deleted_at=null):n.selected.forEach((function(t,e){n.comments.forEach((function(e,o){t.unique_id===e.unique_id&&(n.comments[o].isSpam=!1,n.comments[o].isActive=!0,n.comments[o].isPending=!1,n.comments[o].deleted_at=null)}))})),n.$toasted.show(this.$t("t_toasted_comments_activated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}).catch(t=>{console.log(t),n.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})},remove:function(t=this.selected,e=null){if(!this.$adgate.allow(this.$auth.user,"delete","comments"))return void this.$router.push("/administrator");let n=this;n.loading=!0,this.$axios.post("administrator/comments/options/delete",{comments:t}).then(t=>{null!==e?(this.comments[e].isPending=!1,this.comments[e].isActive=!1,this.comments[e].isSpam=!1,this.comments[e].deleted_at=!0):n.selected.forEach((function(t,e){n.comments.forEach((function(e,o){t.unique_id===e.unique_id&&(n.comments[o].isSpam=!1,n.comments[o].isActive=!1,n.comments[o].isPending=!1,n.comments[o].deleted_at=!0)}))})),n.$toasted.show(this.$t("t_toasted_comments_moved_to_trash"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),n.dialogs.delete=!1,n.loading=!1}).catch(t=>{console.log(t),n.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})},restore:function(t=this.selected,e=null){if(!this.$adgate.allow(this.$auth.user,"delete","comments"))return void this.$router.push("/administrator");let n=this;n.loading=!0,this.$axios.post("administrator/comments/options/restore",{comments:t}).then(t=>{null!==e?(this.comments[e].isPending=!1,this.comments[e].isActive=!0,this.comments[e].isSpam=!1,this.comments[e].deleted_at=null):n.selected.forEach((function(t,e){n.comments.forEach((function(e,o){t.unique_id===e.unique_id&&(n.comments[o].isSpam=!1,n.comments[o].isActive=!0,n.comments[o].isPending=!1,n.comments[o].deleted_at=null)}))})),n.$toasted.show(this.$t("t_toasted_comments_restored"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}).catch(t=>{console.log(t),n.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})},spam:function(t=this.selected,e=null){if(!this.$adgate.allow(this.$auth.user,"delete","comments"))return void this.$router.push("/administrator");let n=this;n.loading=!0,this.$axios.post("administrator/comments/options/spam",{comments:t}).then(t=>{null!==e?(this.comments[e].isPending=!1,this.comments[e].isActive=!1,this.comments[e].isSpam=!0,this.comments[e].deleted_at=null):n.selected.forEach((function(t,e){n.comments.forEach((function(e,o){t.unique_id===e.unique_id&&(n.comments[o].isSpam=!0,n.comments[o].isActive=!1,n.comments[o].isPending=!1,n.comments[o].deleted_at=null)}))})),n.$toasted.show(this.$t("t_toasted_comments_marked_spam"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1}).catch(t=>{console.log(t),n.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),n.loading=!1})}}},l=n(1),component=Object(l.a)(o,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-app",{attrs:{id:"inspire"}},[n("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-dialog",{attrs:{"max-width":"400",persistent:""},model:{value:t.dialogs.delete,callback:function(e){t.$set(t.dialogs,"delete",e)},expression:"dialogs.delete"}},[n("v-card",{staticClass:"py-2"},[n("v-card-text",[n("div",{staticClass:"text-center mb-5"},[n("v-icon",{attrs:{size:"100px",color:"error"}},[t._v("mdi-alert-octagon-outline")])],1),t._v(" "),n("div",{staticClass:"mb-4 text-center headline font-weight-black text-uppercase"},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_are_you_sure"))+"\n\t\t\t\t\t")])]),t._v(" "),n("v-card-actions",[n("v-spacer"),t._v(" "),n("v-btn",{attrs:{color:"grey darken-1",text:""},on:{click:function(e){t.dialogs.delete=!1}}},[t._v("\n\t\t\t\t\t\tCancel\n\t\t\t\t\t")]),t._v(" "),n("v-btn",{attrs:{color:"error",text:""},on:{click:function(e){return t.remove()}}},[t._v("\n\t\t\t\t\t\t"+t._s(t.selected.length>1?t.$t("t_delete_comments"):t.$t("t_delete_comment"))+"\n\t\t\t\t\t")])],1)],1)],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"access","comments")?n("v-dialog",{attrs:{"max-width":"500px"},model:{value:t.dialogs.read,callback:function(e){t.$set(t.dialogs,"read",e)},expression:"dialogs.read"}},[n("v-card",{staticClass:"pa-3"},[n("v-card-title",{staticClass:"pt-1",attrs:{"primary-title":""}},[n("h3",{staticClass:"body-2 mb-0 text-uppercase font-weight-black"},[t._v(t._s(t.$t("t_read_comment")))]),t._v(" "),n("v-spacer"),t._v(" "),n("v-btn",{attrs:{icon:""},on:{click:function(e){t.dialogs.read=!1}}},[n("v-icon",[t._v("mdi-close")])],1)],1),t._v(" "),n("v-card-text",{domProps:{innerHTML:t._s(t.readComment)}})],1)],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"edit","comments")?n("v-dialog",{attrs:{"max-width":"500px",persistent:""},model:{value:t.dialogs.edit,callback:function(e){t.$set(t.dialogs,"edit",e)},expression:"dialogs.edit"}},[n("v-card",{staticClass:"pa-2"},[n("v-card-title",{staticClass:"pt-1",attrs:{"primary-title":""}},[n("h3",{staticClass:"body-2 mb-0 text-uppercase font-weight-black"},[t._v(t._s(t.$t("t_edit_comment")))]),t._v(" "),n("v-spacer"),t._v(" "),n("v-btn",{attrs:{icon:""},on:{click:function(e){t.dialogs.edit=!1}}},[n("v-icon",[t._v("mdi-close")])],1)],1),t._v(" "),n("v-card-text",[n("v-container",{staticClass:"pa-0",attrs:{"grid-list-md":""}},[n("v-layout",{attrs:{wrap:""}},[n("v-flex",{attrs:{xs12:""}},[n("v-textarea",{attrs:{color:"grey lighten-1",label:t.$t("t_comment"),placeholder:t.$t("t_enter_comment"),counter:"500",maxlength:"500","no-resize":"",required:""},model:{value:t.editComment.newComment,callback:function(e){t.$set(t.editComment,"newComment",e)},expression:"editComment.newComment"}})],1)],1)],1)],1),t._v(" "),n("v-card-actions",{staticClass:"pa-2"},[n("v-spacer"),t._v(" "),n("client-only",[n("v-btn",{attrs:{color:t.$vuetify.theme.dark?"#bfbfbf":"#2e3131",text:"",disabled:t.$striphtml(t.editComment.comment.comment)===t.editComment.newComment||""===t.editComment.newComment},on:{click:function(e){return t.update()}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1):t._e(),t._v(" "),n("v-container",[n("div",{staticClass:"m-card"},[n("v-toolbar",{attrs:{flat:""}},[n("v-toolbar-title",[t._v(t._s(t.$t("t_comments")))]),t._v(" "),n("v-spacer"),t._v(" "),t.$adgate.allow(t.$auth.user,"approve","comments")?n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){return t.publish()}}},o),[n("v-icon",[t._v("mdi-check-all")])],1)]}}],null,!1,2456770748)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_activate")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){t.dialogs.delete=!0}}},o),[n("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,3064050874)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){return t.restore()}}},o),[n("v-icon",[t._v("mdi-delete-restore")])],1)]}}],null,!1,2460523131)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_restore")))])]):t._e()],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:""},on:{click:function(e){return t.spam()}}},o),[n("v-icon",[t._v("mdi-alert-octagon")])],1)]}}],null,!1,1212790844)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_spam")))])]):t._e()],1):t._e()],1),t._v(" "),n("v-data-table",{attrs:{headers:t.headers,items:t.comments,"item-key":"id","hide-default-footer":"",loading:t.loading,"show-select":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available"),"mobile-breakpoint":1},scopedSlots:t._u([{key:"item.author",fn:function(e){var n=e.item;return[t._v("\n\t\t\t\t\t\t"+t._s(n.user.username)+"\n\t\t\t\t\t")]}},{key:"item.post",fn:function(e){var o=e.item;return[n("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[n("v-list-item",{attrs:{to:"/listing/"+o.deal.unique_slug,target:"_blank"}},[n("v-list-item-avatar",[n("img",{attrs:{src:t.preview(o)}})]),t._v(" "),n("v-list-item-content",{staticClass:"table-wrap-title"},[n("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(t.title(o))}}),t._v(" "),o.isDeal?n("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[t._v("Deals")]):t._e(),t._v(" "),o.isArticle?n("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[t._v("Articles")]):t._e()],1)],1)],1)]}},{key:"item.status",fn:function(e){var o=e.item;return[null===o.deleted_at?n("div",[o.isActive?n("v-btn",{attrs:{text:"",color:"#26c281"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t\t")]):o.isPending?n("v-btn",{attrs:{text:"",color:"warning"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_pending"))+"\n\t\t\t\t\t\t\t")]):o.isSpam?n("v-btn",{attrs:{text:"",color:"info"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_spam"))+"\n\t\t\t\t\t\t\t")]):t._e()],1):t._e(),t._v(" "),null!==o.deleted_at?n("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t")]):t._e()]}},{key:"item.created",fn:function(e){var n=e.item;return[t._v(t._s(t.$ago(n.created_at)))]}},{key:"item.options",fn:function(e){var o=e.item;return[n("v-menu",{attrs:{bottom:"",left:!t.$vuetify.rtl,right:!!t.$vuetify.rtl,origin:"center center","max-height":"300px"},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n("v-btn",t._g({attrs:{small:"",icon:""}},o),[n("v-icon",{attrs:{size:"18px",color:t.$vuetify.theme.dark?"white":"grey darken-1"}},[t._v("mdi-dots-vertical")])],1)]}}],null,!0)},[t._v(" "),n("v-list",{attrs:{dense:""}},[t.$adgate.allow(t.$auth.user,"access","comments")?n("v-list-item",{on:{click:function(e){return t.read(o.comment)}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-comment-eye")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_read")))])],1)],1):t._e(),t._v(" "),(o.isPending||o.isSpam)&&t.$adgate.allow(t.$auth.user,"approve","comments")?n("v-list-item",{on:{click:function(e){t.publish([o],t.comments.indexOf(o))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-check-all")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_activate")))])],1)],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"edit","comments")?n("v-list-item",{on:{click:function(e){t.edit(o,t.comments.indexOf(o))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-square-edit-outline")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_edit")))])],1)],1):t._e(),t._v(" "),null===o.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-list-item",{on:{click:function(e){t.remove([o],t.comments.indexOf(o))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-delete")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_delete")))])],1)],1):t._e(),t._v(" "),null!==o.deleted_at&&t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-list-item",{on:{click:function(e){t.restore([o],t.comments.indexOf(o))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-delete-restore")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_restore")))])],1)],1):t._e(),t._v(" "),t.$adgate.allow(t.$auth.user,"delete","comments")?n("v-list-item",{on:{click:function(e){t.spam([o],t.comments.indexOf(o))}}},[n("v-list-item-action",[n("v-icon",[t._v("mdi-alert-octagon")])],1),t._v(" "),n("v-list-item-content",[n("v-list-item-title",[t._v(t._s(t.$t("t_spam")))])],1)],1):t._e()],1)],1)]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),n("div",{staticClass:"text-center pt-2"},[n("pagination",{attrs:{data:t.commentsData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[n("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),n("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,"22787ea1");e.default=component.exports}};