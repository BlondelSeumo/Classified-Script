exports.ids=[33],exports.modules={114:function(t,e,r){"use strict";r.r(e);var n=r(96),l=r.n(n);for(var o in n)"default"!==o&&function(t){r.d(e,t,(function(){return n[t]}))}(o);e.default=l.a},266:function(t,e,r){"use strict";r.r(e);var n={layout:"default/manager",middleware:"auth",async asyncData({$axios:t,params:e}){let r=await t.post("manager/deals/options/statistics",{id:e.id});return{listing:r.data.deal,views:r.data.views,visits:r.data.countries,continents:r.data.continents,devices:r.data.devices,platforms:r.data.platforms,browsers:r.data.browsers,dates:r.data.dates}},data:function(){return{statistics:[],statisticsData:{},views:null,loading:!1}},head(){return{title:this.$t("t_deal_statistics"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},computed:{poster:function(){return 0==this.listing.photosNumber?this.$homeApi("storage/app/uploads/default/classifieds/no-image.png"):this.$homeApi("storage/app/uploads/classifieds/"+this.listing.unique_id+"/preview_0.jpg")},avatar:function(){return null===this.listing.user.avatar?this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):this.$homeApi("storage/app/"+this.listing.user.avatar)},headers(){return[{text:this.$t("t_location"),value:"country",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_referrer"),value:"referrer",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_search_keyword"),value:"keyword",sortable:!1,align:this.$vuetify.rtl?"right":"left"},{text:this.$t("t_last_activity"),value:"activity",sortable:!1,align:"center"},{text:this.$t("t_device"),value:"device",sortable:!1,align:"center"},{text:this.$t("t_platform"),value:"platform",sortable:!1,align:"center"},{text:this.$t("t_browser"),value:"browser",sortable:!1,align:"center"}]}},methods:{map:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:l,am4themes_material:o}=this.$am4core();var c=t.create("mapChart",r.MapChart);c.geodata=l,c.projection=new r.projections.Miller;var d=c.series.push(new r.MapPolygonSeries);d.useGeodata=!0;var v=d.mapPolygons.template;c.zoomControl=new r.ZoomControl,d.exclude=["AQ"],v.tooltipText="{value} visits from {name}",v.fill=t.color("#e2e2e2"),v.stroke=t.color("#a9a9a9"),d.heatRules.push({property:"fill",target:d.mapPolygons.template,min:t.color("#737373"),max:t.color("#292929")}),d.data=this.visits},setContinents:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:l,am4themes_material:o}=this.$am4core();var c=t.create("continentsChart",e.PieChart3D);c.hiddenState.properties.opacity=0,c.data=this.continents,c.innerRadius=t.percent(40),c.depth=30,c.legend=new e.Legend,c.legend.position="right";var d=c.series.push(new e.PieSeries3D);d.dataFields.value="value",d.dataFields.depthValue="value",d.dataFields.category="continent",d.slices.template.cornerRadius=5,d.colors.step=3},setDevices:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:l,am4themes_material:o}=this.$am4core();var c=t.create("devicesChart",e.PieChart3D);c.hiddenState.properties.opacity=0,c.data=this.devices,c.innerRadius=t.percent(40),c.depth=30,c.legend=new e.Legend,c.legend.position="right";var d=c.series.push(new e.PieSeries3D);d.dataFields.value="value",d.dataFields.depthValue="value",d.dataFields.category="device",d.slices.template.cornerRadius=5,d.colors.step=3},setPlatforms:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:l,am4themes_material:o}=this.$am4core();var c=t.create("platformsChart",e.PieChart3D);c.hiddenState.properties.opacity=0,c.data=this.platforms,c.innerRadius=t.percent(40),c.depth=30,c.legend=new e.Legend,c.legend.position="right";var d=c.series.push(new e.PieSeries3D);d.dataFields.value="value",d.dataFields.depthValue="value",d.dataFields.category="platform",d.slices.template.cornerRadius=5,d.colors.step=3},setBrowsers:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:l,am4themes_material:o}=this.$am4core();var c=t.create("browsersChart",e.PieChart3D);c.hiddenState.properties.opacity=0,c.data=this.browsers,c.innerRadius=t.percent(40),c.depth=30,c.legend=new e.Legend,c.legend.position="right";var d=c.series.push(new e.PieSeries3D);d.dataFields.value="value",d.dataFields.depthValue="value",d.dataFields.category="browser",d.slices.template.cornerRadius=5,d.colors.step=3},setVisits:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:n,am4geodata_worldLow:l,am4themes_material:o}=this.$am4core();var c=t.create("visitsChart",e.XYChart);let data=new Array;for(const t in this.dates){let e={date:t,value:this.dates[t]};data.push(e)}c.data=data;var d=c.xAxes.push(new e.DateAxis);d.renderer.minGridDistance=40,c.cursor=new e.XYCursor,d.dateFormats.setKey("day","dt"),d.periodChangeDateFormats.setKey("day","dt");c.yAxes.push(new e.ValueAxis);var v=c.series.push(new e.ColumnSeries);v.dataFields.valueY="value",v.dataFields.dateX="date",v.name="Sales"},getMoreStatistics(t=1){this.loading=!0,this.$axios.get("manager/deals/options/statistics/more/"+this.listing.unique_id+"?page="+t).then(t=>{this.statistics=t.data.data,this.statisticsData=t.data}),this.loading=!1}},mounted(){this.map(),this.setContinents(),this.setDevices(),this.setPlatforms(),this.setBrowsers(),this.getMoreStatistics(),this.setVisits()}},l=r(1);var component=Object(l.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-dialog",{attrs:{persistent:"","elevation-0":"",width:"100","content-class":"elevation-0"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-card",{attrs:{flat:""}},[r("v-card-text",{staticClass:"pa-4 text-center"},[r("v-progress-circular",{attrs:{size:45,width:"2",color:"light-blue",indeterminate:""}})],1)],1)],1),t._v(" "),r("v-container",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card",class:t.listing.isUpgraded&&"highlight"===t.listing.upgradedTo?"deal-highlight":""},[r("nuxt-link",{staticStyle:{position:"absolute",height:"100%",width:"100%"},attrs:{to:"/listing/"+t.listing.unique_slug}}),t._v(" "),r("v-container",{attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",[r("v-flex",{attrs:{xs4:""}},[r("v-img",{attrs:{src:t.poster,height:"80px",contain:""},on:{click:function(e){return t.$router.push("/listing/"+t.listing.unique_slug)}}})],1),t._v(" "),r("v-flex",{staticClass:"pr-4 pl-0",attrs:{xs8:""}},[r("v-card-title",{staticClass:"pa-0",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"deal-title"},[t.listing.isUpgraded&&"urgent"===t.listing.upgradedTo?r("span",{staticClass:"deal-urgent"},[t._v(t._s(t.$t("t_urgent")))]):t._e(),t._v(" "+t._s(t.listing.title))]),t._v(" "),r("div",{staticClass:"deal-description"},[t._v(t._s(t.$description(t.listing.description)))])])])],1)],1)],1),t._v(" "),r("v-divider",{attrs:{light:""}}),t._v(" "),r("v-card-actions",{staticClass:"py-0 px-2"},[r("v-list-item",{staticClass:"grow deal-avatar"},[r("v-list-item-avatar",{attrs:{size:"25px"}},[r("v-img",{attrs:{src:t.avatar}})],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{staticClass:"deal-username"},[t._v(t._s(t.listing.user.username))])],1),t._v(" "),r("v-layout",{staticStyle:{"margin-bottom":"14px"},attrs:{"align-center":"","justify-end":""}},[t.listing.price&&t.listing.currency&&t.listing.locale?r("span",{staticClass:"deal-price"},[t._v(t._s(t.$getCurrency(t.listing.price,t.listing.currency,t.listing.locale)))]):t._e()])],1)],1)],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pt-3",attrs:{flat:""}},[r("div",{ref:"mapChart",staticClass:"mapDiv",attrs:{id:"mapChart"}})])],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_visits_last_week")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_visits_last_week_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"visitsChart",staticClass:"chartDiv",attrs:{id:"visitsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_continents")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_continents_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"continentsChart",staticClass:"chartDiv",attrs:{id:"continentsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_devices")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_devices_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"devicesChart",staticClass:"chartDiv",attrs:{id:"devicesChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_platforms")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_platforms_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"platformsChart",staticClass:"chartDiv",attrs:{id:"platformsChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_browsers")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_browsers_stats_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"browsersChart",staticClass:"chartDiv",attrs:{id:"browsersChart"}})])],1)],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"m-card"},[r("v-toolbar",{attrs:{flat:""}},[r("v-toolbar-title",[t._v(t._s(t.$t("t_more_stats")))])],1),t._v(" "),r("v-data-table",{attrs:{headers:t.headers,items:t.statistics,"item-key":"id","disable-pagination":"","hide-default-footer":"","mobile-breakpoint":1,"no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.country",fn:function(e){var n=e.item;return[r("v-list",{staticClass:"transparent",attrs:{"two-line":""}},[r("v-list-item",[r("v-list-item-avatar",[r("img",{attrs:{src:t.$homeApi("public/images/flags/large/"+n.countryCode+".png")}})]),t._v(" "),r("v-list-item-content",{staticClass:"table-wrap-title"},[r("v-list-item-title",{staticClass:"table-wrap-title",domProps:{innerHTML:t._s(n.countryName)}}),t._v(" "),r("v-list-item-subtitle",{staticClass:"pb-1 font-weight-regular d-block caption"},[t._v(t._s(n.state)+" - "+t._s(n.city)+" - "),r("b",[t._v(t._s(n.continent))])])],1)],1)],1)]}},{key:"item.referrer",fn:function(e){var n=e.item;return[r("a",{staticClass:"table-wrap-title",attrs:{href:n.referrer,target:"_blank"}},[t._v(t._s(n.referrer))]),t._v(" "),r("small",{staticClass:"pb-1 font-weight-regular text-uppercase d-block"},[t._v(t._s(n.referrerDomain))])]}},{key:"item.keyword",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(r.searchEngineKeyword?r.searchEngineKeyword:t.$t("t_n_a"))+"\n\t\t\t\t\t\t\t\t")]}},{key:"item.activity",fn:function(e){var r=e.item;return[t._v(t._s(t.$ago(r.last_visit)))]}},{key:"item.device",fn:function(e){var n=e.item;return[r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n.isPhone&&!n.isTablet?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},l),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-cellphone")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.deviceName))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n.isDesktop?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},l),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-desktop-mac")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(t.$t("t_desktop")))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n.isTablet?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},l),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-tablet-android")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.deviceName))])]),t._v(" "),r("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var l=e.on;return[n.isRobot?r("v-btn",t._g({attrs:{text:"",icon:"",color:"#7f8c8d"}},l),[r("v-icon",{attrs:{size:"20px"}},[t._v("mdi-robot")])],1):t._e()]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(n.robotName))])])]}},{key:"item.platform",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(r.platformName)+" "+t._s(r.platformVersion)+"\n\t\t\t\t\t\t\t\t")]}},{key:"item.browser",fn:function(e){var r=e.item;return[t._v("\n\t\t\t\t\t\t\t\t\t"+t._s(r.browserName)+" "+t._s(r.browserVersion)+"\n\t\t\t\t\t\t\t\t")]}}])})],1),t._v(" "),r("div",{staticClass:"text-center pt-2"},[r("pagination",{attrs:{data:t.statisticsData,limit:8},on:{"pagination-change-page":t.getMoreStatistics}},[r("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v("chevron_left")])]),t._v(" "),r("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[r("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v("chevron_right")])])])],1)])],1)],1)],1)],1)}),[],!1,(function(t){var e=r(114);e.__inject__&&e.__inject__(t)}),"65a6cdb9","b06c677a");e.default=component.exports},96:function(t,e){}};