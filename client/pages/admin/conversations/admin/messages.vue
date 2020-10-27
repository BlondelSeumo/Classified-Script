<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'admins', 'conversations')">
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="error">mdi-alert-octagon-outline</v-icon>
					</div>
					<div class="mb-4 text-center headline font-weight-black text-uppercase">
						{{ $t('t_are_you_sure') }}
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="dialogs.delete = false">
						Cancel
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_messages') : this.$t('t_delete_message') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Read dialog -->
		<v-dialog v-model="dialogs.read" max-width="500px" v-if="$adgate.allow($auth.user, 'admins', 'conversations')">
			<v-card class="pa-3">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_read_message') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.read = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text v-html="message.content"></v-card-text>
			</v-card>
		</v-dialog>

		<!-- Reply dialog -->
		<v-dialog v-model="dialogs.reply" max-width="500px" persistent v-if="$adgate.allow($auth.user, 'admins', 'conversations')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_reply_message') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.reply = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>

							<!-- Subject -->
							<v-flex xs12>
								<v-text-field
									v-model="form.subject"
									color="grey lighten-1"
									:label="$t('t_subject')"
									:placeholder="$t('t_enter_subject')"
									counter="100"
									maxlength="100"
									:rules="errors.subject"
									:error="errors.subject ? true : false"
									></v-text-field>
							</v-flex>

							<!-- Message -->
							<v-flex xs12>
								<v-textarea
									v-model="form.message"
									color="grey lighten-1"
									:label="$t('t_message')"
									:placeholder="$t('t_enter_message')"
									no-resize
									></v-textarea>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn color="#2e3131" text @click="send()" :disabled="!form.message || !form.subject">{{ $t('t_send') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_admins_messages') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'admins', 'conversations')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="messages"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- From -->
					<template v-slot:item.from="{ item }">
						<span class="table-wrap-title">{{ item.name }}</span>
						<small class="pb-1 font-weight-regular text-uppercase d-block">{{ item.email }}</small>
					</template>

					<!-- Subject -->
					<template v-slot:item.subject="{ item }">
						<div class="cursor-pointer" :class="item.isRead ? 'font-weight-regular' : 'font-weight-bold'" @click="show(item, messages.indexOf(item))">
							<v-icon small v-if="item.isReplied">mdi-arrow-left-bold</v-icon>
							<span>{{ item.subject }}</span>
						</div>
					</template>

					<!-- IP Address -->
					<template v-slot:item.ip="{ item }">{{ item.ip }}</template>

					<!-- Created -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-icon small color="grey darken-1" @click="remove([item], messages.indexOf(item))" v-if="$adgate.allow($auth.user, 'admins', 'conversations')">mdi-delete</v-icon>
						<v-icon small color="grey darken-1" @click="reply(item, messages.indexOf(item))" v-if="$adgate.allow($auth.user, 'admins', 'conversations')">mdi-reply</v-icon>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="messagesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
		      	</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'admins', 'conversations')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/conversations/admin')
			return {
				messagesData: response.data,
				messages: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_from'), value: 'from', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_subject'), value: 'subject', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_ip'), value: 'ip', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        	read: false,
		        	reply: false
		        },
		        message: {
		        	index: null,
		        	content: null,
		        },
		        form: {
		        	index: null,
		        	id: null,
		        	subject: null,
		        	message: null
		        },
		        errors: [],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_admins_messages'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('administrator/conversations/admin?page=' + page)
				.then(response => {
					this.selected     = []
					this.messagesData = response.data
					this.messages     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading      = false
				})
			},

		   	avatar: function(avatar) {
				if (avatar === null) {
		   			return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
		   		}else{
		   			return this.$homeApi('storage/app/' + avatar)
		   		}
			},

			preview: function(deal) {
		   		if (deal.photosNumber == 0) {
            
		            // get default image
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');

		        }else{

		            // get first image
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg');

		        }
		   	},

		   	show: function(message, index) {
		   		// Check if message seen
		   		if (!message.isRead) {
		   			this.read(message, index)
		   		}
				this.message.index   = index
				this.message.content = message.message
				this.dialogs.read    = true
		   	},

		   	reply: function(message, index) {
				this.form.index    = index
				this.form.id       = message.unique_id
				this.form.subject  = 'Re: ' + message.subject
				this.dialogs.reply = true
		   	},

		   	send: function() {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'admins', 'conversations')) {
					this.$router.push('/administrator')	
					return
				}

		   		this.loading = true
		   		this.$axios
		   		.post('administrator/conversations/admin/options/reply', {
		   			id: this.form.id,
		   			subject: this.form.subject,
		   			message: this.form.message
		   		})
		   		.then(response => {
					this.messages[this.form.index].isReplied = true
					this.messages[this.form.index].isRead    = true
					this.dialogs.reply                       = false
					this.form.id                             = null
					this.form.index                          = null
					this.form.subject                        = null
					this.form.message                        = null
					this.$toasted.show(this.$t('t_toasted_message_sent'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading                             = false
		   		})
		   		.catch(error => {
		   			if (error.response.data.errors) {
		   				this.errors = error.response.data.errors
		   			}
		   			console.log(error)
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
		   		})
		   	},

		   	read: function(message, index) {
		   		this.loading = true
		   		this.$axios
		   		.post('administrator/conversations/admin/options/read', {
		   			id: message.unique_id
		   		})
		   		.then(response => {
					this.messages[index].isRead = true
					this.loading                = false
		   		})
		   		.catch(error => {
		   			console.log(error)
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
		   		})
		   	},

		   	remove: function(messages = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'admins', 'conversations')) {
					this.$router.push('/administrator')	
					return
				}
				
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/conversations/admin/options/delete', {
					messages: messages
				})
				.then(response => {
					if (index !== null) {
						this.messages.splice(index, 1)
					}else{
						vm.selected.forEach(function (selectedMessage, selectedIndex) {
							vm.messages.forEach(function(value, ind) {
								if (selectedMessage.unique_id === value.unique_id) {
									vm.messages.splice(ind, 1)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_message_deleted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.dialogs.delete = false
					vm.loading        = false
				})
				.catch(error => {
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			}
		}
  	}
</script>