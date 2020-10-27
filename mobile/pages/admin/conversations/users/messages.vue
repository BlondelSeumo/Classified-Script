<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'users', 'conversations')">
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
						{{$t('t_cancel') }}
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_messages') : $t('t_delete_message') }}
					</v-btn>
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
					<v-toolbar-title>{{ $t('t_users_messages') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'users', 'conversations')">
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
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- From -->
					<template v-slot:item.from="{ item }">
						<nuxt-link :to="'/administrator/users/edit/' + item.sender.username" target="_blank">
							<v-avatar size="36px">
								<img :src="avatar(item.sender.avatar)">
							</v-avatar>
						</nuxt-link>
					</template>

					<!-- To -->
					<template v-slot:item.to="{ item }">
						<nuxt-link :to="'/administrator/users/edit/' + item.receiver.username" target="_blank">
							<v-avatar size="36px">
								<img :src="avatar(item.receiver.avatar)">
							</v-avatar>
						</nuxt-link>
					</template>

					<!-- Deal -->
					<template v-slot:item.deal="{ item }">
						<v-list two-line class="transparent">
							<v-list-item :to="'/listing/' + item.deal.unique_slug" target="_blank">
								<v-list-item-avatar>
									<img :src="preview(item.deal)">
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title class="table-wrap-title" v-html="item.deal.title"></v-list-item-title>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Message -->
					<template v-slot:item.message="{ item }">
						<v-btn text color="grey darken-3" icon @click="show(item, messages.indexOf(item))">
							<v-icon>mdi-android-messages</v-icon>
						</v-btn>
					</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<!-- Not read -->
						<v-tooltip top v-if="!item.isRead">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text color="grey darken-3" icon v-if="!item.isRead">
									<v-icon>mdi-eye-off</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_user_not_seen_message') }}</span>
						</v-tooltip>

						<!-- Read -->
						<v-tooltip top v-else>
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text color="#22a7f0" icon>
									<v-icon>mdi-eye</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_user_seen_message') }}</span>
						</v-tooltip>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small icon @click="remove([item], messages.indexOf(item))" v-if="$adgate.allow($auth.user, 'users', 'conversations')">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-delete</v-icon>
						</v-btn>
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
			if (!app.$adgate.allow(app.$auth.user, 'users', 'conversations')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/conversations/users')
			return {
				messagesData: response.data,
				messages: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_chat_from'), value: 'from', sortable: false, align: 'center'},
		          	{ text: this.$t('t_chat_to'), value: 'to', sortable: false, align: 'center'},
		          	{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_message'), value: 'message', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        	read: false
		        },
		        message: {
		        	index: null,
		        	content: null
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_users_messages'),
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
				.get('administrator/conversations/users?page=' + page)
				.then(response => {
					this.selected  = []
					this.messagesData = response.data
					this.messages     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
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
				this.message.index   = index
				this.message.content = message.message
				this.dialogs.read    = true
		   	},

		   	remove: function(messages = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'users', 'conversations')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/conversations/users/options/delete', {
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
					vm.$toasted.show(this.$t('t_toasted_messages_deleted'), {
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