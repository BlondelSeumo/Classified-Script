(window.webpackJsonp=window.webpackJsonp||[]).push([[129],{658:function(t,e,o){"use strict";o.r(e);o(47);var r,n=o(27),c={layout:"default/main",asyncData:(r=Object(n.a)(regeneratorRuntime.mark((function t(e){var o,r,n;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return o=e.$axios,r=e.params,t.next=3,o.post("blog/".concat(r.id));case 3:return n=t.sent,t.abrupt("return",{article:n.data.article,seo:{title:n.data.seo.title,separator:n.data.seo.separator,description:n.data.seo.description,favicon:n.data.seo.favicon,image:n.data.seo.image}});case 5:case"end":return t.stop()}}),t)}))),function(t){return r.apply(this,arguments)}),head:function(){return{title:this.article.title,titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.title),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"index, follow"},{property:"og:type",content:"article"},{property:"og:title",content:"".concat(this.article.title," ").concat(this.seo.separator," ").concat(this.seo.title)},{property:"og:description",content:this.seo.description},{property:"og:image",content:this.seo.image},{property:"og:url",content:this.$home("blog/article/".concat(this.article.title))},{property:"og:site_name",content:this.seo.title},{property:"twitter:title",content:"".concat(this.article.title," ").concat(this.seo.separator," ").concat(this.seo.title)},{property:"twitter:description",content:this.seo.description},{property:"twitter:image",content:this.seo.image}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},computed:{cover:function(){return null===this.article.cover?this.$homeApi("storage/app/uploads/default/article/no-image.png"):this.$homeApi("storage/app/"+this.article.cover)}}},l=o(52),component=Object(l.a)(c,(function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("v-app",{attrs:{id:"inspire"}},[o("v-content",[o("section",{staticClass:"mt-5"},[o("v-img",{attrs:{src:t.cover,height:"500",gradient:"to top right, rgba(10, 10, 10, 0.6), rgba(31, 31, 31, 0.73)"}},[o("v-layout",{staticClass:"white--text",attrs:{column:"","align-center":"","justify-center":"","fill-height":""}},[o("h1",{staticClass:"white--text mb-3 display-1 text-center"},[t._v(t._s(t.article.title))]),t._v(" "),o("div",{staticClass:"caption mb-4 text-center"},[t._v(t._s(t.$t("t_posted"))+" "+t._s(t.$ago(t.article.created_at)))]),t._v(" "),o("v-btn",{staticClass:"white",attrs:{light:"",large:"",to:"/blog",depressed:""}},[t._v("\n\t\t\t\t\t\t"+t._s(t.$t("t_back_to_blog"))+"\n\t\t\t\t\t")])],1)],1)],1),t._v(" "),o("v-card",{staticClass:"m-card mt-4",attrs:{flat:""}},[o("v-card-text",{domProps:{innerHTML:t._s(t.article.content)}})],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);