(window.webpackJsonp=window.webpackJsonp||[]).push([[26],{527:function(t,e,r){var n=r(56);n(n.P,"Array",{fill:r(528)}),r(206)("fill")},528:function(t,e,r){"use strict";var n=r(121),o=r(412),l=r(109);t.exports=function(t){for(var e=n(this),r=l(e.length),c=arguments.length,d=o(c>1?arguments[1]:void 0,r),v=c>2?arguments[2]:void 0,m=void 0===v?r:o(v,r);m>d;)e[d++]=t;return e}},557:function(t,e,r){},579:function(t,e,r){"use strict";var n=r(557);r.n(n).a},606:function(t,e,r){"use strict";r.r(e);r(84),r(527),r(45);var n,o=r(26),l={layout:"default/manager",middleware:"auth",asyncData:(n=Object(o.a)(regeneratorRuntime.mark((function t(e){var r,n,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return r=e.$axios,n=e.params,t.next=3,r.post("manager/deals/options/statistics",{id:n.id});case 3:return o=t.sent,t.abrupt("return",{listing:o.data.deal,views:o.data.views,visits:o.data.countries,continents:o.data.continents,devices:o.data.devices,platforms:o.data.platforms,browsers:o.data.browsers,dates:o.data.dates});case 5:case"end":return t.stop()}}),t)}))),function(t){return n.apply(this,arguments)}),data:function(){return{statistics:[],statisticsData:{},views:null,loading:!1}},head:function(){return{title:this.$t("t_deal_statistics"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},computed:{poster:function(){return 0==this.listing.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+this.listing.unique_id+"/preview_0.jpg")},avatar:function(){return null===this.listing.user.avatar?this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):this.$homeApi("storage/app/"+this.listing.user.avatar)},headers:function(){return[{text:this.$t("t_location"),value:"country",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_referrer"),value:"referrer",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_search_keyword"),value:"keyword",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_last_activity"),value:"activity",sortable:!1,align:"center"},{text:this.$t("t_device"),value:"device",sortable:!1,align:"center"},{text:this.$t("t_platform"),value:"platform",sortable:!1,align:"center"},{text:this.$t("t_browser"),value:"browser",sortable:!1,align:"center"}]}},methods:{map:function(){var t=this.$am4core(),e=t.am4core,r=(t.am4charts,t.am4maps),n=(t.am4themes_animated,t.am4geodata_worldLow),o=(t.am4themes_material,e.create("mapChart",r.MapChart));o.geodata=n,o.projection=new r.projections.Miller;var l=o.series.push(new r.MapPolygonSeries);l.useGeodata=!0;var c=l.mapPolygons.template;o.zoomControl=new r.ZoomControl,l.exclude=["AQ"],c.tooltipText="{value} visits from {name}",c.fill=e.color("#e2e2e2"),c.stroke=e.color("#a9a9a9"),l.heatRules.push({property:"fill",target:l.mapPolygons.template,min:e.color("#737373"),max:e.color("#292929")}),l.data=this.visits},setContinents:function(){var t=this.$am4core(),e=t.am4core,r=t.am4charts,n=(t.am4maps,t.am4themes_animated,t.am4geodata_worldLow,t.am4themes_material,e.create("continentsChart",r.PieChart3D));n.hiddenState.properties.opacity=0,n.data=this.continents,n.innerRadius=e.percent(40),n.depth=30,n.legend=new r.Legend,n.legend.position="right";var o=n.series.push(new r.PieSeries3D);o.dataFields.value="value",o.dataFields.depthValue="value",o.dataFields.category="continent",o.slices.template.cornerRadius=5,o.colors.step=3},setDevices:function(){var t=this.$am4core(),e=t.am4core,r=t.am4charts,n=(t.am4maps,t.am4themes_animated,t.am4geodata_worldLow,t.am4themes_material,e.create("devicesChart",r.PieChart3D));n.hiddenState.properties.opacity=0,n.data=this.devices,n.innerRadius=e.percent(40),n.depth=30,n.legend=new r.Legend,n.legend.position="right";var o=n.series.push(new r.PieSeries3D);o.dataFields.value="value",o.dataFields.depthValue="value",o.dataFields.category="device",o.slices.template.cornerRadius=5,o.colors.step=3},setPlatforms:function(){var t=this.$am4core(),e=t.am4core,r=t.am4charts,n=(t.am4maps,t.am4themes_animated,t.am4geodata_worldLow,t.am4themes_material,e.create("platformsChart",r.PieChart3D));n.hiddenState.properties.opacity=0,n.data=this.platforms,n.innerRadius=e.percent(40),n.depth=30,n.legend=new r.Legend,n.legend.position="right";var o=n.series.push(new r.PieSeries3D);o.dataFields.value="value",o.dataFields.depthValue="value",o.dataFields.category="platform",o.slices.template.cornerRadius=5,o.colors.step=3},setBrowsers:function(){var t=this.$am4core(),e=t.am4core,r=t.am4charts,n=(t.am4maps,t.am4themes_animated,t.am4geodata_worldLow,t.am4themes_material,e.create("browsersChart",r.PieChart3D));n.hiddenState.properties.opacity=0,n.data=this.browsers,n.innerRadius=e.percent(40),n.depth=30,n.legend=new r.Legend,n.legend.position="right";var o=n.series.push(new r.PieSeries3D);o.dataFields.value="value",o.dataFields.depthValue="value",o.dataFields.category="browser",o.slices.template.cornerRadius=5,o.colors.step=3},setVisits:function(){var t=this.$am4core(),e=t.am4core,r=t.am4charts,n=(t.am4maps,t.am4themes_animated,t.am4geodata_worldLow,t.am4themes_material,e.create("visitsChart",r.XYChart)),data=new Array;for(var o in this.dates){var l={date:o,value:this.dates[o]};data.push(l)}n.data=data;var c=n.xAxes.push(new r.DateAxis);c.renderer.minGridDistance=40,n.cursor=new r.XYCursor,c.dateFormats.setKey("day","dt"),c.periodChangeDateFormats.setKey("day","dt");n.yAxes.push(new r.ValueAxis);var d=n.series.push(new r.ColumnSeries);d.dataFields.valueY="value",d.dataFields.dateX="date",d.name="Sales"},getMoreStatistics:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.loading=!0,this.$axios.get("manager/deals/options/statistics/more/"+this.listing.unique_id+"?page="+e).then((function(e){t.statistics=e.data.data,t.statisticsData=e.data})),this.loading=!1}},mounted:function(){this.map(),this.setContinents(),this.setDevices(),this.setPlatforms(),this.setBrowsers(),this.getMoreStatistics(),this.setVisits()}},c=(r(579),r(52)),component=Object(c.a)(l,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-dialog",{attrs:{persistent:"","elevation-0":"",width:"100","content-class":"elevation-0"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-card",{attrs:{flat:""}},[r("v-card-text",{staticClass:"pa-4 text-center"},[r("v-progress-circular",{attrs:{size:45,width:"2",color:"light-blue",indeterminate:""}})],1)],1)],1),t._v(" "),r("v-container",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card",class:t.listing.isUpgraded&&"highlight"===t.listing.upgradedTo?"deal-highlight":""},[r("nuxt-link",{staticStyle:{position:"absolute",height:"100%",width:"100%"},attrs:{to:"/listing/"+t.listing.unique_slug}}),t._v(" "),r("v-container",{attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",[r("v-flex",{attrs:{xs4:""}},[r("v-img",{attrs:{src:t.poster,height:"80px",contain:""},on:{click:function(e){return t.$router.push("/listing/"+t.listing.unique_slug)}}})],1),t._v(" "),r("v-flex",{staticClass:"pr-4 pl-0",attrs:{xs8:""}},[r("v-card-title",{staticClass:"pa-0",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"deal-title"},[t.listing.isUpgraded&&"urgent"===t.listing.upgradedTo?r("span",{staticClass:"deal-urgent"},[t._v(t._s(t.$t("t_urgent")))]):t._e(),t._v(" "+t._s(t.listing.title))]),t._v(" "),r("div",{staticClass:"deal-description"},[t._v(t._s(t.$description(t.listing.description)))])])])],1)],1)],1),t._v(" "),r("v-divider",{attrs:{light:""}}),t._v(" "),r("v-card-actions",{staticClass:"py-0 px-2"},[r("v-list-item",{staticClass:"grow deal-avatar"},[r("v-list-item-avatar",{attrs:{size:"25px"}},[r("v-img",{attrs:{src:t.avatar}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{staticClass:"deal-username"},[t._v(t._s(t.listing.user.username))])],1),t._v(" "),r("v-layout",{staticStyle:{"margin-bottom":"14px"},attrs:{"align-center":"","justify-end":""}},[t.listing.price&&t.listing.currency&&t.listing.locale?r("span",{staticClass:"deal-price"},[t._v(t._s(t.$getCurrency(t.listing.price,t.listing.currency,t.listing.locale)))]):t._e()])],1)],1)],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pt-3",attrs:{flat:""}},[r("div",{ref:"mapChart",staticClass:"mapDiv",attrs:{id:"mapChart"}})])],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_visits_last_week")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_visits_last_week_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"visitsChart",staticClass:"chartDiv",attrs:{id:"visitsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_continents")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_continents_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"continentsChart",staticClass:"chartDiv",attrs:{id:"continentsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_devices")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_devices_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"devicesChart",staticClass:"chartDiv",attrs:{id:"devicesChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_platforms")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_platforms_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"platformsChart",staticClass:"chartDiv",attrs:{id:"platformsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_browsers")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_browsers_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"browsersChart",staticClass:"chartDiv",attrs:{id:"browsersChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"m-card"},[r("v-toolbar",{attrs:{flat:""}},[r("v-toolbar-title",[t._v(t._s(t.$t("t_more_stats")))])],1),t._v(" "),r("v-data-table",{attrs:{headers:t.headers,items:t.statistics,"item-key":"id","disable-pagination":"","hide-default-footer":"","mobile-breakpoint":1,"no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.country",fn:function(e){var n=e.item;return[r("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[r("v-list-item",[r("v-list-item-avatar",[r("img",{attrs:{src:t.$homeApi("public/images/flags/large/"+n.countryCode+".png")}})]),t._v(" "),r("v-list-item-content",{staticClass:"table-wrap-title"},[r("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(n.countryName)}}),t._v(" "),r("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[t._v(t._s(n.state)+" - "+t._s(n.city)+" - "),r("b",[t._v(t._s(n.continent))])])],1)],1)],1)]}},{key:"item.referrer",fn:function(e){var n=e.item;return[r("a",{staticClass:"table-wrap-title",attrs:{href:n.referrer,target:"_blank"}},[t._v(t._s(n.referrer))]),t._v(" "),r("small",{staticClass:"pb-1 font-weight-regular text-uppercase d-block"},[t._v(t._s(n.referrerDomain))])]}},{key:"item.keyword",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(r.searchEngineKeyword?r.searchEngineKeyword:t.$t("t_n_a"))+"\n\t\t\t\t\t\t\t\t")]}},{key:"item.activity",fn:function(e){var r=e.item;return[t._v(t._s(t.$ago(r.last_visit)))]}},{key:"item.device",fn:function(e){var n=e.item;return[r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isPhone&&!n.isTablet?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-cellphone")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.deviceName))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isDesktop?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-desktop-mac")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_desktop")))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isTablet?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-tablet-android")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.deviceName))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var o=e.on;return[n.isRobot?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},o),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-robot")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.robotName))])])]}},{key:"item.platform",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(r.platformName)+" "+t._s(r.platformVersion)+"\n\t\t\t\t\t\t\t\t")]}},{key:"item.browser",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(r.browserName)+" "+t._s(r.browserVersion)+"\n\t\t\t\t\t\t\t\t")]}}])})],1),t._v(" "),r("div",{staticClass:"text-center pt-2"},[r("pagination",{attrs:{data:t.statisticsData,limit:8},on:{"pagination-change-page":t.getMoreStatistics}},[r("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v("chevron_left")])]),t._v(" "),r("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v("chevron_right")])])])],1)])],1)],1)],1)],1)}),[],!1,null,"65a6cdb9",null);e.default=component.exports}}]);