exports.ids=[20],exports.modules={141:function(e,t,r){"use strict";r.r(t);var l=r(68),o=r.n(l),d=(r(77),r(78),r(69)),n=r.n(d),c=r(70),f=r.n(c);var h={layout:"default/manager",middleware:"manager",component:o()(n.a,f.a),async asyncData({app:e,$axios:t,params:r,redirect:l}){let o=await t.get(`manager/deals/options/edit/${r.id}`);return{form:{currency:o.data.deal.currency,title:o.data.deal.title,description:o.data.deal.description,price:o.data.deal.price,category:o.data.deal.category_id,sub_cat:o.data.deal.category_id,video:o.data.deal.video,images:[],fields:o.data.formFields},fields:o.data.fields,currencies:o.data.currencies,categories:o.data.parents,childs:o.data.subs,deal:o.data.deal}},data:function(){return{errors:[],dialogs:{currency:!1},server:{url:process.env.SERVER_URL,process:!1,load:(source,e,t)=>{fetch(source).then(e=>e.blob()).then(e)}},loading:!0}},head(){return{title:this.$t("t_edit_deal"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$t("t_dashboard")}`,meta:[{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")}]}},methods:{update(){try{this.loading=!0;let e=JSON.stringify(this.form.fields),data=new FormData;data.append("id",this.deal.unique_id),data.append("title",this.form.title),this.form.description&&data.append("description",this.form.description),this.form.video&&data.append("video",this.form.video),this.form.price&&data.append("price",this.form.price),this.form.category&&data.append("category",this.form.category),this.form.sub_cat&&data.append("sub_cat",this.form.sub_cat),this.form.currency&&data.append("currency",this.form.currency),data.append("fields",e);for(let i=0;i<this.$refs.pond.getFiles().length;i++)"image/jpeg"!==this.$refs.pond.getFiles()[i].file.type&&"image/png"!==this.$refs.pond.getFiles()[i].file.type||data.append("images[]",this.$refs.pond.getFiles()[i].file);this.$axios.post("manager/deals/options/update",data,{headers:{"Content-Type":"multipart/form-data"}}).then(e=>{this.errors=[],this.$toasted.show(this.$t("t_toasted_deal_updated"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.$router.push("/manager/deals"),this.loading=!1}).catch(e=>{e.response.data.errors&&(this.errors=e.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})}catch(e){this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}},changeCurrency:function(){this.dialogs.currency=!this.dialogs.currency},fetchFields:function(e=!1){this.form.fields=[],this.loading=!0;let t=e?this.form.sub_cat:this.form.category;this.$axios.post("ajax/fetch/fields",{category:t}).then(e=>{this.fields=e.data,this.loading=!1}).catch(e=>{console.log(e),this.loading=!1})},fetchChilds:function(){this.loading=!0,this.$axios.post("ajax/fetch/categories/childs",{id:this.form.category}).then(e=>{0===e.data.length?(this.form.sub_cat=null,this.childs=[]):this.childs=e.data,this.loading=!1}).catch(e=>{console.log(e),this.loading=!1})},fetchSubs:function(){this.loading=!0,this.$axios.post("ajax/fetch/categories/childs",{id:this.form.sub_cat}).then(e=>{if(0!==e.data.length){let t=this.form.sub_cat;this.form.sub_cat=null,this.$nextTick(()=>{this.childs=e.data,this.form.sub_cat=t})}this.loading=!1}).catch(e=>{console.log(e),this.loading=!1})},addDropdownField:function(e,t){let r=this,data={slug:t,value:e};if(0===r.form.fields.length)r.form.fields.push(data);else{if(r.form.fields.some(a=>a.slug===t)){for(var l in r.form.fields){let o=r.form.fields[l];if(o.slug===t){o.value=e;break}}return}r.form.fields.push(data)}},addCheckboxField:function(e,t,r){let l=this,data={slug:t,value:[e]};if(0===l.form.fields.length)l.form.fields.push(data);else{if(l.form.fields.some(a=>a.slug===t)){for(var o in l.form.fields){let n=l.form.fields[o];if(n.slug===t){let t=n.value;for(var d in 0===t.length&&t.push(e),t){let t=l.form.fields[o].value[d];if(null===e&&t===r){l.$delete(l.form.fields[o].value,d);break}if(null!==e&&r!==t){l.form.fields[o].value.push(e);break}}break}}return}l.form.fields.push(data)}},addRadioField:function(e,t){let r=this,data={slug:t,value:e};if(0===r.form.fields.length)r.form.fields.push(data);else{if(r.form.fields.some(a=>a.slug===t)){for(var l in r.form.fields){let o=r.form.fields[l];if(o.slug===t){o.value=e;break}}return}r.form.fields.push(data)}},fieldValue(e,t=null,r,l=null){if(0!==this.form.fields.length)if("checkbox"===t){let t=this.form.fields.find((function(element){return element.slug===e}));if(t){return t.value.find((function(element){return element===l}))}}else{let t=this.form.fields.find((function(element){return element.slug===e}));if(t)return t.value}},fetchImages(){if(this.deal.photosNumber)for(let e=0;e<this.deal.photosNumber;e++){let image={source:this.$homeApi(`api/fetch/image/${this.deal.unique_id}-preview_${e}.jpg?v=${Math.random()}`),options:{type:"local"}};this.form.images.push(image)}}},mounted(){0}},m=r(1),component=Object(m.a)(h,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:e.loading,callback:function(t){e.loading=t},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[e._v("\n\t\t\t\t"+e._s(e.$t("t_loading"))+"\n\t\t\t")])],1),e._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-card",{staticClass:"m-card px-3",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-4",attrs:{"primary-title":""}},[r("div",[r("div",{staticClass:"title"},[e._v(e._s(e.$t("t_edit_deal")))]),e._v(" "),r("span",{staticClass:"card-description"},[e._v(e._s(e.$t("t_edit_deal_para",{title:e.deal.title})))])])]),e._v(" "),r("v-container",{staticClass:"pa-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_title"),placeholder:e.$t("t_enter_title"),counter:"100",maxlength:"100",rules:e.errors.title,error:!!e.errors.title},model:{value:e.form.title,callback:function(t){e.$set(e.form,"title",t)},expression:"form.title"}})],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("div",{staticClass:"form-group mb-4",class:[e.errors.description?"has-danger":""]},[r("label",{attrs:{for:"input-normal"}},[e._v(e._s(e.$t("t_description")))]),e._v(" "),r("ckeditor",{staticClass:"form-control",attrs:{config:{language:this.$i18n.locale},type:"classic"},model:{value:e.form.description,callback:function(t){e.$set(e.form,"description",t)},expression:"form.description"}})],1),e._v(" "),e.errors.description?r("small",{staticClass:"red--text block"},[e._v(e._s(e.errors.description[0]))]):e._e()]),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:e.categories,"item-text":"title","item-value":"id",label:e.$t("t_category"),placeholder:e.$t("t_choose_category"),autocomplete:"off",color:"grey lighten-1",rules:e.errors.category,error:!!e.errors.category,dense:""},on:{change:function(t){e.fetchFields(),e.fetchChilds()}},model:{value:e.form.category,callback:function(t){e.$set(e.form,"category",t)},expression:"form.category"}})],1),e._v(" "),0!==e.childs.length?r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{items:e.childs,"item-text":"title","item-value":"id",label:e.$t("t_sub_category"),placeholder:e.$t("t_choose_sub_category"),autocomplete:"off",color:"grey lighten-1",rules:e.errors.sub_cat,error:!!e.errors.sub_cat,dense:""},on:{change:function(t){e.fetchFields(!0),e.fetchSubs()}},model:{value:e.form.sub_cat,callback:function(t){e.$set(e.form,"sub_cat",t)},expression:"form.sub_cat"}})],1):e._e(),e._v(" "),e._l(e.fields,(function(t){return r("div",{key:t.id,staticStyle:{width:"100%"}},["dropdown"===t.field.type?r("v-flex",{attrs:{xs12:""}},[r("v-select",{attrs:{value:e.fieldValue(t.field.slug),items:t.field.options.split(","),placeholder:t.field.name,label:t.field.name,autocomplete:"off",color:"grey lighten-1",rules:e.errors[t.field.slug],error:!!e.errors[t.field.slug],dense:""},on:{change:function(r){return e.addDropdownField(r,t.field.slug)}}})],1):e._e(),e._v(" "),"checkbox"===t.field.type?r("v-flex",{attrs:{xs12:""}},[r("div",{class:e.errors[t.field.slug]?"error--text":""},[e._v(e._s(t.field.name))]),e._v(" "),e._l(t.field.options.split(","),(function(l,o){return r("v-checkbox",{key:o,staticClass:"mb-3",attrs:{label:l,color:"red",value:l,"hide-details":"","input-value":e.fieldValue(t.field.slug,t.field.type,o,l)},on:{change:function(r){return e.addCheckboxField(r,t.field.slug,l)}}})})),e._v(" "),e.errors[t.field.slug]?r("small",{staticClass:"error--text"},[e._v(e._s(e.errors[t.field.slug][0]))]):e._e()],2):e._e(),e._v(" "),"radio"===t.field.type?r("v-flex",{attrs:{xs12:""}},[r("div",{class:e.errors[t.field.slug]?"error--text":""},[e._v(e._s(t.field.name))]),e._v(" "),r("v-radio-group",{attrs:{column:"",value:e.fieldValue(t.field.slug)}},[e._l(t.field.options.split(","),(function(l){return r("v-radio",{key:l,staticClass:"mb-3",attrs:{label:l,color:"red",value:l,"hide-details":""},on:{change:function(r){return e.addRadioField(l,t.field.slug)}}})})),e._v(" "),e.errors[t.field.slug]?r("small",{staticClass:"error--text"},[e._v(e._s(e.errors[t.field.slug][0]))]):e._e()],2)],1):e._e()],1)})),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_price"),placeholder:e.$t("t_enter_price"),prefix:e.$currencySymbol(e.form.currency),"append-icon":"mdi-dots-vertical",rules:e.errors.price,error:!!e.errors.price},on:{"click:append":e.changeCurrency},model:{value:e.form.price,callback:function(t){e.$set(e.form,"price",t)},expression:"form.price"}})],1),e._v(" "),e.dialogs.currency?r("v-flex",{attrs:{xs12:""}},[r("v-autocomplete",{attrs:{autocomplete:"off",color:"grey lighten-1",items:e.currencies,"item-text":"name","item-value":"code",placeholder:e.$t("t_enter_currency"),label:e.$t("t_currency"),dense:""},on:{change:function(t){e.dialogs.currency=!e.dialogs.currency}},model:{value:e.form.currency,callback:function(t){e.$set(e.form,"currency",t)},expression:"form.currency"}})],1):e._e(),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:e.$t("t_youtube_video"),placeholder:e.$t("t_enter_youtube_video"),rules:e.errors.video,error:!!e.errors.video},model:{value:e.form.video,callback:function(t){e.$set(e.form,"video",t)},expression:"form.video"}})],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("client-only",[r("div",[r("div",[r("file-pond",{ref:"pond",attrs:{name:"test","label-idle":e.$t("t_drag_deal_images"),"allow-multiple":"true","accepted-file-types":"image/jpeg, image/png",server:e.server,files:e.form.images}})],1)])])],1),e._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mb-1",attrs:{disabled:e.loading,loading:e.loading,block:"",depressed:"",color:"blue",dark:""},on:{click:function(t){return t.preventDefault(),e.update(t)}}},[e._v(e._s(e.$t("t_update")))])],1)],2)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"2025e3aa");t.default=component.exports},77:function(e,t){},78:function(e,t){}};