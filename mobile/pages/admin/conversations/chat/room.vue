<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'chat', 'conversations')">
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
						{{ $t('t_cancel') }}
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_messages') : $t('t_delete_message') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_room_messages') }}</v-toolbar-title>
					<v-spacer></v-spacer>

				    <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'chat', 'conversations')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="dialogs.delete = true">
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
					<!-- Sender -->
					<template v-slot:item.sender="{ item }">
						<nuxt-link :to="'/administrator/users/edit/' + item.sender.username" target="_blank">
							<v-avatar size="36px">
								<img :src="avatar(item.sender.avatar)">
							</v-avatar>
						</nuxt-link>
					</template>

					<!-- Message -->
					<template v-slot:item.message="{ item }">{{ $striphtml(item.message) }}</template>

					<!-- Sent at -->
					<template v-slot:item.sent="{ item }">{{ $ago(item.sent_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small icon @click="remove([item], messages.indexOf(item))" v-if="$adgate.allow($auth.user, 'chat', 'conversations')">
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

		async asyncData({ app, $axios, params, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'chat', 'conversations')) {
				redirect('/administrator')
			}

			let response = await $axios.get(`administrator/conversations/chat/room/${params.id}`)
			return {
				roomId: params.id,
				messagesData: response.data,
				messages: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_sender'), value: 'sender', sortable: false, align: 'center'},
		          	{ text: this.$t('t_message'), value: 'message', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_sent_at'), value: 'sent', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_room_messages'),
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
				.get('administrator/conversations/chat/room/' + this.roomId + '?page=' + page)
				.then(response => {
					this.selected  = []
					this.roomsData = response.data
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

			remove: function(messages = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'chat', 'conversations')) {
					this.$router.push('/administrator')	
					return
				}
				
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/conversations/chat/options/messages/delete', {
					id: this.roomId,
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