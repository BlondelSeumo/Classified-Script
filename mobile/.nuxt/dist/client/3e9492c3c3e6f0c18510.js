(window.webpackJsonp=window.webpackJsonp||[]).push([[43],{553:function(e,t,r){"use strict";(function(e){r(413),r(45);var o,l=r(26),n=r(529),c=r.n(n),d=(r(530),r(531),r(532)),f=r.n(d),h=r(533),m=r.n(h),v=c()(f.a,m.a);t.a={layout:"default/moderator",middleware:"moderator",component:v,asyncData:(o=Object(l.a)(regeneratorRuntime.mark((function e(t){var r,o,l,n,c;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r=t.app,o=t.$axios,l=t.params,n=t.redirect,r.$mogate.allow(r.$auth.user,"edit","deals")||n("/moderator"),e.next=4,o.get("moderator/deals/options/edit/".concat(l.id));case 4:return c=e.sent,e.abrupt("return",{form:{currency:c.data.deal.currency,title:c.data.deal.title,description:c.data.deal.description,price:c.data.deal.price,category:c.data.deal.category_id,sub_cat:c.data.deal.category_id,video:c.data.deal.video,images:[],fields:c.data.formFields},fields:c.data.fields,currencies:c.data.currencies,categories:c.data.parents,childs:c.data.subs,deal:c.data.deal});case 6:case"end":return e.stop()}}),e)}))),function(e){return o.apply(this,arguments)}),data:function(){return{errors:[],dialogs:{currency:!1},server:{url:e.env.SERVER_URL,process:!1,load:function(source,e,t){fetch(source).then((function(e){return e.blob()})).then(e)}},loading:!0}},head:function(){return{title:this.$t("t_edit_deal"),titleTemplate:"%s ".concat(this.$store.state.settings.separator," ").concat(this.$t("t_dashboard")),meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi("storage/app/".concat(this.$store.state.settings.favicon)):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update:function(){var e=this;try{this.loading=!0;var t=JSON.stringify(this.form.fields),data=new FormData;data.append("id",this.deal.unique_id),data.append("title",this.form.title),this.form.description&&data.append("description",this.form.description),this.form.video&&data.append("video",this.form.video),this.form.price&&data.append("price",this.form.price),this.form.category&&data.append("category",this.form.category),this.form.sub_cat&&data.append("sub_cat",this.form.sub_cat),this.form.currency&&data.append("currency",this.form.currency),data.append("fields",t);for(var i=0;i<this.$refs.pond.getFiles().length;i++)"image/jpeg"!==this.$refs.pond.getFiles()[i].file.type&&"image/png"!==this.$refs.pond.getFiles()[i].file.type||data.append("images[]",this.$refs.pond.getFiles()[i].file);this.$axios.post("moderator/deals/options/update",data,{headers:{"Content-Type":"multipart/form-data"}}).then((function(t){e.errors=[],e.$toasted.show(e.$t("t_toasted_deal_updated"),{icon:"done_all",className:e.$vuetify.rtl?"toasted-rtl":""}),e.$router.push("/moderator/deals"),e.loading=!1})).catch((function(t){t.response.data.errors&&(e.errors=t.response.data.errors),e.$toasted.error(e.$t("t_toasted_something_went_wrong"),{icon:"error",className:e.$vuetify.rtl?"toasted-rtl":""}),e.loading=!1}))}catch(e){this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}},changeCurrency:function(){this.dialogs.currency=!this.dialogs.currency},fetchFields:function(){var e=this,t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.form.fields=[],this.loading=!0;var r=t?this.form.sub_cat:this.form.category;this.$axios.post("ajax/fetch/fields",{category:r}).then((function(t){e.fields=t.data,e.loading=!1})).catch((function(t){console.log(t),e.loading=!1}))},fetchChilds:function(){var e=this;this.loading=!0,this.$axios.post("ajax/fetch/categories/childs",{id:this.form.category}).then((function(t){0===t.data.length?(e.form.sub_cat=null,e.childs=[]):e.childs=t.data,e.loading=!1})).catch((function(t){console.log(t),e.loading=!1}))},fetchSubs:function(){var e=this;this.loading=!0,this.$axios.post("ajax/fetch/categories/childs",{id:this.form.sub_cat}).then((function(t){if(0!==t.data.length){var r=e.form.sub_cat;e.form.sub_cat=null,e.$nextTick((function(){e.childs=t.data,e.form.sub_cat=r}))}e.loading=!1})).catch((function(t){console.log(t),e.loading=!1}))},addDropdownField:function(e,t){var r=this,data={slug:t,value:e};if(0!==r.form.fields.length)if(r.form.fields.some((function(a){return a.slug===t})))for(var o in r.form.fields){var l=r.form.fields[o];if(l.slug===t){l.value=e;break}}else r.form.fields.push(data);else r.form.fields.push(data)},addCheckboxField:function(e,t,r){var o=this,data={slug:t,value:[e]};if(0!==o.form.fields.length)if(o.form.fields.some((function(a){return a.slug===t})))for(var l in o.form.fields){var n=o.form.fields[l];if(n.slug===t){var c=n.value;for(var d in 0===c.length&&c.push(e),c){var f=o.form.fields[l].value[d];if(null===e&&f===r){o.$delete(o.form.fields[l].value,d);break}if(null!==e&&r!==f){o.form.fields[l].value.push(e);break}}break}}else o.form.fields.push(data);else o.form.fields.push(data)},addRadioField:function(e,t){var r=this,data={slug:t,value:e};if(0!==r.form.fields.length)if(r.form.fields.some((function(a){return a.slug===t})))for(var o in r.form.fields){var l=r.form.fields[o];if(l.slug===t){l.value=e;break}}else r.form.fields.push(data);else r.form.fields.push(data)},fieldValue:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,r=(arguments.length>2&&arguments[2],arguments.length>3&&void 0!==arguments[3]?arguments[3]:null);if(0!==this.form.fields.length)if("checkbox"===t){var o=this.form.fields.find((function(element){return element.slug===e}));if(o){var l=o.value.find((function(element){return element===r}));return l}}else{var n=this.form.fields.find((function(element){return element.slug===e}));if(n)return n.value}},fetchImages:function(){if(this.deal.photosNumber)for(var e=0;e<this.deal.photosNumber;e++){var image={source:this.$homeApi("api/fetch/image/".concat(this.deal.unique_id,"-preview_").concat(e,".jpg?v=").concat(Math.random())),options:{type:"local"}};this.form.images.push(image)}}},mounted:function(){this.fetchImages(),this.loading=!1}}}).call(this,r(161))},740:function(e,t,r){"use strict";r.r(t);var o=r(553).a,l=r(52),component=Object(l.a)(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[e._v("\n\t\t\t\t"+e._s(e.$t("t_loading"))+"\n\t\t\t")])],1),e._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card px-3",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[e._v(e._s(e.$t("t_edit_deal")))]),e._v(" "),r("span",{staticClass:"card-description"},[e._v(e._s(e.$t("t_edit_deal_para",{title:e.deal.title})))])])]),e._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_title"),placeholder:e.$t("t_enter_title"),counter:"100",maxlength:"100",rules:e.errors.title,error:!!e.errors.title},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"form-group mb-4",class:[e.errors.description?"has-danger":""]},[r("label",{attrs:{for:"input-normal"}},[e._v(e._s(e.$t("t_description")))]),e._v(" "),r("ckeditor",{staticClass:"form-control",attrs:{config:{language:this.$i18n.locale},type:"classic"},model:{value:e.form.description,callback:function(t){e.$set(e.form,"description",t)},expression:"form.description"}})],1),e._v(" "),e.errors.description?r("small",{staticClass:"red--text block"},[e._v(e._s(e.errors.description[0]))]):e._e()]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:e.categories,"item-text":"title","item-value":"id",label:e.$t("t_category"),placeholder:e.$t("t_choose_category"),autocomplete:"off",color:"grey lighten-1",rules:e.errors.category,error:!!e.errors.category,dense:""},on:{change:function(t){e.fetchFields(),e.fetchChilds()}},model:{value:e.form.category,callback:function(t){e.$set(e.form,"category",t)},expression:"form.category"}})],1),e._v(" "),0!==e.childs.length?r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:e.childs,"item-text":"title","item-value":"id",label:e.$t("t_sub_category"),placeholder:e.$t("t_choose_sub_category"),autocomplete:"off",color:"grey lighten-1",rules:e.errors.sub_cat,error:!!e.errors.sub_cat,dense:""},on:{change:function(t){e.fetchFields(!0),e.fetchSubs()}},model:{value:e.form.sub_cat,callback:function(t){e.$set(e.form,"sub_cat",t)},expression:"form.sub_cat"}})],1):e._e(),e._v(" "),e._l(e.fields,(function(t){return r("div",{key:t.id,staticStyle:{width:"100%"}},["dropdown"===t.field.type?r("v-flex",{attrs:{xs12:""}},[r("v-select",{attrs:{value:e.fieldValue(t.field.slug),items:t.field.options.split(","),placeholder:t.field.name,label:t.field.name,autocomplete:"off",color:"grey lighten-1",rules:e.errors[t.field.slug],error:!!e.errors[t.field.slug],dense:""},on:{change:function(r){return e.addDropdownField(r,t.field.slug)}}})],1):e._e(),e._v(" "),"checkbox"===t.field.type?r("v-flex",{attrs:{xs12:""}},[r("div",{class:e.errors[t.field.slug]?"error--text":""},[e._v(e._s(t.field.name))]),e._v(" "),e._l(t.field.options.split(","),(function(o,l){return r("v-checkbox",{key:l,staticClass:"mb-3",attrs:{label:o,color:"red",value:o,"hide-details":"","input-value":e.fieldValue(t.field.slug,t.field.type,l,o)},on:{change:function(r){return e.addCheckboxField(r,t.field.slug,o)}}})})),e._v(" "),e.errors[t.field.slug]?r("small",{staticClass:"error--text"},[e._v(e._s(e.errors[t.field.slug][0]))]):e._e()],2):e._e(),e._v(" "),"radio"===t.field.type?r("v-flex",{attrs:{xs12:""}},[r("div",{class:e.errors[t.field.slug]?"error--text":""},[e._v(e._s(t.field.name))]),e._v(" "),r("v-radio-group",{attrs:{column:"",value:e.fieldValue(t.field.slug)}},[e._l(t.field.options.split(","),(function(o){return r("v-radio",{key:o,staticClass:"mb-3",attrs:{label:o,color:"red",value:o,"hide-details":""},on:{change:function(r){return e.addRadioField(o,t.field.slug)}}})})),e._v(" "),e.errors[t.field.slug]?r("small",{staticClass:"error--text"},[e._v(e._s(e.errors[t.field.slug][0]))]):e._e()],2)],1):e._e()],1)})),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_price"),placeholder:e.$t("t_enter_price"),prefix:e.$currencySymbol(e.form.currency),"append-icon":"mdi-dots-vertical",rules:e.errors.price,error:!!e.errors.price},on:{"click:append":e.changeCurrency},model:{value:e.form.price,callback:function(t){e.$set(e.form,"price",t)},expression:"form.price"}})],1),e._v(" "),e.dialogs.currency?r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",color:"grey lighten-1",items:e.currencies,"item-text":"name","item-value":"code",placeholder:e.$t("t_enter_currency"),label:e.$t("t_currency"),dense:""},on:{change:function(t){e.dialogs.currency=!e.dialogs.currency}},model:{value:e.form.currency,callback:function(t){e.$set(e.form,"currency",t)},expression:"form.currency"}})],1):e._e(),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_youtube_video"),placeholder:e.$t("t_enter_youtube_video"),rules:e.errors.video,error:!!e.errors.video},model:{value:e.form.video,callback:function(t){e.$set(e.form,"video",t)},expression:"form.video"}})],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("client-only",[r("div",[r("div",[r("file-pond",{ref:"pond",attrs:{name:"test","label-idle":e.$t("t_drag_deal_images"),"allow-multiple":"true","accepted-file-types":"image/jpeg, image/png",server:e.server,files:e.form.images}})],1)])])],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1",attrs:{disabled:e.loading,loading:e.loading,block:"",depressed:"",color:"blue",dark:""},on:{click:function(t){return t.preventDefault(),e.update(t)}}},[e._v(e._s(e.$t("t_update")))])],1)],2)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);t.default=component.exports}}]);