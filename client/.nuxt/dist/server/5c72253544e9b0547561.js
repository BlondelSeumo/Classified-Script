exports.ids=[60],exports.modules={234:function(t,e,r){"use strict";r.r(e);var o={layout:"default/admin",middleware:"administrator",asyncData:async({app:t,$axios:e,redirect:r})=>(t.$owgate.allow(t.$auth.user,"create","geo")||r("/administrator"),{countries:(await e.get("administrator/geolocation/cities/options/create")).data}),data:function(){return{form:{name:"",country:"",state:""},states:[],errors:[],loading:!1,pageLoaded:!0}},head(){return{title:this.$t("t_create_city"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{create:function(){this.$owgate.allow(this.$auth.user,"create","geo")?(this.loading=!0,this.$axios.post("administrator/geolocation/cities/options/create",{name:this.form.name,country:this.form.country,state:this.form.state}).then(t=>{this.errors=[],this.form={name:"",country:"",state:""},this.$toasted.show(this.$t("t_toasted_city_created"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")},fetchStates:function(){this.loading=!0,this.$axios.post("ajax/fetch/states",{country:this.form.country}).then(t=>{this.states=t.data.states,this.loading=!1}).catch(t=>{this.loading=!1,console.log(t.response.data.errors)})}}},n=r(1),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_create_city")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_create_city_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:t.countries,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_country"),placeholder:t.$t("t_choose_country"),rules:t.errors.country,error:!!t.errors.country,dense:""},on:{change:function(e){return t.fetchStates()}},model:{value:t.form.country,callback:function(e){t.$set(t.form,"country",e)},expression:"form.country"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:t.states,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_state"),placeholder:t.$t("t_choose_state"),rules:t.errors.state,error:!!t.errors.state,dense:""},model:{value:t.form.state,callback:function(e){t.$set(t.form,"state",e)},expression:"form.state"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_city_name"),placeholder:t.$t("t_enter_city_name"),counter:"60",maxlength:"60",rules:t.errors.name,error:!!t.errors.name},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.create(e)}}},[t._v(t._s(t.$t("t_create")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"325efe24");e.default=component.exports}};