exports.ids=[129],exports.modules={176:function(t,e,r){"use strict";r.r(e);var o={layout:"default/main",async asyncData({app:t,$axios:e,redirect:r}){let o=await e.get("contact");return{seo:{title:o.data.title,separator:o.data.separator,description:o.data.description,favicon:o.data.favicon,image:o.data.image}}},head(){return{title:this.$t("t_contact_us"),titleTemplate:`%s ${this.seo.separator} ${this.seo.title}`,meta:[{name:"description",content:this.seo.description},{name:"robots",content:"index, follow"},{property:"og:type",content:"article"},{property:"og:title",content:`${this.$t("t_contact_us")} ${this.seo.separator} ${this.seo.title}`},{property:"og:description",content:this.seo.description},{property:"og:image",content:this.seo.image},{property:"og:url",content:this.$home("contact")},{property:"og:site_name",content:this.seo.title},{property:"twitter:title",content:`${this.$t("t_contact_us")} ${this.seo.separator} ${this.seo.title}`},{property:"twitter:description",content:this.seo.description},{property:"twitter:image",content:this.seo.image}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},data:function(){return{form:{name:"",email:"",subject:"",message:""},errors:[],loading:!1}},methods:{send:function(){this.loading=!0,this.$axios.post("contact",{name:this.form.name,email:this.form.email,subject:this.form.subject,message:this.form.message}).then(t=>{this.errors=[],this.form={name:"",email:"",subject:"",message:""},this.$toasted.show(this.$t("t_toasted_message_sent"),{icon:"done_all",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1}).catch(t=>{t.response.data.errors&&(this.errors=t.response.data.errors),this.$toasted.error(this.$t("t_toasted_something_went_wrong"),{icon:"error",className:this.$vuetify.rtl?"toasted-rtl":""}),this.loading=!1})}}},n=r(1),component=Object(n.a)(o,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-overlay",{attrs:{opacity:"0.8"},model:{value:t.loading,callback:function(e){t.loading=e},expression:"loading"}},[r("v-progress-circular",{attrs:{indeterminate:"",size:"80",width:"4",color:"white"}},[t._v("\n\t\t\t\t"+t._s(t.$t("t_loading"))+"\n\t\t\t")])],1),t._v(" "),r("v-content",[r("v-container",{attrs:{"grid-list-xl":"",fluid:""}},[r("v-row",{attrs:{justify:"center"}},[r("v-flex",{attrs:{xs6:""}},[r("v-card",{staticClass:"m-card",attrs:{flat:""}},[r("v-card-title",{staticClass:"pa-0 justify-center",attrs:{"primary-title":""}},[r("h1",{staticClass:"display-1 font-weight-black text-uppercase pt-12"},[t._v(t._s(t.$t("t_contact_us")))])]),t._v(" "),r("v-container",{staticClass:"pa-7 mt-4",attrs:{"grid-list-xl":"",fluid:""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_name"),placeholder:t.$t("t_enter_name"),rules:t.errors.name,error:!!t.errors.name},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_email"),placeholder:t.$t("t_enter_email"),rules:t.errors.email,error:!!t.errors.email,type:"email"},model:{value:t.form.email,callback:function(e){t.$set(t.form,"email",e)},expression:"form.email"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-text-field",{attrs:{color:"grey lighten-1",label:t.$t("t_subject"),placeholder:t.$t("t_enter_subject"),counter:"100",maxlength:"100",rules:t.errors.subject,error:!!t.errors.subject},model:{value:t.form.subject,callback:function(e){t.$set(t.form,"subject",e)},expression:"form.subject"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-textarea",{attrs:{color:"grey lighten-1",label:t.$t("t_message"),placeholder:t.$t("t_enter_message"),counter:"700",maxlength:"700",rules:t.errors.message,error:!!t.errors.message},model:{value:t.form.message,callback:function(e){t.$set(t.form,"message",e)},expression:"form.message"}})],1),t._v(" "),r("v-flex",{attrs:{xs12:""}},[r("v-btn",{staticClass:"mt-0",attrs:{depressed:"",loading:t.loading,disabled:t.loading,block:"",color:"light-blue lighten-1",dark:""},on:{click:function(e){return e.preventDefault(),t.send()}}},[t._v(t._s(t.$t("t_send")))])],1)],1)],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,null,"6ae614cd");e.default=component.exports}};