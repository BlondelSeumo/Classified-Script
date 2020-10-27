exports.ids=[57],exports.modules={239:function(t,e,r){"use strict";r.r(e);var o={layout:"default/admin",middleware:"administrator",asyncData:async({app:t,$axios:e,redirect:r})=>(t.$adgate.allow(t.$auth.user,"create","fields")||r("/administrator"),{categories:(await e.post("administrator/ajax/fetch/categories/all")).data}),data:function(){return{form:{name:"",slug:"",type:"",options:"",categories:[],required:!1,searchable:!1},types:[{title:this.$t("t_dropdown"),value:"dropdown"},{title:this.$t("t_checkbox"),value:"checkbox"},{title:this.$t("t_radio"),value:"radio"}],errors:[],loading:!1}},head(){return{title:this.$t("t_create_field"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{create:function(){this.$adgate.allow(this.$auth.user,"create","fields")?(this.loading=!0,this.$axios.post("administrator/fields/options/create",{name:this.form.name,slug:this.form.slug,type:this.form.type,options:this.form.options,categories:this.form.categories,required:this.form.required,searchable:this.form.searchable}).then(t=>{this.errors=[],this.form={name:"",slug:"",type:"",options:"",categories:[],required:!1,searchable:!1},this.$toasted.show(this.$t("t_toasted_field_created"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})):this.$router.push("/administrator")},fetchChilds:function(){this.$nextTick(()=>{this.noMoreChilds||(this.loading=!0,this.$axios.post("administrator/ajax/fetch/categories/childs",{parent:this.selected}).then(t=>{t.data.hasChilds?(this.loading=!1,this.categories=t.data.childs,this.form.parent=this.selected,this.selected=""):(this.loading=!1,this.form.parent=this.selected,this.noMoreChilds=!0)}).catch(t=>{this.loading=!1,console.log(t)}))})},remove(t){this.form.categories.splice(this.form.categories.indexOf(t),1),this.form.categories=[...this.form.categories]}}},l=r(1),component=Object(l.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t")])],1),t._v(" "),r("v-container",[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[t._v(t._s(t.$t("t_create_field")))]),t._v(" "),r("span",{staticClass:"card-description"},[t._v(t._s(t.$t("t_create_field_para")))])])]),t._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_name"),placeholder:t.$t("t_enter_name"),counter:"100",maxlength:"100",rules:t.errors.name,error:!!t.errors.name},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_identifier_name"),placeholder:t.$t("t_enter_identifier_name"),counter:"100",maxlength:"100",hint:t.$t("t_hint_identifier_name"),rules:t.errors.slug,error:!!t.errors.slug},model:{value:t.form.slug,callback:function(e){t.$set(t.form,"slug",e)},expression:"form.slug"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-select",{attrs:{autocomplete:"off",color:"grey lighten-1",items:t.types,"item-text":"title","item-value":"value",placeholder:t.$t("t_choose_field_type"),label:t.$t("t_type"),rules:t.errors.type,error:!!t.errors.type,dense:""},model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}})],1),t._v(" "),""!==t.form.type?r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_options"),placeholder:t.$t("t_enter_options"),hint:t.$t("t_field_options_hint"),"persistent-hint":"",rules:t.errors.options,error:!!t.errors.options},model:{value:t.form.options,callback:function(e){t.$set(t.form,"options",e)},expression:"form.options"}})],1):t._e(),t._v(" "),r("v-flex",{staticClass:"multiple-categories-select",attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:t.categories,"item-text":"title","item-value":"id",label:t.$t("t_categories"),placeholder:t.$t("t_choose_categories"),autocomplete:"off",color:"grey lighten-1",rules:t.errors.categories,error:!!t.errors.categories,chips:"",dense:"",multiple:""},scopedSlots:t._u([{key:"selection",fn:function(data){return[r("v-chip",{staticClass:"elevation-0 small-chips-close-icon",attrs:{color:"#3da3fa","text-color":"white","input-value":data.selected,close:"",small:""},on:{"click:close":function(e){return t.remove(data)}}},[r("v-avatar",[r("v-icon",{attrs:{left:""}},[t._v("check_circle")])],1),t._v(" "),r("span",{staticClass:"text-uppercase font-weight-light caption"},[t._v(t._s(data.item.title))])],1)]}}]),model:{value:t.form.categories,callback:function(e){t.$set(t.form,"categories",e)},expression:"form.categories"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[t.errors.required?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{"false-value":0,"true-value":1,color:"blue"},model:{value:t.form.required,callback:function(e){t.$set(t.form,"required",e)},expression:"form.required"}}),t._v(" "),r("span",[t._v(t._s(t.$t("t_required_field")))])],1),t._v(" "),t.errors.required?r("small",{staticClass:"red--text"},[t._v(t._s(t.errors.required[0]))]):t._e()]),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("label",{staticClass:"form-group has-float-label",class:[t.errors.searchable?"has-danger":""]},[r("v-switch",{staticClass:"ma-0 form-group form-control",attrs:{"false-value":0,"true-value":1,color:"blue"},model:{value:t.form.searchable,callback:function(e){t.$set(t.form,"searchable",e)},expression:"form.searchable"}}),t._v(" "),r("span",[t._v(t._s(t.$t("t_searchable")))])],1),t._v(" "),t.errors.searchable?r("small",{staticClass:"red--text"},[t._v(t._s(t.errors.searchable[0]))]):t._e()]),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1 white--text",attrs:{disabled:t.loading,loading:t.loading,block:"",depressed:"",color:"blue"},on:{click:function(e){return e.preventDefault(),t.create(e)}}},[t._v(t._s(t.$t("t_create")))])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"09173042");e.default=component.exports}};