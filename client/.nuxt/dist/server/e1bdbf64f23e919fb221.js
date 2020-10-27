exports.ids=[61],exports.modules={247:function(t,e,o){"use strict";o.r(e);var n={layout:"default/admin",middleware:"administrator",async asyncData({app:t,$axios:e,redirect:o}){t.$owgate.allow(t.$auth.user,"access","geo")||o("/administrator");let n=await e.get("administrator/geolocation/countries");return{countriesData:n.data,countries:n.data.data}},data:function(){return{headers:[{text:this.$t("t_flag"),value:"flag",sortable:!1,align:"center"},{text:this.$t("t_continent"),value:"continent",sortable:!1,align:"center"},{text:this.$t("t_country"),value:"country",sortable:!1,align:"center"},{text:this.$t("t_capital"),value:"capital",sortable:!1,align:"center"},{text:this.$t("t_calling_code"),value:"phone",sortable:!1,align:"center"},{text:this.$t("t_states"),value:"states",sortable:!1,align:"center"},{text:this.$t("t_options"),value:"options",sortable:!1,align:"center"}],edit:{country:{id:"",name:"",capital:"",has_division:!1},index:null,dialog:!1},errors:[],loading:!1}},head(){return{title:this.$t("t_countries"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{getNextPage(t=1){this.loading=!0,this.$axios.get("administrator/geolocation/countries?page="+t).then(t=>{this.selected=[],this.countriesData=t.data,this.countries=t.data.data,void 0!==typeof window&&window.scrollTo(0,0),this.loading=!1}).catch(t=>{console.log(t),this.loading=!1})},change:function(t,e){this.edit.country.id=t.id,this.edit.country.name=t.name,this.edit.country.capital=t.capital,this.edit.country.has_division=t.has_division,this.edit.index=e,this.edit.dialog=!0},update:function(){this.$owgate.allow(this.$auth.user,"edit","geo")?(this.loading=!0,this.$axios.post("administrator/geolocation/countries/options/edit",{id:this.edit.country.id,name:this.edit.country.name,capital:this.edit.country.capital,has_division:this.edit.country.has_division}).then(t=>{this.countries[this.edit.index].name=t.data.name,this.countries[this.edit.index].capital=t.data.capital,this.countries[this.edit.index].has_division=t.data.has_division,this.edit.dialog=!1,this.edit.country.name="",this.edit.country.capital="",this.edit.country.has_division=!1,this.edit.country.index=null,this.$toasted.show(this.$t("t_toasted_country_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")}}},r=o(1),component=Object(r.a)(n,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-app",{attrs:{id:"inspire"}},[o("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[o("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),t.$owgate.allow(t.$auth.user,"edit","geo")?o("v-dialog",{attrs:{"max-width":"500px",persistent:""},model:{value:t.edit.dialog,callback:function(e){t.$set(t.edit,"dialog",e)},expression:"edit.dialog"}},[o("v-card",{staticClass:"pa-2"},[o("v-card-title",{staticClass:"pt-1",attrs:{"primary-title":""}},[o("h3",{staticClass:"body-2 mb-0 text-uppercase font-weight-black"},[t._v(t._s(t.$t("t_edit_country")))]),t._v(" "),o("v-spacer"),t._v(" "),o("v-btn",{attrs:{icon:""},on:{click:function(e){t.edit.dialog=!1}}},[o("v-icon",[t._v("mdi-close")])],1)],1),t._v(" "),o("v-card-text",[o("v-container",{staticClass:"pa-0",attrs:{"grid-list-md":""}},[o("v-layout",{attrs:{wrap:""}},[o("v-flex",{attrs:{xs12:""}},[o("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_country_name"),placeholder:t.$t("t_enter_country_name"),counter:"60",maxlength:"60",rules:t.errors.name,error:!!t.errors.name},model:{value:t.edit.country.name,callback:function(e){t.$set(t.edit.country,"name",e)},expression:"edit.country.name"}})],1),t._v(" "),o("v-flex",{attrs:{xs12:""}},[o("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_capital"),placeholder:t.$t("t_enter_capital"),counter:"60",maxlength:"60",rules:t.errors.capital,error:!!t.errors.capital},model:{value:t.edit.country.capital,callback:function(e){t.$set(t.edit.country,"capital",e)},expression:"edit.country.capital"}})],1),t._v(" "),o("v-flex",{attrs:{xs12:""}},[o("label",{staticClass:"form-group has-float-label",class:[t.errors.has_division?"has-danger":""]},[o("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:t.edit.country.has_division,callback:function(e){t.$set(t.edit.country,"has_division",e)},expression:"edit.country.has_division"}}),t._v(" "),o("span",[t._v(t._s(t.$t("t_country_has_states")))]),t._v(" "),t.errors.has_division?o("small",{staticClass:"form-text"},[t._v(t._s(t.errors.has_division[0]))]):t._e()],1)])],1)],1)],1),t._v(" "),o("v-card-actions",{staticClass:"pa-2"},[o("v-spacer"),t._v(" "),o("v-btn",{attrs:{color:"#2e3131",text:""},on:{click:function(e){return t.update()}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1):t._e(),t._v(" "),o("v-container",[o("div",{staticClass:"m-card"},[o("v-toolbar",{staticClass:"elevation-0",attrs:{color:"white"}},[o("v-toolbar-title",[t._v(t._s(t.$t("t_countries")))]),t._v(" "),o("v-spacer"),t._v(" "),o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{to:"/administrator/geolocation/countries/create",text:"",icon:""}},n),[o("v-icon",[t._v("add")])],1)]}}])},[t._v(" "),o("span",[t._v(t._s(t.$t("t_create")))])])],1),t._v(" "),o("v-data-table",{attrs:{headers:t.headers,items:t.countries,"item-key":"id","hide-default-footer":"","disable-pagination":"","no-data-text":t.$t("t_table_no_data_available")},scopedSlots:t._u([{key:"item.flag",fn:function(e){var n=e.item;return[o("v-avatar",{attrs:{size:"36px"}},[o("img",{attrs:{src:t.$homeApi("public/images/flags/large/"+n.code.toUpperCase()+".png")}})])]}},{key:"item.continent",fn:function(e){var o=e.item;return[t._v(t._s(o.continent.name))]}},{key:"item.country",fn:function(e){var o=e.item;return[t._v(t._s(o.name))]}},{key:"item.capital",fn:function(e){var o=e.item;return[t._v(t._s(o.capital))]}},{key:"item.phone",fn:function(e){var o=e.item;return[t._v(t._s(o.callingcode))]}},{key:"item.states",fn:function(e){return[e.item.has_division?o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:"",color:"green accent-3"}},n),[o("v-icon",{attrs:{size:"20px"}},[t._v("mdi-check-all")])],1)]}}],null,!0)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_has_states")))])]):o("v-tooltip",{attrs:{top:""},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on;return[o("v-btn",t._g({attrs:{text:"",icon:"",color:"error"}},n),[o("v-icon",{attrs:{size:"20px"}},[t._v("mdi-close")])],1)]}}],null,!0)},[t._v(" "),o("span",[t._v(t._s(t.$t("t_no_states")))])])]}},{key:"item.options",fn:function(e){var n=e.item;return[t.$owgate.allow(t.$auth.user,"edit","geo")?o("v-btn",{attrs:{small:"",icon:""},on:{click:function(e){t.change(n,t.countries.indexOf(n))}}},[o("v-icon",{attrs:{size:"18px",color:"grey darken-1"}},[t._v("mdi-square-edit-outline")])],1):t._e()]}}])})],1),t._v(" "),o("div",{staticClass:"text-center pt-2"},[o("pagination",{attrs:{data:t.countriesData,limit:8,align:t.$vuetify.rtl?"left":"right"},on:{"pagination-change-page":t.getNextPage}},[o("span",{attrs:{slot:"prev-nav"},slot:"prev-nav"},[o("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_right":"chevron_left"))])]),t._v(" "),o("span",{attrs:{slot:"next-nav"},slot:"next-nav"},[o("i",{staticClass:"v-icon material-icons theme--light",attrs:{"aria-hidden":"true"}},[t._v(t._s(t.$vuetify.rtl?"chevron_left":"chevron_right"))])])])],1)])],1)}),[],!1,null,null,"24eb4b66");e.default=component.exports}};