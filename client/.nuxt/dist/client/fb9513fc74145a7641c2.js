(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{519:function(t,e,n){},520:function(t,e,n){},521:function(t,e,n){"use strict";var o=n(519);n.n(o).a},522:function(t,e,n){"use strict";var o=n(520);n.n(o).a},523:function(t,e,n){"use strict";n(47);var o,r=n(27),c={name:"sidebar",middleware:"auth",layout:"default/main",data:function(){return{items:[{icon:"mdi-settings",title:"t_account_settings",to:"/account/settings",enabled:!0},{icon:"mdi-image-multiple",title:"t_my_deals",to:"/account/deals",enabled:!0},{icon:"mdi-message",title:"t_message_center",to:"/account/messages",enabled:this.$megate.allow(this.$auth.user,"receive","messages")},{icon:"mdi-tag",title:"t_received_offers",to:"/account/offers",enabled:this.$megate.allow(this.$auth.user,"receive","offers")},{icon:"mdi-folder-search",title:"t_saved_search",to:"/account/search",enabled:this.$megate.allow(this.$auth.user,"save","search")},{icon:"mdi-store",title:"t_following_shops",to:"/account/following",enabled:this.$megate.allow(this.$auth.user,"follow","shops")},{icon:"mdi-wallet-membership",title:"t_membership",to:"/account/subscription",enabled:!0},{divider:!0},{icon:"mdi-two-factor-authentication",title:"t_two_factor_auth",to:"/account/2fa",enabled:!0}]}},methods:{logout:(o=Object(r.a)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,this.$auth.logout();case 2:this.$router.push({name:"mainIndex"});case 3:case"end":return t.stop()}}),t,this)}))),function(){return o.apply(this,arguments)})}},l=(n(521),n(522),n(52)),component=Object(l.a)(c,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-flex",{staticClass:"user-area",attrs:{xs3:""}},[n("v-navigation-drawer",{staticClass:"m-card pb-3",staticStyle:{height:"auto"},attrs:{width:"auto"}},[n("v-list",{staticClass:"user-sidebar",attrs:{dense:""}},[t._l(t.items,(function(e,o){return[e.divider?n("v-divider",{key:e.to}):t._e(),t._v(" "),e.divider?t._e():n("nuxt-link",{key:o,attrs:{to:e.to,tag:"v-list-item","active-class":"active-item"}},[n("v-list-item",[n("v-list-item-action",[n("v-icon",{staticClass:"v-icon theme--light mdi",class:e.icon})],1),t._v(" "),n("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.title,expression:"item.title"}]})],1)],1)]})),t._v(" "),n("v-list-item",{staticClass:"user-signout",on:{click:function(e){return e.preventDefault(),t.logout()}}},[n("v-list-item-action",[n("v-icon",{staticClass:"v-icon theme--light mdi mdi-logout"})],1),t._v(" "),n("v-list-item-title",[t._v(t._s(t.$t("t_sign_out")))])],1)],2)],1)],1)}),[],!1,null,"7a304184",null);e.a=component.exports},652:function(t,e,n){"use strict";n.r(e);var o,r=n(97),c=(n(231),n(47),n(27)),l={middleware:"auth",layout:"default/main",components:{"v-sidebar":n(523).a},asyncData:(o=Object(c.a)(regeneratorRuntime.mark((function t(e){var n,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n=e.$axios,t.next=3,n.get("account/search");case 3:return o=t.sent,t.abrupt("return",{search:o.data.saved.data,searchData:o.data.saved,seo:{title:o.data.seo.title,separator:o.data.seo.separator,description:o.data.seo.description,favicon:o.data.seo.favicon}});case 5:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{selected:[],dialogs:{delete:!1},loading:!1}},head:function(){return{title:this.$t("t_saved_search"),titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.title),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},computed:{headers:function(){return[{text:this.$t("t_search"),value:"search",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_saved_at"),value:"saved",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}]}},methods:{getNextPage:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,this.$axios.get("account/search?page="+e).then((function(e){t.selected=[],t.searchData=e.data.saved,t.search=e.data.saved.data,void 0!==("undefined"==typeof window?"undefined":Object(r.a)(window))&&window.scrollTo(0,0),t.loading=!1}))},remove:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.selected,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,o=this;o.loading=!0,this.$axios.post("account/search/options/delete",{search:e}).then((function(e){null!==n?t.search.splice(n):o.selected.forEach((function(t,e){o.search.forEach((function(e,n){t.id===e.id&&o.search.splice(n)}))})),o.$toasted.show(t.$t("t_toasted_search_deleted"),{icon:"done_all",className:t.$vuetify.rtl?"toasted-rtl":""}),o.dialogs.delete=!1,o.loading=!1})).catch((function(e){o.$toasted.error(t.$t("t_toasted_something_went_wrong"),{icon:"error",className:t.$vuetify.rtl?"toasted-rtl":""}),o.loading=!1}))},launch:function(t){this.$root.$emit("launchSearch",t.search)}}},d=n(52),component=Object(d.a)(l,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-app",{attrs:{id:"inspire"}},[n("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[n("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),n("v-content",[n("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[n("v-layout",{attrs:{wrap:"","fill-height":""}},[n("v-sidebar"),t._v(" "),n("v-flex",{attrs:{xs9:""}},[n("v-card",{staticClass:"m-card",attrs:{text:""}},[n("v-toolbar",{attrs:{color:"white",flat:""}},[n("v-toolbar-title",{staticClass:"title"},[t._v(t._s(t.$t("t_saved_search")))]),t._v(" "),n("v-spacer"),t._v(" "),n("v-fade-transition",[t.selected.length>0?n("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n("v-btn",t._g({attrs:{text:"",icon:"",color:"grey darken-3"},on:{click:function(e){return t.remove()}}},o),[n("v-icon",[t._v("mdi-delete")])],1)]}}],null,!1,2229815835)},[t._v(" "),n("span",[t._v(t._s(t.$t("t_delete")))])]):t._e()],1)],1),t._v(" "),n("v-data-table",{attrs:{headers:t.headers,items:t.search,"item-key":"id","hide-default-footer":"","show-select":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.search",fn:function(e){var o=e.item;return[n("div",{staticClass:"font-weight-bold"},[t._v(t._s(o.search))])]}},{key:"item.saved",fn:function(e){var n=e.item;return[t._v(t._s(t.$ago(n.created_at)))]}},{key:"item.options",fn:function(e){var o=e.item;return[n("v-icon",{class:t.$vuetify.rtl?"ml-2":"mr-2",attrs:{small:""},on:{click:function(e){t.remove([o],t.search.indexOf(o))}}},[t._v("\n\t\t\t\t\t\t\t\t\t\tmdi-delete\n\t\t\t\t\t\t\t\t\t")]),t._v(" "),n("v-icon",{attrs:{small:""},on:{click:function(e){return t.launch(o)}}},[t._v("\n\t\t\t\t\t\t\t\t\t\tmdi-launch\n\t\t\t\t\t\t\t\t\t")])]}}]),model:{value:t.selected,callback:function(e){t.selected=e},expression:"selected"}})],1),t._v(" "),n("div",{staticClass:"text-center pt-2"},[n("pagination",{attrs:{data:t.searchData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[n("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),n("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[n("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);