<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Reply dialog -->
		<v-dialog v-model="dialogs.reply" max-width="500px" persistent>
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_reply') }}</h3>
					<v-spacer></v-spacer>
					<v-btn small depressed class="px-3" v-if="message.message && message.message.isReplied">
						{{ $t('t_already_replied') }}
					</v-btn>
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
									:label="$t('t_message')"
									:placeholder="$t('t_enter_message')"
									counter="500"
									maxlength="500"
									no-resize
									required
									></v-textarea>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<client-only>
						<v-btn color="#2e3131" flat @click="send" :disabled="!form.message">{{ $t('t_send') }}</v-btn>
					</client-only>
				</v-card-actions>
			</v-card>
		</v-dialog>

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
				<v-card-text v-html="message.content"></v-card-text>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_message_center') }}</v-toolbar-title>
				</v-toolbar>
				<v-data-table
					:headers="headers"
					:items="messages"
					item-key="id"
      				hide-default-footer
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					:mobile-breakpoint="1"
					>
					<template v-slot:item.shop="{ item }">
						<v-list two-line class="transparent">
							<v-list-item :to="`/shop/${item.shop.store}`" target="_blank" nuxt :ripple="false">
								<v-list-item-avatar>
									<img :src="logo(item.shop.logo)">
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title class="table-wrap-title" v-html="item.shop.title"></v-list-item-title>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- From -->
					<template v-slot:item.from="{ item }">
						{{ item.sender.username }}
					</template>

					<!-- Subject -->
					<template v-slot:item.subject="{ item }">
						<div :class="item.isRead ? 'font-weight-regular' : 'font-weight-black'">
							<span class="cursor-pointer" @click="read(item, messages.indexOf(item))">{{ item.subject }}</span>
						</div>
					</template>

					<!-- sent at -->
					<template v-slot:item.sent="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-tooltip top>
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" small icon @click="reply(item, messages.indexOf(item))">
									<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-reply</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_reply') }}</span>
						</v-tooltip>
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
		layout: 'default/manager',

		middleware: 'manager',

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.get('manager/messages')
			return {
				messagesData: response.data,
				messages: response.data.data
			}
		},

		data: function() {
			return {
				form: {
					message: null
				},
		        dialogs: {
		        	reply: false,
		        	read: false
		        },
		        message: {
		        	index: null,
					content: null,
					message: null
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_message_center'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},

		computed: {
			headers(){
				return [
					{ text: this.$t('t_shop'), value: 'shop', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_from'), value: 'from', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_subject'), value: 'subject', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_sent_at'), value: 'sent', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('manager/messages?page=' + page)
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

			async read(message, index) {
				if (!message.isRead) {
					await this.$axios.post('manager/messages/options/read', { id: message.unique_id })
				}
				this.message.content        = message.message
				this.message.index          = index
				this.messages[index].isRead = true
				this.dialogs.read           = true
			},

			reply: function(message, index) {
				this.message.message = message
				this.message.index   = index
				this.dialogs.reply   = true
			},

			send: function() {
				if (!this.form.message) {
					return
				}
				this.loading = true
				this.$axios
				.post('manager/messages/options/reply', {
					id: this.message.message.unique_id,
					message: this.form.message
				})
				.then(response => {
					this.$toasted.show(this.$t('t_toasted_message_sent'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.messages[this.message.index].isReplied = true
					this.loading                                = false
					this.dialogs.reply                          = false
					this.message.index                          = null
					this.message.content                        = null
					this.message.message                        = null
				})
				.catch(error => {
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading       = false
					this.dialogs.reply =  false
				})
			},

		   	logo: function(logo) {
				if (logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			}
		}
  	}
</script>