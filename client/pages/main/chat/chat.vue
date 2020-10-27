<template>
	<v-app id="inspire">

		<v-content>
	  		<v-container fluid grid-list-xl>
	    		<v-layout wrap>

	    			<!-- Conversations -->
					<v-flex xs4>
						<v-card max-width="500" class="mx-auto m-card py-3">
							<v-list subheader avatar dense>
								<v-subheader v-t="'t_live_chat'"></v-subheader>
								<v-list-item
									v-for="(contact, index) in contacts"
									:key="index"
									@click="loadMessages(contact)"
									:class="currentChat && contact.id === currentChat.id ? 'grey lighten-4' : ''"
									>
									<v-list-item-avatar>
										<v-avatar size="35">
											<img :src="avatar(contact)">
										</v-avatar>
									</v-list-item-avatar>
									<v-list-item-content>
										<v-list-item-title v-text="receiver(contact)"></v-list-item-title>
										<v-list-item-subtitle v-if="currentChat && currentChat.id === contact.id && isTyping" v-t="'t_typing'" class="green--text red--text text--accent-4 font-italic font-weight-light overline"></v-list-item-subtitle>
										<v-list-item-subtitle v-else>{{ $ago(contact.created_at) }}</v-list-item-subtitle>
									</v-list-item-content>
									<v-list-item-action>
										<v-icon size="20" :color="currentChat && contact.id === currentChat.id ? 'light-blue' : 'grey'">chat_bubble</v-icon>
									</v-list-item-action>
								</v-list-item>
							</v-list>
						</v-card>
					</v-flex>

					<!-- Messages -->
					<v-flex xs8>
						<v-card class="m-card">

							<!-- Conversation toolbar -->
							<v-card-title class="pa-5 light-blue" v-if="currentChat">
								<h3 class="body-2 text-uppercase font-weight-bold text-center grow white--text" v-text="roomUsername"></h3>
							</v-card-title>

							<v-card-text id="chat-container">
								<div v-if="currentChat">
									<!-- Conversation messages -->
									<div id="chat-body" ref="chatBox" v-chat-scroll="{always: false, smooth: true, scrollonremoved:true}">
										<v-slide-x-transition group>
											<div class="chat-message" :class="message.user_id === $auth.user.id ? 'self' : ''" v-for="(message, index) in messages" :key="index">
												<div class="chat-message-content-w">
													<div class="chat-message-content" v-html="message.message">
													</div>
												</div>
											</div>
										</v-slide-x-transition>
									</div>

									<!-- Conversation form -->
									<div id="chat-footer" class="px-4">
										<v-text-field
										v-model="input"
										ref="input"
										:placeholder="$t('t_type_your_message')"
										append-icon="mdi-message-text-outline"
										@click:append="send"
										@keydown.enter="send"
										:loading="loading"
										class="chat-input"
										color="blue-grey lighten-4"
										solo
										></v-text-field>
									</div>
								</div>

								<div v-else class="fill-height">
									<v-container fluid class="pa-12 fill-height">
    									<v-row class="fill-height">
      										<v-col cols="12" class="fill-height">
        										<v-row align="center" justify="center" style="height: 100%;">
													<v-alert
														color="info"
														text
														dark
														icon="mdi-clock-fast"
      													border="left"
														class="px-4 py-3"
														>
														<span class="font-weight-light body-1" v-t="'t_select_chat_to_start'"></span>
													</v-alert>
        										</v-row>
      										</v-col>
    									</v-row>
									</v-container>
								</div>
							</v-card-text>

						</v-card>
					</v-flex>

		      	</v-layout>
			</v-container>
		</v-content>

	</v-app>
</template>

