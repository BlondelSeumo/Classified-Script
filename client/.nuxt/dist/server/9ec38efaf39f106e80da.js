exports.ids=[29],exports.modules={102:function(t,e,r){"use strict";r.r(e);var n=r(84),d=r.n(n);for(var o in n)"default"!==o&&function(t){r.d(e,t,(function(){return n[t]}))}(o);e.default=d.a},183:function(t,e,r){"use strict";r.r(e);var n={data:()=>({isRtl:!1}),head(){return{title:this.$t("t_page_not_found"),titleTemplate:`%s ${this.$store.state.settings.separator} ${this.$store.state.settings.title}`,meta:[{name:"description",content:this.$store.state.settings.description},{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.$store.state.settings.favicon?this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`):this.$homeApi("storage/app/uploads/default/settings/favicon/favicon.png")},{rel:"stylesheet",type:"text/css",href:"https://fonts.googleapis.com/css?family=Nunito"},{rel:"dns-prefetch",href:"//fonts.gstatic.com"}],bodyAttrs:{class:"antialiased"}}},mounted(){this.$vuetify.rtl&&(this.isRtl=!0)}},d=r(1);var component=Object(d.a)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{class:t.isRtl?"is-rtl":""},[t._ssrNode('<div class="md:flex min-h-screen font-sans right" data-v-25bd61a2>',"</div>",[t._ssrNode('<div class="w-full md:w-1/2 bg-white flex items-center justify-center" data-v-25bd61a2>',"</div>",[t._ssrNode('<div class="max-w-sm m-8" data-v-25bd61a2>',"</div>",[t._ssrNode('<div class="text-black text-5xl md:text-15xl font-black" data-v-25bd61a2>404</div> <div class="w-16 h-1 bg-purple-light my-3 md:my-6" data-v-25bd61a2></div> <p class="text-grey-darker text-2xl md:text-3xl font-light mb-8 leading-normal" data-v-25bd61a2>'+t._ssrEscape(t._s(t.$t("t_sorry_page_not_found")))+"</p> "),r("nuxt-link",{staticClass:"bg-transparent text-grey-darkest font-bold uppercase tracking-wide py-3 px-6 border-2 border-grey-light hover:border-grey rounded-lg",attrs:{to:"/"}},[t._v("\n                    "+t._s(t.$t("t_go_home"))+"\n                ")])],2)]),t._ssrNode(' <div class="relative pb-full md:flex md:pb-0 md:min-h-screen w-full md:w-1/2" data-v-25bd61a2><div class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center"'+t._ssrStyle(null,"background-image: url("+t.$homeApi("public/svg/404.svg")+");",null)+" data-v-25bd61a2></div></div>")],2)])}),[],!1,(function(t){var e=r(102);e.__inject__&&e.__inject__(t)}),"25bd61a2","f6656c24");e.default=component.exports},84:function(t,e){}};