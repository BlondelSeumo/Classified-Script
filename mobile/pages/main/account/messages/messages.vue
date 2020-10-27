<template>
	<v-app id="inspire">
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Read dialog -->
		<v-dialog v-model="dialogs.read" max-width="500px">
			<v-card class="pa-3">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_read_message') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.read = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text v-html="messageToRead"></v-card-text>
			</v-card>
		</v-dialog>

		<!-- Reply dialog -->
		<v-dialog v-model="dialogs.reply" max-width="500px" persistent>
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
							<v-flex xs12>
								<v-textarea
									v-model="form.message"
									color="grey lighten-1"
									:label="$t('t_reply')"
									:placeholder="$t('t_write_reply')"
									counter="500"
									maxlength="500"
                                    :rules="errors.message"
                                    :error="errors.message ? true : false"
									no-resize
									required
									></v-textarea>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn :color="$vuetify.theme.dark ? '#bfbfbf' : '#2e3131'" text @click="send()" :disabled="!form.message">{{ $t('t_reply') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>
					  
          			<!-- Account page -->
					<v-flex xs12>
						<v-card class="m-card" flat>
							<v-toolbar flat>
								<v-toolbar-title class="title">{{ $t('t_message_center') }}</v-toolbar-title>
								<v-spacer></v-spacer>
							</v-toolbar>

					        <v-data-table
								:headers="headers"
								:items="messages"
								item-key="id"
				  				hide-default-footer
								disable-pagination
								:mobile-breakpoint="1"
								:no-data-text="$t('t_table_no_data_available')"
								>
								<!-- Deal -->
								<template v-slot:item.deal="{ item }">
									<v-list two-line class="transparent">
										<v-list-item nuxt :ripple="false" :to="'/listing/' + item.deal.unique_slug" target="_blank">
											<v-list-item-avatar>
												<img :src="preview(item.deal)">
											</v-list-item-avatar>
											<v-list-item-content class="table-wrap-title">
												<v-list-item-title class="table-wrap-title" v-html="item.deal.title"></v-list-item-title>
												<v-list-item-subtitle class="pb-1 text-uppercase text-small font-weight-regular d-block">
													{{ item.deal.unique_id }}
												</v-list-item-subtitle>
											</v-list-item-content>
										</v-list-item>
									</v-list>
								</template>

								<!-- from -->
								<template v-slot:item.from="{ item }">
									<div class="font-weight-bold">{{ item.sender.username }}</div>
								</template>

								<!-- Read message -->
								<template v-slot:item.message="{ item }">
									<v-btn depressed dark :color="$vuetify.theme.dark ? '#f6f6f6' : 'grey darken-3'" text icon @click="read(item, messages.indexOf(item))">
										<v-icon size="20px">mdi-eye</v-icon>
									</v-btn>
								</template>

								<!-- Status -->
								<template v-slot:item.status="{ item }">
									<v-btn depressed dark :color="item.isRead ? 'grey' : 'green accent-4'" icon text>
										<v-icon size="20px" v-if="item.isRead">mdi-email-open</v-icon>
										<v-icon size="20px" v-else>mdi-email</v-icon>
									</v-btn>
								</template>

								<!-- Sent -->
								<template v-slot:item.sent="{ item }">{{ $ago(item.created_at) }}</template>

								<!-- Options -->
								<template v-slot:item.options="{ item }">
									<!-- Reply -->
									<v-tooltip top>
										<v-icon slot="activator" size="20px" @click="reply(item)">
											mdi-reply
										</v-icon>
										<span>Reply</span>
										</v-tooltip>
								</template>
							</v-data-table>
						</v-card>
						<div class="text-center pt-2">
							<pagination :data="messagesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
								<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
								<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
							</pagination>
						</div>
					</v-flex>

				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	import sidebar from '~/pages/main/account/layout/account'

	export default {
		middleware: 'auth',

		layout: 'default/main',

		components: {
			'v-sidebar': sidebar
		},

		async asyncData ({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$megate.allow(app.$auth.user, 'receive', 'messages')) {
				redirect('/administrator')
			}

			let response = await $axios.get('account/messages')
			return {
				messagesData: response.data.messages,
				messages: response.data.messages.data,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon
				}
			}
		},

		data: function() {
			return {
		        form:  {
		        	message: null,
		        },
		        dialogs: {
		        	read: false,
		        	reply: false
		        },
		        errors: [],
				loading: false,
				messageToRead: null,
				messageToReply: null
			}
		},

		head() {
			return {
				title : this.$t('t_message_center'),
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
			headers(){
				return [
					{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_from'), value: 'from', sortable: false, align: 'center'},
		          	{ text: this.$t('t_message'), value: 'message', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_sent_at'), value: 'sent', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('account/messages?page=' + page)
				.then(response => {
					this.selected   = []
					this.messagesData = response.data.messages
					this.messages     = response.data.messages.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
			},

			preview: function(deal) {
		   		if (deal.photosNumber == 0) {
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png')
		        }else{
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg')
		        }
		   	},

		   	read: function(message, index) {
		   		if (!message.isRead) {
		   			this.loading = true
		   			this.$axios
		   			.post('account/messages/options/read', {
		   				id: message.unique_id
		   			})
		   			.then(response => {
						this.messages[index].isRead = true
						this.loading                = false
		   			})
		   			.catch(error => {
		   				this.loading = false
		   			})
		   		}
		   		this.messageToRead = message.message
		   		this.dialogs.read  = true
		   	},

		   	reply: function(message) {
		   		this.messageToReply = message
		   		this.dialogs.reply  = true
		   	},

		   	send: function() {
				this.loading = true
				this.$axios
				.post('account/messages/options/reply', {
					id: this.messageToReply.unique_id,
					message: this.form.message
				})
				.then(response => {
					this.$toasted.show(this.$t('t_toasted_reply_sent'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.dialogs.reply = false
					this.loading       = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors = error.response.data.errors
					}
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			}
		}
	}
</script>