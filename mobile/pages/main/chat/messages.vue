<template>
	<v-flex xs11 v-if="room !== null">
		<v-toolbar color="#fffdfd" flat>
			<v-list class="pl-3">
	          	<v-list-item>
	            	<v-list-item-content>
	              		<v-list-item-title v-html="username"></v-list-item-title>
	              		<v-list-item-subtitle v-if="isTyping">Typing...</v-list-item-subtitle>
	              		<v-list-item-subtitle v-else>Online</v-list-item-subtitle>
	            	</v-list-item-content>
	            </v-list-item>
	        </v-list>
			<v-spacer></v-spacer>
			<v-tooltip top>
				<v-btn icon slot="activator">
					<v-icon>mdi-cancel</v-icon>
				</v-btn>
				<span>Block contact</span>
			</v-tooltip>
			<v-tooltip top>
				<v-btn icon slot="activator">
					<v-icon>mdi-delete-empty</v-icon>
				</v-btn>
				<span>Delete contact</span>
			</v-tooltip>
		</v-toolbar>

		<v-card-title :style="'max-height:' + (screenHeight - 64) + 'px; overflow-y: auto; height: ' + (screenHeight - 64) + 'px;position:relative'" ref="chatBox" v-chat-scroll>
				<div id="chat-container">

					<!-- Chat messages -->
			   		<div id="chat-body" v-if="messages.length !== 0">
					   	<div class="chat-message" v-for="(message, index) in messages" :key="index" :class="[message.user_id === $auth.user().id ? 'self' : '']">
							<div class="chat-message-content-w">
								<div class="chat-message-content" v-html="message.message">
								</div>
							</div>

							<div v-if="message.user_id === $auth.user().id">
								<div class="chat-message-date">
									{{ chatTime(message.sent_at) }}
								</div>
							</div>
							<div v-else>
								<div class="chat-message-date">
									{{ chatTime(message.sent_at) }}
								</div>
							</div>
						</div>
				   	</div>

				   	<div id="chat-body" class="text-center pt-5" v-else>
				   		<v-icon size="48px" color="#bdbac2">mdi-forum</v-icon>
				   		<div class="font-weight-light text-uppercase my-2 subheading grey--text text--lighten-1">
					      	Seems people are shy to start the chat. Break the ice send the first message.
					    </div>
				   	</div>

				   	<!-- Send message -->
				   	<div id="chat-footer">
					   	<v-text-field
						v-model="message"
						ref="message"
			            placeholder="Type your message"
			            append-icon="send"
			            @click:append="send"
			            @keyup.enter="send"
			            :loading="loading"
			            class="chat-input"
			            color="blue-grey lighten-4"
			            solo
			        	></v-text-field>
				   	</div>

				</div>
		</v-card-title>
	</v-flex>
</template>

<script>
	export default {
		beforeRouteEnter: function(to, from, next) {
			axios
			.post('conversations/chat/messages/latest', {
				room: to.params.room
			})
			.then(response => {
				next(vm => {
					vm.messages       = response.data.messages.data
					vm.room           = response.data.room
				})
			})
			.catch(error => {
				//next('/')
			})
		},

		data: function() {
			return {
				messages: [],
				room: null,
				message: '',
				loading: false,
				isTyping: false,
				screenHeight: screen.height - 200,
			}
		},

		watch: {
			room: {
	            handler: function(room) {
	                
	            },
	            deep: true
	        },

	        message() {
	        	var roomId  = this.$router.currentRoute.params.room
				var channel = 'chat.' + roomId
	    		Echo.private(channel)
	    		    .whisper('typing', {
	    		        typing: this.message
	    		    });
	    	}
		},

		computed: {
			username: function() {
				if (this.$auth.user().id === this.room.sender_id) {
	    			return this.room.receiver.username
	    		}else{
	    			return this.room.sender.username
	    		}
			}
		},

		methods: {
			send: function() {
	    		this.loading = true
		        if (this.message !== '') {
		          	axios
		          	.post('conversations/chat/send', {
		            	message: this.message,
		            	room: this.room.id
		          	})
		          	.then(response => {
		          		this.messages.push(response.data)
		        		this.$nextTick(function () {
					        let chatBox       = this.$refs.chatBox;
							chatBox.scrollTop = chatBox.scrollHeight;
					    })
		          		this.loading = false
            			this.message = ''
		        		this.$refs.message.focus()
		          	})
		        }
	    	}
		},

		created: function() {
			var roomId  = this.$router.currentRoute.params.room
			var channel = 'chat.' + roomId
			Echo.private(channel)  
			    .listen('ChatEvent', (e) => {
			    	this.messages.push(e.message)
			    })
			    .listenForWhisper('typing', (e) => {
			    	this.isTyping = true
			    	if (e.typing === '') {
			    		this.isTyping = false
			    	}else{
			    		this.isTyping = true
			    	}
	    	    });

	    	Echo.join('online')
	            .here(users => {
	            	console.log(users)
	            })
	            .joining(user => {
	            	console.log(user)
	            })
	            .leaving(user => {
	            	console.log(user)
	            })

	    	/*Echo.join(`chat.${roomId}`)
			    .here((users) => {
			        console.log('Hello')
			    })
			    .joining((user) => {
			        console.log('Hello');
			    })
			    .leaving((user) => {
			        console.log('Hello');
			    });*/

		}
	}
</script>