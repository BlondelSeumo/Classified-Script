exports.ids=[99],exports.modules={202:function(t,e,r){"use strict";r.r(e);var o={layout:"default/admin",middleware:"administrator",async asyncData({app:t,$axios:e,redirect:r}){t.$owgate.allow(t.$auth.user,"upload","settings")||r("/administrator");let o=await e.get("administrator/settings/upload");return{form:{max_image_size:o.data.singleImageSize,max_total_images_size:o.data.multipleImageSize,compression:o.data.isCompression,storage:o.data.storage}}},data:function(){return{clouds:[{id:"local",name:this.$t("t_local_storage")}],errors:[],loading:!1}},head(){return{title:this.$t("t_uploading_settings"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){this.$owgate.allow(this.$auth.user,"upload","settings")?(this.loading=!0,this.$axios.post("administrator/settings/upload",{max_image_size:this.form.max_image_size,max_total_images_size:this.form.max_total_images_size,compression:this.form.compression,storage:this.form.storage}).then(t=>{this.errors=[],this.$toasted.show(this.$t("t_toasted_uploading_settings_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{this.errors=t.response.data.errors,this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")}}},l=r(1),component=Object(l.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_uploading_settings")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_upload_settings_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_max_single_image_size"),placeholder:t.$t("t_enter_max_single_image_size"),rules:t.errors.max_image_size,error:!!t.errors.max_image_size},model:{value:t.form.max_image_size,callback:function(e){t.$set(t.form,"max_image_size",e)},expression:"form.max_image_size"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_max_total_image_size"),placeholder:t.$t("t_enter_max_total_image_size"),rules:t.errors.max_total_images_size,error:!!t.errors.max_total_images_size},model:{value:t.form.max_total_images_size,callback:function(e){t.$set(t.form,"max_total_images_size",e)},expression:"form.max_total_images_size"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[t.errors.compression?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{color:"blue"},model:{value:t.form.compression,callback:function(e){t.$set(t.form,"compression",e)},expression:"form.compression"}}),t._v(" "),r("span",[t._v(t._s(t.$t("t_enable_compressor")))])],1),t._v(" "),t.errors.compression?r("small",{staticClass:"red--text"},[t._v(t._s(t.errors.compression[0]))]):t._e()]),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-select",{attrs:{items:t.clouds,"item-text":"name","item-value":"id",color:"grey lighten-1",label:t.$t("t_default_storage"),placeholder:t.$t("t_choose_default_storage"),rules:t.errors.storage,error:!!t.errors.storage,dense:""},model:{value:t.form.storage,callback:function(e){t.$set(t.form,"storage",e)},expression:"form.storage"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.update(e)}}},[t._v(t._s(t.$t("t_update")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"f9ffbd1e");e.default=component.exports}};