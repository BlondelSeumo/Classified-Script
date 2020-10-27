exports.ids=[35],exports.modules={109:function(t,e,r){"use strict";r.r(e);var n=r(91),o=r.n(n);for(var l in n)"default"!==l&&function(t){r.d(e,t,(function(){return n[t]}))}(l);e.default=o.a},159:function(t,e,r){"use strict";r.r(e);var n={layout:"default/moderator",middleware:"moderator",async asyncData({app:t,$axios:e,params:r,redirect:n}){t.$mogate.allow(t.$auth.user,"stats","deals")||n("/moderator");let o=await e.post("moderator/deals/options/statistics",{id:r.id});return{listing:o.data.deal,views:o.data.views,visits:o.data.countries,continents:o.data.continents,dates:o.data.dates}},data:function(){return{statistics:[],statisticsData:{},views:null,loading:!1}},head(){return{title:this.$t("t_deal_statistics"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},computed:{headers(){return[{text:this.$t("t_location"),value:"country",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_referrer"),value:"referrer",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_search_keyword"),value:"search_key",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_last_activity"),value:"last_activity",sortable:!1,align:"center"},{text:this.$t("t_device"),value:"device",sortable:!1,align:"center"},{text:this.$t("t_platform"),value:"platform",sortable:!1,align:"center"},{text:this.$t("t_browser"),value:"browser",sortable:!1,align:"center"}]},poster:function(){return 0==this.listing.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+this.listing.unique_id+"/preview_0.jpg")},avatar:function(){return null===this.listing.user.avatar?this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):this.$homeApi("storage/app/"+this.listing.user.avatar)}},methods:{map:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:o,am4themes_material:l}=this.$am4core();var c=t.create("mapChart",r.MapChart);c.geodata=o,c.projection=new r.projections.Miller;var v=c.series.push(new r.MapPolygonSeries);v.useGeodata=!0;var d=v.mapPolygons.template;c.zoomControl=new r.ZoomControl,v.exclude=["AQ"],d.tooltipText="{value} visits from {name}",d.fill=t.color("#e2e2e2"),d.stroke=t.color("#a9a9a9"),v.heatRules.push({property:"fill",target:v.mapPolygons.template,min:t.color("#737373"),max:t.color("#292929")}),v.data=this.visits},setContinents:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:o,am4themes_material:l}=this.$am4core();var c=t.create("continentsChart",e.PieChart3D);c.hiddenState.properties.opacity=0,c.data=this.continents,c.innerRadius=t.percent(40),c.depth=30,c.legend=new e.Legend,c.legend.position="right";var v=c.series.push(new e.PieSeries3D);v.dataFields.value="value",v.dataFields.depthValue="value",v.dataFields.category="continent",v.slices.template.cornerRadius=5,v.colors.step=3},setVisits:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:o,am4themes_material:l}=this.$am4core();var c=t.create("visitsChart",e.XYChart);let data=new Array;for(const t in this.dates){let e={date:t,value:this.dates[t]};data.push(e)}c.data=data;var v=c.xAxes.push(new e.DateAxis);v.renderer.minGridDistance=40,c.cursor=new e.XYCursor,v.dateFormats.setKey("day","dt"),v.periodChangeDateFormats.setKey("day","dt");c.yAxes.push(new e.ValueAxis);var d=c.series.push(new e.ColumnSeries);d.dataFields.valueY="value",d.dataFields.dateX="date",d.name="Sales"},getMoreStatistics(t=1){this.loading=!0,this.$axios.get("moderator/deals/options/statistics/more/"+this.listing.unique_id+"?page="+t).then(t=>{this.statistics=t.data.data,this.statisticsData=t.data}),this.loading=!1}},mounted(){this.map(),this.setContinents(),this.getMoreStatistics(),this.setVisits()}},o=r(1);var component=Object(o.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card mb-4",attrs:{flat:""}},[r("v-layout",[r("v-flex",{staticClass:"py-0",attrs:{xs3:""}},[r("v-img",{attrs:{src:t.poster,height:"200px",cover:""}})],1),t._v(" "),r("v-flex",{attrs:{xs9:""}},[r("v-card-title",{staticClass:"py-2 px-4",attrs:{"primary-title":""}},[r("div",[r("nuxt-link",{staticClass:"headline black--text",attrs:{to:"/listing/"+t.listing.unique_slug,target:"_blank"}},[t._v(t._s(t.listing.title))]),t._v(" "),r("div",{staticClass:"pt-2 caption",domProps:{innerHTML:t._s(t.listing.description)}})],1)]),t._v(" "),r("v-card-actions",[r("v-list-item",{staticClass:"grow"},[r("v-list-item-avatar",[r("v-img",{attrs:{src:t.avatar}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",[t._v(t._s(t.listing.user.username))])],1),t._v(" "),r("v-layout",{staticClass:"ma-0",attrs:{"align-center":"","justify-end":""}},[r("v-icon",{staticClass:"mr-2",attrs:{size:"20px"}},[t._v("mdi-eye")]),t._v(" "),r("span",{staticClass:"subheading mr-2"},[t._v(t._s(t._f("numeralFormat")(t.views)))])],1)],1)],1)],1)],1)],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pt-3",attrs:{flat:""}},[r("div",{ref:"mapChart",staticClass:"mapDiv",attrs:{id:"mapChart"}})])],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_visits_last_week")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_visits_last_week_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"visitsChart",staticClass:"chartDiv",attrs:{id:"visitsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_continents")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_continents_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"continentsChart",staticClass:"chartDiv",attrs:{id:"continentsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"m-card"},[r("v-toolbar",{attrs:{color:"white",flat:""}},[r("v-toolbar-title",[t._v(t._s(t.$t("t_more_stats")))])],1),t._v(" "),r("v-data-table",{attrs:{headers:t.headers,items:t.statistics,"item-key":"id","hide-default-footer":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.country",fn:function(e){var n=e.item;return[r("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[r("v-list-item",[r("v-list-item-avatar",[r("img",{attrs:{src:t.$homeApi("public/images/flags/large/"+n.countryCode+".png")}})]),t._v(" "),r("v-list-item-content",{staticClass:"table-wrap-title"},[r("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(n.countryName)}}),t._v(" "),r("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[t._v(t._s(n.state)+" - "+t._s(n.city)+" - "),r("b",[t._v(t._s(n.continent))])])],1)],1)],1)]}},{key:"item.referrer",fn:function(e){var n=e.item;return[r("a",{staticClass:"table-wrap-title",attrs:{href:n.referrer,target:"_blank"}},[t._v(t._s(n.referrer))]),t._v(" "),r("small",{staticClass:"pb-1 font-weight-regular text-uppercase d-block"},[t._v(t._s(n.referrerDomain))])]}},{key:"item.search_key",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t"+t._s(r.searchEngineKeyword?r.searchEngineKeyword:t.$t("t_n_a"))+"\n\t\t\t\t\t\t\t")]}},{key:"item.last_activity",fn:function(e){var r=e.item;return[t._v(t._s(t.$ago(r.last_visit)))]}},{key:"item.device",fn:function(e){var n=e.item;return[r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isPhone&&!n.isTablet?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-cellphone")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.deviceName))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isDesktop?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-desktop-mac")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_desktop")))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isTablet?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-tablet-android")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.deviceName))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isRobot?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-robot")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.robotName))])])]}},{key:"item.platform",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t"+t._s(r.platformName)+" "+t._s(r.platformVersion)+"\n\t\t\t\t\t\t\t")]}},{key:"item.browser",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t"+t._s(r.browserName)+" "+t._s(r.browserVersion)+"\n\t\t\t\t\t\t\t")]}}])})],1),t._v(" "),r("div",{staticClass:"text-center pt-2"},[r("pagination",{attrs:{data:t.statisticsData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getMoreStatistics}},[r("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),r("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)],1)],1)],1)}),[],!1,(function(t){var e=r(109);e.__inject__&&e.__inject__(t)}),"90a9b99c","258a3819");e.default=component.exports},91:function(t,e){}};