<script>
	export default {
		middleware: 'auth',

		layout: 'default/main',

		async asyncData({ app, $axios, query, redirect }) {
			let response = await $axios.post('chat', {conversation: query.conversation})
			return {
				currentChat: response.data.current,
				messages: response.data.messages,
				contacts: response.data.conversations,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon
				}
			}
		},

		data() {
			return {
				messages: [],
				input: null,
				isTyping: false,
				isOnline: false,
				currentChat: null,
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_live_chat'),
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		computed: {
	    	roomUsername: function() {
	    		if (this.currentChat.receiver.id === this.$auth.user.id) {
	    			return this.currentChat.sender.username
	    		}else{
	    			return this.currentChat.receiver.username
	    		}
	    	}
		},

		watch: {
			currentChat: {
				handler: function (after, before) {
					let self = this
					Echo.private(`chat.${this.currentChat.unique_id}`)  
						.listen('ChatEvent', (e) => {
							this.messages.push(e.message)
						})
						.listenForWhisper('typing', (e) => {
      						this.isTyping = e.typing;

      						// remove is typing indicator after 1s
							setTimeout(function() {
								self.isTyping = false
							}, 1000);
						});
						
				},
				deep: true,
			},
			input: {
				handler: function (after, before) {
					Echo.private(`chat.${this.currentChat.unique_id}`)
						.whisper('typing', {
							typing: true
						});
						
				},
				deep: true,
			}
		},

	    methods: {
			send: function() {
		        if (this.input) {
		        	this.loading   = true
		          	this.$axios
					    .post('chat/options/send', 
						{
							message: this.input,
							room: this.currentChat.id
						},
						{
							headers: {
								"X-Socket-Id": Echo.socketId()
							}
						})
		          	.then(response => {
		          		this.messages.push(response.data)
						this.loading = false
						this.input = null
						this.$refs.input.focus()
		          	})
		        }
			},

			loadMessages(room) {
				if (this.currentChat && this.currentChat.id === room.id) {
					return
				}

				this.loading = true
				this.$axios.post('chat/conversation', {
					id: room.unique_id
				})
				.then(response => {
					this.messages    = response.data.messages
					this.currentChat = room
					this.loading     = false
				})
				.catch(error => {
					console.log(error)
				})
			},
			
	    	avatar: function(contact) {
	    		if (contact) {
	    			if (contact.receiver.id === this.$auth.user.id) {
		    			if (contact.sender.avatar !== null) {
							return this.$homeApi('storage/app/' + contact.sender.avatar)
						}else{
							return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
						}
		    		}else{
		    			if (contact.receiver.avatar !== null) {
							return this.$homeApi('storage/app/' + contact.receiver.avatar)
						}else{
							return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
						}
		    		}
	    		}else{
	    			if (this.$auth.user().avatar !== null) {
						return this.$homeApi('storage/app/' + this.$auth.user.avatar)
					}else{
						return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
					}
	    		}
	    	},

	    	receiver: function(contact) {
	    		if (contact.receiver.id === this.$auth.user.id) {
	    			return contact.sender.username
	    		}else{
	    			return contact.receiver.username
	    		}
			},

			username: function() {
	    		if (this.current.receiver.id === this.$auth.user.id) {
	    			return this.current.sender.username
	    		}else{
	    			return this.current.receiver.username
	    		}
	    	}
		}
  	}
</script>

<style>
	.active-conversation, .active-conversation:hover {
		background-color: #e7e7e7 !important
	}

	#chat-container {
	   min-height:100%;
	   position:relative;
	   width: 100%;
	   padding: 0;
       height: 600px;
	}
	#chat-body {
	   	padding: 30px;
		overflow-y: scroll;
		height: 500px;
		min-height: 500px;
		max-height: 500px;
		padding-bottom: 100px;
		overflow-x: hidden;
	}
	#chat-footer {
	   position:absolute;
	   bottom:0;
	   width:100%;
       height: 80px;
	}
	.contacts-list {
		background: #fffdfd;
		border-right: 1px solid #eaeaea;
		height: 100%
	}
	.contacts .v-list__tile {
	    justify-content: center;
	}
	.contacts .v-list__tile--avatar {
	    height: 60px;
	}
	.chat-my-account .v-list__tile--avatar {
		margin-bottom: 10px;
	    background-color: rgb(247, 247, 247);
	    height: 64px;
	    justify-content: center;
	}
	.online-offline .v-badge__badge {
	    bottom: 3px !important;
	    top: auto;
	    height: 10px;
	    width: 10px;
	    right: 0px;
	}
	.chat-message {
		width: 100%;
		margin-bottom: 20px;
	}
	.chat-message.self {
	    text-align: right;
	}
	.v-application--is-rtl .chat-message.self {
	    text-align: left;
	}
	.chat-message.self .chat-message-content {
	    background-color: #f3f3f3;
	    color: #545353;
	    margin-right: 20px;
	    margin-left: 0px;
	    max-width: 320px;
	}
	.chat-message .chat-message-content {
		padding: 15px;
		color: #fff;
		max-width: 400px;
		display: inline-block;
		margin-bottom: -20px;
		margin-left: 20px;
		border-radius: 5px;
		text-align: left;
		background-color: #54a0ff;
		box-shadow: 0 1px 0.5px rgba(0, 0, 0, 0.13);
	}
	.chat-message .chat-message-content p {
		margin-bottom: 0 !important;
	}
	.chat-message .chat-message-date {
		display: inline-block;
		vertical-align: bottom;
		margin-left: 20px;
		margin-right: 20px;
		font-size: .72rem;
		color: rgba(0, 0, 0, 0.39);
		margin-top: 20px;
	}
	.chat-message .chat-message-avatar {
	    display: inline-block;
	    vertical-align: bottom;
	}
	.chat-message .chat-message-avatar img {
	    width: 40px;
	    height: auto;
	    border-radius: 30px;
	    display: inline-block;
	    -webkit-box-shadow: 0px 0px 0px 10px #f7f9fa;
	    box-shadow: 0px 0px 0px 10px #f7f9fa;
	}
	.chat-input {
		max-height: 50px;
	}
	.chat-input .v-input__slot {
		background-color: #efefef !important;
		border-radius: 2px !important;
		border: 0 !important;
		box-shadow: 0px 1px 0px 0px rgba(0, 0, 0, 0.05) !important;
	    padding: 0 20px !important;
	    height: 60px;
	}
	.chat-input .v-input__append-inner {
		margin-top: 2px !important;
	}
</style>