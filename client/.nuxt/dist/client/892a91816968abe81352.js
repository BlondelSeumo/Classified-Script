(window.webpackJsonp=window.webpackJsonp||[]).push([[156],{641:function(t,e,r){"use strict";r.r(e);r(47);var n,l=r(27),o={layout:"default/moderator",middleware:"moderator",asyncData:(n=Object(l.a)(regeneratorRuntime.mark((function t(e){var r,n;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.app,r=e.$axios,e.redirect,t.next=3,r.post("moderator");case 3:return n=t.sent,t.abrupt("return",{deals:n.data.deals,shops:n.data.shops});case 5:case"end":return t.stop()}}),t)}))),function(t){return n.apply(this,arguments)}),data:function(){return{headers:[{deals:[{text:this.$t("t_deal"),value:"deal",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_price"),value:"price",sortable:!1,align:"center"},{text:this.$t("t_category"),value:"category",sortable:!1,align:"center"},{text:this.$t("t_created"),value:"created",sortable:!1,align:"center"},{text:this.$t("t_expires_in"),value:"expires",sortable:!1,align:"center"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"}],shops:[{text:this.$t("t_shop"),value:"shop",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_country"),value:"country",sortable:!1,align:"center"},{text:this.$t("t_created"),value:"created",sortable:!1,align:"center"},{text:this.$t("t_status"),value:"status",sortable:!1,align:"center"}]}]}},head:function(){return{title:this.$t("t_dashboard"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$store.state.settings.title),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{preview:function(t){return 0==t.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+t.unique_id+"/preview_0.jpg")},logo:function(t){return null===t?this.$homeApi("storage/app/uploads/default/store/no-logo.png"):this.$homeApi("storage/app/"+t)}}},c=r(52),component=Object(c.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-container",{attrs:{fluid:"","grid-list-xl":"",id:"home-stats"}},[r("v-layout",{attrs:{wrap:""}},[t.$mogate.allow(t.$auth.user,"access","deals")?r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"m-card"},[r("v-toolbar",{attrs:{color:"white",flat:""}},[r("v-toolbar-title",[t._v(t._s(t.$t("t_recent_deals")))])],1),t._v(" "),r("v-data-table",{attrs:{headers:t.headers[0].deals,items:t.deals,"item-key":"id","hide-default-footer":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.deal",fn:function(e){var n=e.item;return[r("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[r("v-list-item",{attrs:{nuxt:"",ripple:!1,to:"/listing/"+n.unique_slug,target:"_blank"}},[r("v-list-item-avatar",[r("img",{attrs:{src:t.preview(n)}})]),t._v(" "),r("v-list-item-content",{staticClass:"table-wrap-title"},[r("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(n.title)}}),t._v(" "),r("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[r("b",[t._v(t._s(n.user.username))])])],1)],1)],1)]}},{key:"item.price",fn:function(e){var n=e.item;return[null===n.price&&null===n.currency&&null===n.locale?r("div",{staticClass:"font-weight-black"}):r("div",{staticClass:"font-weight-black"},[t._v(t._s(t.$getCurrency(n.price,n.currency,n.locale)))])]}},{key:"item.category",fn:function(e){var r=e.item;return[t._v(t._s(r.category.title))]}},{key:"item.created",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t"+t._s(t.$ago(r.created_at))+"\n\t\t\t\t\t\t")]}},{key:"item.expires",fn:function(e){var r=e.item;return[t._v(t._s(t.$dateString(r.expires_at)))]}},{key:"item.status",fn:function(e){var n=e.item;return[null===n.deleted_at?r("div",[!n.isUpgraded||n.isPending||n.isArchived?n.isActive?r("v-btn",{attrs:{text:"",color:"#2ecc71"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t\t\t")]):n.isPending?r("v-btn",{attrs:{text:"",color:"warning"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_pending"))+"\n\t\t\t\t\t\t\t\t")]):n.isArchived?r("v-btn",{attrs:{text:"",color:"#95a5a6"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_archived"))+"\n\t\t\t\t\t\t\t\t")]):t._e():r("v-btn",{attrs:{text:"",color:"#19b5fe"}},["urgent"===n.upgradedTo?r("span",[t._v(t._s(t.$t("t_urgent")))]):t._e(),t._v(" "),"highlight"===n.upgradedTo?r("span",[t._v(t._s(t.$t("t_highlight")))]):t._e(),t._v(" "),"featured"===n.upgradedTo?r("span",[t._v(t._s(t.$t("t_featured")))]):t._e()])],1):t._e(),t._v(" "),null!==n.deleted_at?r("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t\t")]):t._e()]}}],null,!1,3869588536)})],1)]):t._e(),t._v(" "),t.$mogate.allow(t.$auth.user,"access","shops")?r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"m-card"},[r("v-toolbar",{attrs:{color:"white",flat:""}},[r("v-toolbar-title",[t._v(t._s(t.$t("t_recent_shops")))])],1),t._v(" "),r("v-data-table",{attrs:{headers:t.headers[0].shops,items:t.shops,"item-key":"id","no-data-text":t.$t("t_table_no_data_available"),"hide-default-footer":"","disable-pagination":""},scopedSlots:t._u([{key:"item.shop",fn:function(e){var n=e.item;return[r("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[r("v-list-item",{attrs:{nuxt:"",ripple:!1,to:"/shop/"+n.store,target:"_blank"}},[r("v-list-item-avatar",[r("img",{attrs:{src:t.logo(n.logo)}})]),t._v(" "),r("v-list-item-content",{staticClass:"table-wrap-title"},[r("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(n.title)}}),t._v(" "),r("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption",domProps:{innerHTML:t._s(n.store)}})],1)],1)],1)]}},{key:"item.country",fn:function(e){var n=e.item;return[n.country?r("v-avatar",{attrs:{size:"36px"}},[r("img",{attrs:{src:t.$homeApi("public/images/flags/large/"+n.country.code+".png")}})]):r("v-avatar",{attrs:{size:"36px"}},[r("img",{attrs:{src:t.$homeApi("public/images/flags/large/unknown.png")}})])]}},{key:"item.created",fn:function(e){var r=e.item;return[t._v(t._s(t.$ago(r.created_at)))]}},{key:"item.status",fn:function(e){var n=e.item;return[null===n.deleted_at?r("div",[n.isActive&&!n.isExpired?r("v-btn",{attrs:{text:"",color:"#26c281"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_active"))+"\n\t\t\t\t\t\t\t\t")]):n.isPending?r("v-btn",{attrs:{text:"",color:"warning"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_pending"))+"\n\t\t\t\t\t\t\t\t")]):n.isExpired?r("v-btn",{attrs:{text:"",color:"#7f8c8d"}},[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(t.$t("t_expired"))+"\n\t\t\t\t\t\t\t\t")]):t._e()],1):t._e(),t._v(" "),null!==n.deleted_at?r("v-btn",{attrs:{text:"",color:"error"}},[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_deleted"))+"\n\t\t\t\t\t\t\t")]):t._e()]}}],null,!1,2676466015)})],1)]):t._e()],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);