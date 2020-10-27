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
						{{ selected.length > 1 ? $t('t_delete_rooms') : $t('t_delete_room') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_chat_rooms') }}</v-toolbar-title>
					<v-spacer></v-spacer>

				    <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'chat', 'conversations')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="dialogs.delete = true">
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
					:items="rooms"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Room -->
					<template v-slot:item.room="{ item }">
						<nuxt-link :to="'/administrator/conversations/chat/room/' + item.unique_id">{{ item.unique_id }}</nuxt-link>
					</template>

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

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small slot="activator" icon @click="remove([item], rooms.indexOf(item))" v-if="$adgate.allow($auth.user, 'chat', 'conversations')">
							<v-icon size="18px" color="grey darken-1">mdi-delete</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		     	<pagination :data="roomsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'chat', 'conversations')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/conversations/chat')
			return {
				roomsData: response.data,
				rooms: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_room'), value: 'room', sortable: false, align: 'center'},
		          	{ text: this.$t('t_chat_from'), value: 'from', sortable: false, align: 'center'},
		          	{ text: this.$t('t_chat_to'), value: 'to', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
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
				title: this.$t('t_chat_rooms'),
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
				.get('administrator/conversations/chat?page=' + page)
				.then(response => {
					this.selected  = []
					this.roomsData = response.data
					this.rooms     = response.data.data
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

			remove: function(rooms = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'chat', 'conversations')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/conversations/chat/options/delete', {
					rooms: rooms
				})
				.then(response => {
					if (index !== null) {
						this.rooms.splice(index, 1)
					}else{
						vm.selected.forEach(function (selectedRoom, selectedIndex) {
							vm.rooms.forEach(function(value, ind) {
								if (selectedRoom.unique_id === value.unique_id) {
									vm.rooms.splice(ind, 1)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_chat_rooms_deleted'), {
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