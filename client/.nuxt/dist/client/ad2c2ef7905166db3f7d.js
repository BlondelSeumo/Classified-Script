(window.webpackJsonp=window.webpackJsonp||[]).push([[32],{541:function(t,e,r){},566:function(t,e,r){"use strict";var n=r(541);r.n(n).a},662:function(t,e,r){"use strict";r.r(e);r(47);var n,o=r(27),c={middleware:"auth",layout:"default/main",asyncData:(n=Object(o.a)(regeneratorRuntime.mark((function t(e){var r,n,o;return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e.app,r=e.$axios,n=e.query,e.redirect,t.next=3,r.post("chat",{conversation:n.conversation});case 3:return o=t.sent,t.abrupt("return",{currentChat:o.data.current,messages:o.data.messages,contacts:o.data.conversations,seo:{title:o.data.seo.title,separator:o.data.seo.separator,description:o.data.seo.description,favicon:o.data.seo.favicon}});case 5:case"end":return t.stop()}}),t)}))),function(t){return n.apply(this,arguments)}),data:function(){return{messages:[],input:null,isTyping:!1,isOnline:!1,currentChat:null,loading:!1}},head:function(){return{title:this.$t("t_live_chat"),titleTemplate:"%s ".concat(this.seo.separator," ").concat(this.seo.title),meta:[{name:"description",content:this.seo.description},{name:"robots",content:"noindex, nofollow"}],link:[{rel:"icon",type:"image/x-icon",href:this.seo.favicon}]}},computed:{roomUsername:function(){return this.currentChat.receiver.id===this.$auth.user.id?this.currentChat.sender.username:this.currentChat.receiver.username}},watch:{currentChat:{handler:function(t,e){var r=this,n=this;Echo.private("chat.".concat(this.currentChat.unique_id)).listen("ChatEvent",(function(t){r.messages.push(t.message)})).listenForWhisper("typing",(function(t){r.isTyping=t.typing,setTimeout((function(){n.isTyping=!1}),1e3)}))},deep:!0},input:{handler:function(t,e){Echo.private("chat.".concat(this.currentChat.unique_id)).whisper("typing",{typing:!0})},deep:!0}},methods:{send:function(){var t=this;this.input&&(this.loading=!0,this.$axios.post("chat/options/send",{message:this.input,room:this.currentChat.id},{headers:{"X-Socket-Id":Echo.socketId()}}).then((function(e){t.messages.push(e.data),t.loading=!1,t.input=null,t.$refs.input.focus()})))},loadMessages:function(t){var e=this;this.currentChat&&this.currentChat.id===t.id||(this.loading=!0,this.$axios.post("chat/conversation",{id:t.unique_id}).then((function(r){e.messages=r.data.messages,e.currentChat=t,e.loading=!1})).catch((function(t){console.log(t)})))},avatar:function(t){return t?t.receiver.id===this.$auth.user.id?null!==t.sender.avatar?this.$homeApi("storage/app/"+t.sender.avatar):this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):null!==t.receiver.avatar?this.$homeApi("storage/app/"+t.receiver.avatar):this.$homeApi("storage/app/uploads/default/avatar/noavatar.png"):null!==this.$auth.user().avatar?this.$homeApi("storage/app/"+this.$auth.user.avatar):this.$homeApi("storage/app/uploads/default/avatar/noavatar.png")},receiver:function(t){return t.receiver.id===this.$auth.user.id?t.sender.username:t.receiver.username},username:function(){return this.current.receiver.id===this.$auth.user.id?this.current.sender.username:this.current.receiver.username}}},l=(r(566),r(52)),component=Object(l.a)(c,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-app",{attrs:{id:"inspire"}},[r("v-content",[r("v-container",{attrs:{fluid:"","grid-list-xl":""}},[r("v-layout",{attrs:{wrap:""}},[r("v-flex",{attrs:{xs4:""}},[r("v-card",{staticClass:"mx-auto m-card py-3",attrs:{"max-width":"500"}},[r("v-list",{attrs:{subheader:"",avatar:"",dense:""}},[r("v-subheader",{directives:[{name:"t",rawName:"v-t",value:"t_live_chat",expression:"'t_live_chat'"}]}),t._v(" "),t._l(t.contacts,(function(e,n){return r("v-list-item",{key:n,class:t.currentChat&&e.id===t.currentChat.id?"grey lighten-4":"",on:{click:function(r){return t.loadMessages(e)}}},[r("v-list-item-avatar",[r("v-avatar",{attrs:{size:"35"}},[r("img",{attrs:{src:t.avatar(e)}})])],1),t._v(" "),r("v-list-item-content",[r("v-list-item-title",{domProps:{textContent:t._s(t.receiver(e))}}),t._v(" "),t.currentChat&&t.currentChat.id===e.id&&t.isTyping?r("v-list-item-subtitle",{directives:[{name:"t",rawName:"v-t",value:"t_typing",expression:"'t_typing'"}],staticClass:"green--text red--text text--accent-4 font-italic font-weight-light overline"}):r("v-list-item-subtitle",[t._v(t._s(t.$ago(e.created_at)))])],1),t._v(" "),r("v-list-item-action",[r("v-icon",{attrs:{size:"20",color:t.currentChat&&e.id===t.currentChat.id?"light-blue":"grey"}},[t._v("chat_bubble")])],1)],1)}))],2)],1)],1),t._v(" "),r("v-flex",{attrs:{xs8:""}},[r("v-card",{staticClass:"m-card"},[t.currentChat?r("v-card-title",{staticClass:"pa-5 light-blue"},[r("h3",{staticClass:"body-2 text-uppercase font-weight-bold text-center grow white--text",domProps:{textContent:t._s(t.roomUsername)}})]):t._e(),t._v(" "),r("v-card-text",{attrs:{id:"chat-container"}},[t.currentChat?r("div",[r("div",{directives:[{name:"chat-scroll",rawName:"v-chat-scroll",value:{always:!1,smooth:!0,scrollonremoved:!0},expression:"{always: false, smooth: true, scrollonremoved:true}"}],ref:"chatBox",attrs:{id:"chat-body"}},[r("v-slide-x-transition",{attrs:{group:""}},t._l(t.messages,(function(e,n){return r("div",{key:n,staticClass:"chat-message",class:e.user_id===t.$auth.user.id?"self":""},[r("div",{staticClass:"chat-message-content-w"},[r("div",{staticClass:"chat-message-content",domProps:{innerHTML:t._s(e.message)}})])])})),0)],1),t._v(" "),r("div",{staticClass:"px-4",attrs:{id:"chat-footer"}},[r("v-text-field",{ref:"input",staticClass:"chat-input",attrs:{placeholder:t.$t("t_type_your_message"),"append-icon":"mdi-message-text-outline",loading:t.loading,color:"blue-grey lighten-4",solo:""},on:{"click:append":t.send,keydown:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.send(e)}},model:{value:t.input,callback:function(e){t.input=e},expression:"input"}})],1)]):r("div",{staticClass:"fill-height"},[r("v-container",{staticClass:"pa-12 fill-height",attrs:{fluid:""}},[r("v-row",{staticClass:"fill-height"},[r("v-col",{staticClass:"fill-height",attrs:{cols:"12"}},[r("v-row",{staticStyle:{height:"100%"},attrs:{align:"center",justify:"center"}},[r("v-alert",{staticClass:"px-4 py-3",attrs:{color:"info",text:"",dark:"",icon:"mdi-clock-fast",border:"left"}},[r("span",{directives:[{name:"t",rawName:"v-t",value:"t_select_chat_to_start",expression:"'t_select_chat_to_start'"}],staticClass:"font-weight-light body-1"})])],1)],1)],1)],1)],1)])],1)],1)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=component.exports}}]);