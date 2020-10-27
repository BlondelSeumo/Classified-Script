exports.ids=[5],exports.modules={153:function(t,e,r){"use strict";r.r(e);var o={middleware:"auth",layout:"default/main",components:{"v-sidebar":r(73).a},async asyncData({$axios:t,params:e}){let r=await t.post("account/deals/options/statistics",{id:e.id});return{listing:r.data.deal,dates:r.data.dates,seo:{title:r.data.seo.title,separator:r.data.seo.separator,description:r.data.seo.description,favicon:r.data.seo.favicon}}},data:function(){return{loading:!1}},head(){return{title:this.$t("t_deal_statistics"),titleTemplate:`%s ${this.seo.separator} ${this.seo.title}`,meta:[{name:"description",content:this.seo.description},{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},methods:{setMap:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:o,am4geodata_worldLow:n,am4themes_material:l}=this.$am4core();var c=t.create("mapChart",r.MapChart);c.geodata=n,c.projection=new r.projections.Miller;var d=c.series.push(new r.MapPolygonSeries);d.useGeodata=!0;var m=d.mapPolygons.template;c.zoomControl=new r.ZoomControl,d.exclude=["AQ"],m.tooltipText="{value} visits from {name}",m.fill=t.color("#e2e2e2"),m.stroke=t.color("#a9a9a9"),d.heatRules.push({property:"fill",target:d.mapPolygons.template,min:t.color("#737373"),max:t.color("#292929")}),d.data=this.visits},setVisits:function(){let{am4core:t,am4charts:e,am4maps:r,am4themes_animated:o,am4geodata_worldLow:n,am4themes_material:l}=this.$am4core();var c=t.create("visitsChart",e.XYChart);let data=new Array;for(const t in this.dates){let e={date:t,value:this.dates[t]};data.push(e)}c.data=data;var d=c.xAxes.push(new e.DateAxis);d.renderer.minGridDistance=40,c.cursor=new e.XYCursor,d.dateFormats.setKey("day","dt"),d.periodChangeDateFormats.setKey("day","dt");c.yAxes.push(new e.ValueAxis);var m=c.series.push(new e.ColumnSeries);m.dataFields.valueY="value",m.dataFields.dateX="date",m.name="Sales"}},mounted(){this.setMap(),this.setVisits()}},n=r(1);var component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:"","fill-height":""}},[r("v-sidebar"),t._v(" "),r("v-flex",{attrs:{xs9:""}},[r("v-container",{staticClass:"pa-0",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pt-3",attrs:{flat:""}},[r("div",{ref:"mapChart",staticClass:"mapDiv",attrs:{id:"mapChart"}})])],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card pa-3",attrs:{flat:""}},[r("v-card-title",{attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_visits_last_week")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_visits_last_week_desc")))])])]),t._v(" "),r("v-card-text",[r("div",{ref:"visitsChart",staticClass:"chartDiv",attrs:{id:"visitsChart"}})])],1)],1)],1)],1)],1)],1)],1)],1)],1)}),[],!1,(function(t){var e=r(98);e.__inject__&&e.__inject__(t)}),"4de23e52","02008668");e.default=component.exports},69:function(t,e){},70:function(t,e){},71:function(t,e,r){"use strict";r.r(e);var o=r(69),n=r.n(o);for(var l in o)"default"!==l&&function(t){r.d(e,t,(function(){return o[t]}))}(l);e.default=n.a},72:function(t,e,r){"use strict";r.r(e);var o=r(70),n=r.n(o);for(var l in o)"default"!==l&&function(t){r.d(e,t,(function(){return o[t]}))}(l);e.default=n.a},73:function(t,e,r){"use strict";var o={name:"sidebar",middleware:"auth",layout:"default/main",data:function(){return{items:[{icon:"mdi-settings",title:"t_account_settings",to:"/account/settings",enabled:!0},{icon:"mdi-image-multiple",title:"t_my_deals",to:"/account/deals",enabled:!0},{icon:"mdi-message",title:"t_message_center",to:"/account/messages",enabled:this.$megate.allow(this.$auth.user,"receive","messages")},{icon:"mdi-tag",title:"t_received_offers",to:"/account/offers",enabled:this.$megate.allow(this.$auth.user,"receive","offers")},{icon:"mdi-folder-search",title:"t_saved_search",to:"/account/search",enabled:this.$megate.allow(this.$auth.user,"save","search")},{icon:"mdi-store",title:"t_following_shops",to:"/account/following",enabled:this.$megate.allow(this.$auth.user,"follow","shops")},{icon:"mdi-wallet-membership",title:"t_membership",to:"/account/subscription",enabled:!0},{divider:!0},{icon:"mdi-two-factor-authentication",title:"t_two_factor_auth",to:"/account/2fa",enabled:!0}]}},methods:{async logout(){await this.$auth.logout(),this.$router.push({name:"mainIndex"})}}},n=r(1);var component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-flex",{staticClass:"user-area",attrs:{xs3:""}},[r("v-navigation-drawer",{staticClass:"m-card pb-3",staticStyle:{height:"auto"},attrs:{width:"auto"}},[r("v-list",{staticClass:"user-sidebar",attrs:{dense:""}},[t._l(t.items,(function(e,o){return[e.divider?r("v-divider",{key:e.to}):t._e(),t._v(" "),e.divider?t._e():r("nuxt-link",{key:o,attrs:{to:e.to,tag:"v-list-item","active-class":"active-item"}},[r("v-list-item",[r("v-list-item-action",[r("v-icon",{staticClass:"v-icon theme--light mdi",class:e.icon})],1),t._v(" "),r("v-list-item-title",{directives:[{name:"t",rawName:"v-t",value:e.title,expression:"item.title"}]})],1)],1)]})),t._v(" "),r("v-list-item",{staticClass:"user-signout",on:{click:function(e){return e.preventDefault(),t.logout()}}},[r("v-list-item-action",[r("v-icon",{staticClass:"v-icon theme--light mdi mdi-logout"})],1),t._v(" "),r("v-list-item-title",[t._v(t._s(t.$t("t_sign_out")))])],1)],2)],1)],1)}),[],!1,(function(t){var e=r(71);e.__inject__&&e.__inject__(t);var o=r(72);o.__inject__&&o.__inject__(t)}),"7a304184","49e3b5b1");e.a=component.exports},80:function(t,e){},98:function(t,e,r){"use strict";r.r(e);var o=r(80),n=r.n(o);for(var l in o)"default"!==l&&function(t){r.d(e,t,(function(){return o[t]}))}(l);e.default=n.a}};