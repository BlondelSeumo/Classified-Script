(window.webpackJsonp=window.webpackJsonp||[]).push([[137],{629:function(t,e,r){"use strict";r.r(e);r(47);var o,n=r(27),l={layout:function(t){return t.isMobile?"mobile/main":"default/main"},asyncData:(o=Object(n.a)(regeneratorRuntime.mark((function t(e){var r,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.$axios,t.next=3,r.get("home");case 3:return o=t.sent,t.abrupt("return",{deals:{featured:o.data.featured,latest:o.data.latest},shops:o.data.shops,categories:o.data.categories,seo:{title:o.data.seo.title,tagline:o.data.seo.tagline,separator:o.data.seo.separator,description:o.data.seo.description,favicon:o.data.seo.favicon}});case 5:case"end":return t.stop()}}),t)}))),function(t){return o.apply(this,arguments)}),data:function(){return{dealsSwiper:{slidesPerGroup:4,loopFillGroupWithBlank:!0,slidesPerView:4,spaceBetween:20,breakpoints:{1024:{slidesPerView:4,spaceBetween:40},768:{slidesPerView:3,spaceBetween:30},640:{slidesPerView:2,spaceBetween:20},320:{slidesPerView:1,spaceBetween:10}},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},autoplay:{delay:2500,disableOnInteraction:!0}},shopsSwiper:{slidesPerGroup:4,loopFillGroupWithBlank:!0,slidesPerView:4,spaceBetween:20,breakpoints:{1024:{slidesPerView:4,spaceBetween:40},768:{slidesPerView:3,spaceBetween:30},640:{slidesPerView:2,spaceBetween:20},320:{slidesPerView:1,spaceBetween:10}},navigation:{nextEl:".swiper-button-next",prevEl:".swiper-button-prev"},autoplay:{delay:2500,disableOnInteraction:!0}},slideshow:{pagination:{el:".swiper-pagination"},autoplay:!0},previous:null,isChilds:!1,loading:!1}},head:function(){return{title:this.seo.title,titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.tagline),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"index, follow"},{property:"og:type",content:"article"},{property:"og:title",content:"".concat(this.seo.title," ").concat(this.seo.separator," ").concat(this.seo.tagline)},{property:"og:description",content:this.seo.description},{property:"og:image",content:this.$store.state.settings.logo.white?this.$homeApi("storage/app/".concat(this.$store.state.settings.logo.white)):this.$homeApi("storage/app/uploads/default/settings/logo/white.png")},{property:"og:url",content:this.$home()},{property:"og:site_name",content:this.seo.title},{property:"twitter:title",content:"".concat(this.seo.title," ").concat(this.seo.separator," ").concat(this.seo.tagline)},{property:"twitter:description",content:this.seo.description},{property:"twitter:image",content:this.$store.state.settings.logo.white?this.$homeApi("storage/app/".concat(this.$store.state.settings.logo.white)):this.$homeApi("storage/app/uploads/default/settings/logo/white.png")}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon},{rel:"canonical",href:this.$home()}]}},methods:{categoriesChilds:function(t){var e=this;if(0===t.childs_count)return this.$router.push("/category/"+t.slug);this.loading=!0,this.previous=t,this.$axios.post("ajax/fetch/categories/childs",{id:t.id}).then((function(t){e.isChilds=!0,e.categories=t.data,e.loading=!1})).catch((function(t){console.log(t),e.loading=!1}))},categoriesParents:function(){var t=this;this.loading=!0,this.$axios.post("ajax/fetch/categories").then((function(e){t.isChilds=!1,t.categories=e.data,t.loading=!1})).catch((function(e){console.log(e),t.loading=!1}))},preview:function(t){return 0===t.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+t.unique_id+"/preview_0.jpg")},avatar:function(t){return null===t?this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):this.$homeApi("storage/app/"+t)},cover:function(t){return null===t?this.$homeApi("storage/app/uploads/default/store/no-cover.png"):this.$homeApi("storage/app/"+t)},logo:function(t){return null===t?this.$homeApi("storage/app/uploads/default/store/no-logo.png"):this.$homeApi("storage/app/"+t)}}},c=r(52),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{staticClass:"index-content",attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",[r("v-container",{attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",{attrs:{wrap:""}},[0!==t.deals.featured.length?r("section",{staticStyle:{width:"100%"}},[r("v-flex",{staticClass:"mb-4 text-center",attrs:{xs12:""}},[r("h2",{staticClass:"headline font-weight-black text-uppercase"},[t._v(t._s(t.$t("t_featured_deals")))])]),t._v(" "),r("div",{directives:[{name:"swiper",rawName:"v-swiper:mySwiper",value:t.dealsSwiper,expression:"dealsSwiper",arg:"mySwiper"}]},[r("div",{staticClass:"swiper-wrapper"},t._l(t.deals.featured,(function(e){return r("div",{key:e.id,staticClass:"swiper-slide"},[r("v-flex",{staticClass:"mb-12",attrs:{xs12:""}},[r("v-card",{staticClass:"m-card ad-box",attrs:{flat:""}},[r("v-img",{attrs:{cover:"",height:"230px","lazy-src":t.$homeApi("public/images/default/lazy.jpg"),src:t.preview(e)}},[r("v-container",{staticClass:"pa-0",attrs:{"fill-height":""}},[r("v-layout",{attrs:{"align-center":"","justify-center":""}},[r("nuxt-link",{staticStyle:{position:"absolute",height:"100%",width:"100%"},attrs:{to:"/listing/"+e.unique_slug}})],1)],1)],1),t._v(" "),r("v-card-title",[r("div",{staticClass:"text-xs-left text-truncate"},[r("nuxt-link",{staticClass:"deal-title",attrs:{to:"/listing/"+e.unique_slug}},[t._v(t._s(e.title))])],1)]),t._v(" "),r("v-card-actions",[r("v-list-item",{staticClass:"grow deal-avatar px-2"},[r("v-list-item-avatar",{class:t.$vuetify.rtl?"ml-2":"mr-2",attrs:{color:"white",size:"25px"}},[r("v-img",{attrs:{src:t.avatar(e.user.avatar)}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{staticClass:"deal-username"},[t._v(t._s(e.user.username))])],1),t._v(" "),r("v-layout",{staticStyle:{"margin-bottom":"14px"},attrs:{"align-center":"","justify-end":""}},[e.price&&e.currency&&e.locale?r("span",{staticClass:"deal-price"},[t._v(t._s(t.$getCurrency(e.price,e.currency,e.locale,e.id)))]):t._e()])],1)],1)],1)],1)],1)})),0),t._v(" "),r("div",{staticClass:"swiper-button-prev",attrs:{slot:"button-prev"},slot:"button-prev"},[t.$vuetify.rtl?r("i",{staticClass:"mdi mdi-chevron-right"}):r("i",{staticClass:"mdi mdi-chevron-left"})]),t._v(" "),r("div",{staticClass:"swiper-button-next",attrs:{slot:"button-next"},slot:"button-next"},[t.$vuetify.rtl?r("i",{staticClass:"mdi mdi-chevron-left"}):r("i",{staticClass:"mdi mdi-chevron-right"})])])],1):t._e(),t._v(" "),r("section",{staticStyle:{width:"100%"}},[r("v-flex",{staticClass:"mb-4 text-center",attrs:{xs12:""}},[r("h2",{staticClass:"headline font-weight-black text-uppercase"},[t._v(t._s(t.$t("t_popular_shops")))]),t._v(" "),r("v-btn",{staticClass:"px-5 mt-3",attrs:{rounded:"",depressed:"",dark:"",color:"#24252a",to:"browse/shops"}},[t._v("\n\t\t\t\t\t  \t\t\t"+t._s(t.$t("t_browse_shops"))+"\n\t\t\t\t\t  \t\t")])],1),t._v(" "),r("div",{directives:[{name:"swiper",rawName:"v-swiper:mySwiperr",value:t.shopsSwiper,expression:"shopsSwiper",arg:"mySwiperr"}]},[r("div",{staticClass:"swiper-wrapper"},t._l(t.shops,(function(e){return r("div",{key:e.id,staticClass:"swiper-slide"},[r("v-flex",{staticClass:"mb-12",attrs:{xs12:""}},[r("v-card",{staticClass:"m-card store-card",attrs:{flat:""}},[r("v-img",{staticClass:"text-center",attrs:{"aspect-ratio":16/9,height:"150","lazy-src":t.$homeApi("public/images/default/lazy.jpg"),src:t.cover(e.cover)}},[r("v-container",{staticClass:"pa-0",attrs:{"fill-height":""}},[r("v-layout",{attrs:{"align-center":"","justify-center":""}},[r("nuxt-link",{attrs:{to:"/shop/"+e.store}},[r("v-avatar",{staticClass:"my-4",attrs:{size:"60",color:"grey lighten-4"}},[r("img",{attrs:{src:t.logo(e.logo)}})])],1)],1)],1)],1),t._v(" "),r("div",{staticClass:"text-center px-3 py-3"},[r("div",{staticClass:"pb-2"},[r("nuxt-link",{staticClass:"store-title",attrs:{to:"/shop/"+e.store}},[t._v("\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t"+t._s(e.title)+"\n\t\t\t\t\t\t\t\t\t\t\t    \t")]),t._v(" "),r("div",{staticClass:"tagline"},[t._v(t._s(e.subtitle))])],1),t._v(" "),r("div",{staticClass:"pb-4"}),t._v(" "),r("div",{staticClass:"follow-btn"},[r("nuxt-link",{attrs:{to:"/shop/"+e.store}},[r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[r("v-btn",t._g({attrs:{icon:"",text:"",color:"#c0c0c0",ripple:""}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-chevron-right")])],1)]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_visit_shop",{shop:e.title})))])])],1)],1)])],1)],1)],1)})),0),t._v(" "),r("div",{staticClass:"swiper-button-prev",attrs:{slot:"button-prev"},slot:"button-prev"},[t.$vuetify.rtl?r("i",{staticClass:"mdi mdi-chevron-right"}):r("i",{staticClass:"mdi mdi-chevron-left"})]),t._v(" "),r("div",{staticClass:"swiper-button-next",attrs:{slot:"button-next"},slot:"button-next"},[t.$vuetify.rtl?r("i",{staticClass:"mdi mdi-chevron-left"}):r("i",{staticClass:"mdi mdi-chevron-right"})])])],1),t._v(" "),r("v-flex",{staticClass:"mb-4 text-center",attrs:{xs12:""}},[r("h2",{staticClass:"headline font-weight-black text-uppercase"},[t._v(t._s(t.$t("t_recent_deals")))]),t._v(" "),r("v-btn",{staticClass:"px-5 mt-3",attrs:{rounded:"",depressed:"",dark:"",color:"#24252a",to:"/browse/deals"}},[t._v("\n\t\t\t\t  \t\t\t"+t._s(t.$t("t_browse_deals"))+"\n\t\t\t\t  \t\t")])],1),t._v(" "),t._l(t.deals.latest,(function(e){return r("v-flex",{key:e.unique_id,staticClass:"mb-12",attrs:{xs12:"",sm6:"",md6:"",lg3:""}},[r("v-skeleton-loader",{staticClass:"m-card",attrs:{transition:"fade-transition",type:"card-avatar"}},[r("v-card",{staticClass:"m-card ad-box",class:e.isUpgraded&&"highlight"===e.upgradedTo?"deal-highlight":"",attrs:{flat:""}},[r("v-img",{attrs:{cover:"",height:"230px","lazy-src":t.$homeApi("public/images/default/lazy.jpg"),src:t.preview(e)}},[r("v-container",{staticClass:"pa-0",attrs:{"fill-height":""}},[r("v-layout",{attrs:{"align-center":"","justify-center":""}},[r("nuxt-link",{staticStyle:{position:"absolute",height:"100%",width:"100%"},attrs:{to:"/listing/"+e.unique_slug}})],1)],1)],1),t._v(" "),r("v-card-title",[r("div",{staticClass:"text-xs-left text-truncate"},[e.isUpgraded&&"urgent"===e.upgradedTo?r("span",{staticClass:"deal-urgent"},[t._v(t._s(t.$t("t_urgent")))]):t._e(),t._v(" "),r("nuxt-link",{staticClass:"deal-title",attrs:{to:"/listing/"+e.unique_slug}},[t._v(t._s(e.title))])],1)]),t._v(" "),r("v-card-actions",[r("v-list-item",{staticClass:"grow deal-avatar px-2"},[r("v-list-item-avatar",{class:t.$vuetify.rtl?"ml-2":"mr-2",attrs:{color:"white",size:"25px"}},[r("v-img",{attrs:{src:t.avatar(e.user.avatar)}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{staticClass:"deal-username"},[t._v(t._s(e.user.username))])],1),t._v(" "),r("v-layout",{staticStyle:{"margin-bottom":"14px"},attrs:{"align-center":"","justify-end":""}},[e.price&&e.currency&&e.locale?r("span",{staticClass:"deal-price"},[t._v(t._s(t.$getCurrency(e.price,e.currency,e.locale)))]):t._e()])],1)],1)],1)],1)],1)})),t._v(" "),r("v-flex",{staticClass:"mb-4 text-center",attrs:{xs12:""}},[r("h2",{directives:[{name:"t",rawName:"v-t",value:"t_categories",expression:"'t_categories'"}],staticClass:"headline font-weight-black text-uppercase"}),t._v(" "),t.isChilds?r("v-btn",{staticClass:"px-5 mt-3",attrs:{rounded:"",depressed:"",dark:"",color:"#24252a"},on:{click:t.categoriesParents}},[t.$vuetify.rtl?[r("v-icon",{attrs:{left:"",dark:""}},[t._v("mdi-arrow-right")]),t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_categories"))+"\n\t\t\t\t\t\t\t")]:[r("v-icon",{attrs:{left:"",dark:""}},[t._v("mdi-arrow-left")]),t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_categories"))+"\n\t\t\t\t\t\t\t")]],2):t._e(),t._v(" "),t.isChilds?r("v-btn",{staticClass:"px-5 mt-3",attrs:{rounded:"",depressed:"",dark:"",color:"#24252a",to:"/category/"+t.previous.slug}},[t.$vuetify.rtl?[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_browse_category",{category:t.previous.title}))+"\n\t\t\t\t\t\t\t\t"),r("v-icon",{attrs:{right:"",dark:""}},[t._v("mdi-arrow-left")])]:[t._v("\n\t\t\t\t\t\t\t\t"+t._s(t.$t("t_browse_category",{category:t.previous.title}))+"\n\t\t\t\t\t\t\t\t"),r("v-icon",{attrs:{right:"",dark:""}},[t._v("mdi-arrow-right")])]],2):t._e()],1),t._v(" "),t._l(t.categories,(function(e,o){return r("v-flex",{key:o,attrs:{xs3:""}},[r("v-list",{staticClass:"grey lighten-4",attrs:{"two-line":""}},[r("v-list-item",{on:{click:function(r){return t.categoriesChilds(e)}}},[r("v-list-item-avatar",[e.icon?r("img",{attrs:{src:t.$homeApi("storage/app/"+e.icon)}}):r("v-icon",{staticClass:"grey lighten-1 white--text"},[t._v("mdi-buffer")])],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{staticClass:"category-name"},[t._v(t._s(e.title))]),t._v(" "),r("v-list-item-subtitle",{staticClass:"category-stats"},[t._v(t._s(e.deals_count>0?t.$t("t_deals_counter",{number:e.deals_count}):t.$t("t_deal_counter",{number:e.deals_count})))])],1),t._v(" "),0!==e.childs_count?r("v-list-item-action",[t.$vuetify.rtl?r("v-icon",[t._v("mdi-arrow-left")]):r("v-icon",[t._v("mdi-arrow-right")])],1):t._e()],1)],1)],1)}))],2)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);