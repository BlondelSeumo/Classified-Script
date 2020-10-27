<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Block dialog -->
		<v-dialog v-model="dialogs.block" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'users')">
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="warning">mdi-information-outline</v-icon>
					</div>
					<div class="mb-4 text-center headline font-weight-black text-uppercase">
						{{ $t('t_are_you_sure') }}
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="dialogs.block = false">
						Cancel
					</v-btn>
					<v-btn color="warning" text @click="block()">
						{{ selected.length > 1 ? $t('t_block_users') : $t('t_block_user') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'users')">
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
						{{ selected.length > 1 ? $t('t_delete_users'): $t('t_delete_user') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- User profile details -->
		<v-dialog v-model="dialogs.details" max-width="600" persistent v-if="$adgate.allow($auth.user, 'access', 'users')">
			<v-card
				class="ma-0 no-border-radius"
				v-if="Object.keys(selectedUser).length !== 0"
				>
				<v-card
					dark
					flat
					>
					<v-card-title class="pa-2 blue-grey lighten-5">
						<v-btn icon @click="dialogs.details = false" light>
							<v-icon>mdi-close</v-icon>
						</v-btn>
						<h3 class="black--text title font-weight-bold text-uppercase text-center grow">{{ selectedUser.username }}</h3>
						<v-avatar>
							<v-img v-if="selectedUser.avatar !== null" :src="$homeApi('storage/app/' + selectedUser.avatar)"></v-img>
							<v-img v-else :src="$homeApi('storage/app/uploads/default/avatar/noavatar.png')"></v-img>
						</v-avatar>
					</v-card-title>
				</v-card>
				<v-card-text class="py-0">
					<v-timeline
						align-top
						dense
						>
						<v-timeline-item
							color="error"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_updated') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ $ago(selectedUser.updated_at) }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
						<v-timeline-item
							v-if="selectedUser.country !== null"
							color="error"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_country') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ selectedUser.country.name }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
						<v-timeline-item
							v-if="selectedUser.state !== null"
							color="error"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_state') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ selectedUser.state.name }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
						<v-timeline-item
							v-if="selectedUser.city !== null"
							color="error"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_city') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ selectedUser.city.name }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
						<v-timeline-item
							v-if="selectedUser.phone !== null"
							color="error"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_phone') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ selectedUser.phone }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>

						<!-- Stats deals -->
						<v-timeline-item
							v-if="Object.keys(userStatsData).length !== 0 && selectedUser.id === userStatsData.user_id"
							color="green accent-3"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_deals') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ userStatsData.deals }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
						<!-- Stats shops -->
						<v-timeline-item
							v-if="Object.keys(userStatsData).length !== 0 && selectedUser.id === userStatsData.user_id"
							color="green accent-3"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_shops') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ userStatsData.shops }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
						<!-- Stats comments -->
						<v-timeline-item
							v-if="Object.keys(userStatsData).length !== 0 && selectedUser.id === userStatsData.user_id"
							color="green accent-3"
							small
							>
							<v-layout pt-3>
								<v-flex xs3>
									<strong>{{ $t('t_comments') }}</strong>
								</v-flex>
								<v-flex>
									<strong>{{ userStatsData.comments }}</strong>
								</v-flex>
							</v-layout>
						</v-timeline-item>
					</v-timeline>

					<div class="text-center pb-4">
						<v-btn
							color="error"
							class="my-0"
							text
							:loading="statsLoading"
							:disabled="statsLoading"
							@click="statistics"
							>{{ $t('t_load_user_stats') }}</v-btn>
					</div>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" flat>
					<v-toolbar-title>{{ $t('t_users') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Activate -->
					<v-fade-transition v-if="$adgate.allow($auth.user, 'approve', 'users')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="activate()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_activate') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'users')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Restore -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'users')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

			        <!-- Block -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'users')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="dialogs.block = true">
									<v-icon>mdi-cancel</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_block') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Unblock -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'users')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="unblock()">
									<v-icon>mdi-account-convert</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_unblock') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create user -->
					<v-tooltip top v-if="$adgate.allow($auth.user, 'create', 'users')">
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/users/create" v-on="on" text icon color="grey darken-3">
								<v-icon>mdi-account-plus</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="users"
					item-key="id"
      				hide-default-footer
					show-select
					:loading="loading"
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Avatar -->
					<template v-slot:item.avatar="{ item }">
						<v-avatar :color="randomColor(item.username)" size="36px">
							<img
								v-if="item.avatar !== null"
								:src="$homeApi('storage/app/' + item.avatar)">
							<span v-else class="white--text headline">{{ firstChar(item.username) }}</span>
						</v-avatar>
					</template>

					<!-- Username -->
					<template v-slot:item.username="{ item }">
						{{ item.username }}
						<small class="mt-1 font-weight-regular text-uppercase d-block" :class="roleColor(item.role.group)">{{ item.role.name }}</small>
					</template>

					<!-- E-mail address -->
					<template v-slot:item.email="{ item }">
						{{ item.email }}
					</template>

					<!-- Subscription -->
					<template v-slot:item.subscription="{ item }">{{ item.plan.title }}</template>

					<!-- Registered via -->
					<template v-slot:item.via="{ item }">
						<div class="follow-btn">
							<v-tooltip top v-if="item.provider_name !== null">
								<template v-slot:activator="{ on }">
									<v-btn text icon :color="providerColor(item.provider_name)" v-on="on">
										<i class="mdi fs-20" :class="'mdi-' + item.provider_name"></i>
									</v-btn>
								</template>
								<span>Registered via {{ item.provider_name.charAt(0).toUpperCase() + item.provider_name.slice(1) }}</span>
							</v-tooltip>
							<v-tooltip top v-else>
								<template v-slot:activator="{ on }">
									<v-btn text icon color="grey darken-4" v-on="on">
										<i class="mdi mdi-at fs-20"></i>
									</v-btn>
								</template>
								<span>{{ $t('t_registered_via_email') }}</span>
							</v-tooltip>
						</div>
					</template>

					<!-- status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Active -->
							<v-btn text color="#2ecc71" v-if="item.isActive">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="warning" v-if="item.isPending">
								{{ $t('t_pending') }}
							</v-btn>
							<!-- Banned -->
							<v-btn text color="#95a5a6" v-if="item.isBlocked">
								{{ $t('t_banned') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px" v-if="item.id !== 1">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Details -->
								<v-list-item @click="details(item)" v-if="$adgate.allow($auth.user, 'access', 'users')">
									<v-list-item-action>
										<v-icon>mdi-eye</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_details') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Activate -->
								<v-list-item v-if="item.isPending && $adgate.allow($auth.user, 'approve', 'users')" @click="activate([item], users.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-check-all</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_activate') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Edit -->
								<v-list-item :to="'/administrator/users/edit/' + item.username" v-if="$adgate.allow($auth.user, 'edit', 'users')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item v-if="item.deleted_at === null && $adgate.allow($auth.user, 'delete', 'users')" @click="remove([item], users.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item v-if="item.deleted_at !== null && $adgate.allow($auth.user, 'delete', 'users')" @click="restore([item], users.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete-restore</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_restore') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Unblock -->
								<v-list-item v-if="item.isBlocked && $adgate.allow($auth.user, 'delete', 'users')" @click="unblock([item], users.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-account-convert</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_unblock') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Block -->
								<v-list-item v-if="!item.isBlocked && $adgate.allow($auth.user, 'delete', 'users')" @click="block([item], users.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-cancel</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_block') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

							</v-list>
						</v-menu>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="usersData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'access', 'users')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/users')
			return {
				usersData: response.data,
				users: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_avatar'), value: 'avatar', sortable: false, align: 'center'},
		          	{ text: this.$t('t_username'), value: 'username', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_email'), value: 'email', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_subscription'), value: 'subscription', sortable: false, align: 'center'},
		          	{ text: this.$t('t_registered_via'), value: 'via', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	block: false,
		        	delete: false,
		        	details: false
		        },
		        selectedUser: {},
		        userStatsData: {},
		        pageLoaded: false,
		        statsLoading: false,
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_users'),
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
				.get('administrator/users?page=' + page)
				.then(response => {
					this.selected  = []
					this.usersData = response.data
					this.users     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			activate: function(users = this.selected, index = false) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'approve', 'users')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/users/options/activate', {
					users: users
				})
				.then(response => {
					if (index) {
						this.users[index].isBlocked  = false
						this.users[index].isActive   = true
						this.users[index].isPending  = false
						this.users[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedUser, selectedIndex) {
							vm.users.forEach(function(value, ind) {
								if (selectedUser.unique_id === value.unique_id && value.id !== 1) {
									vm.users[ind].isBlocked  = false
									vm.users[ind].isActive   = true
									vm.users[ind].isPending  = false
									vm.users[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_users_activated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			block: function(users = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'users')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/users/options/block', {
					users: users
				})
				.then(response => {
					if (index) {
						this.users[index].isBlocked  = true
						this.users[index].isActive   = false
						this.users[index].isPending  = false
						this.users[index].deleted_at = null
						this.dialogs.block 			 = false
					}else{
						vm.selected.forEach(function (selectedUser, selectedIndex) {
							vm.users.forEach(function(value, ind) {
								if ((selectedUser.unique_id === value.unique_id) && value.id !== 1) {
									vm.users[ind].isBlocked  = true
									vm.users[ind].isActive   = false
									vm.users[ind].isPending  = false
									vm.users[ind].deleted_at = null
									vm.dialogs.block         = false
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_users_banned'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			unblock: function(users = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'users')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/users/options/unblock', {
					users: users
				})
				.then(response => {
					if (index !== null) {
						this.users[index].isBlocked  = false
						this.users[index].isActive   = true
						this.users[index].isPending  = false
						this.users[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedUser, selectedIndex) {
							vm.users.forEach(function(value, ind) {
								if (selectedUser.unique_id === value.unique_id && value.id !== 1) {
									vm.users[ind].isBlocked  = false
									vm.users[ind].isActive   = true
									vm.users[ind].isPending  = false
									vm.users[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_users_unbanned'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			remove: function(users = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'users')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/users/options/delete', {
					users: users
				})
				.then(response => {
					if (index !== null) {
						this.users[index].isBlocked  = false
						this.users[index].isActive   = false
						this.users[index].isPending  = false
						this.users[index].deleted_at = true
						this.dialogs.delete          = false
					}else{
						vm.selected.forEach(function (selectedUser, selectedIndex) {
							vm.users.forEach(function(value, ind) {
								if ((selectedUser.unique_id === value.unique_id) && value.id !== 1) {
									vm.users[ind].isBlocked  = false
									vm.users[ind].isActive   = false
									vm.users[ind].isPending  = false
									vm.users[ind].deleted_at = true
									vm.dialogs.delete        = false
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_users_deleted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			restore: function(users = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'users')) {
					this.$router.push('/administrator')	
					return
				}
				
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/users/options/restore', {
					users: users
				})
				.then(response => {
					if (index !== null) {
						this.users[index].isBlocked  = false
						this.users[index].isActive   = true
						this.users[index].isPending  = false
						this.users[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedUser, selectedIndex) {
							vm.users.forEach(function(value, ind) {
								if ((selectedUser.unique_id === value.unique_id) && value.id !== 1) {
									vm.users[ind].isBlocked  = false
									vm.users[ind].isActive   = true
									vm.users[ind].isPending  = false
									vm.users[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_users_restored'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			details : function(user) {
				this.selectedUser    = user
				this.dialogs.details = true
			},

			statistics: function() {
				this.statsLoading = true
				this.$axios
				.post('administrator/users/options/statistics', {
					id: this.selectedUser.id
				})
				.then(response => {
					this.userStatsData = response.data
					this.statsLoading   = false
				})
				.catch(error => {
					if (error.response.status === 404) {
						this.$toasted.error(this.$t('t_toasted_user_not_found'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
				    }
					this.statsLoading = false
				})
			},

			randomColor: function(str) {
			  	let hash = 0;
			  	for (var i = 0; i < str.length; i++) {
			    	hash = str.charCodeAt(i) + ((hash << 5) - hash);
			  	}
			  	let color = '#';
			  	for (var i = 0; i < 3; i++) {
			    	let value = (hash >> (i * 8)) & 0xFF;
			    	color += ('00' + value.toString(16)).substr(-2);
			  	}
			  	return color;
			},

			firstChar: function(string) {
				return string.charAt(0).toUpperCase()
			},

			roleColor: function(group) {
				if (group === 'owner') {return 'red--text'}
				if (group === 'administrator') {return 'green--text'}
				if (group === 'moderator') {return 'orange--text'}
				if (group === 'member') {return 'blue--text'}
			},

			providerColor: function(provider) {
				if (provider === 'facebook') return "#3c5a99"
				if (provider === 'twitter') return "#1da1f2"
				if (provider === 'google') return "#dc4e41"
				if (provider === 'instagram') return "#e4405f"
				if (provider === 'pinterest') return "#bd081c"
				if (provider === 'linkedin') return "#0077b5"
				if (provider === 'vk') return "#6383a8"
				if (provider === 'reddit') return "#ff4500"
				if (provider === 'yahoo') return "#440099"
				if (provider === 'twitch') return "#6441a4"
				if (provider === 'soundcloud') return "#ff3300"
			}
		},

		
  	}
</script>