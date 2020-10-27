<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$owgate.allow($auth.user, 'access', 'subscriptions')">
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
						{{ selected.length > 1 ? this.$t('t_delete_subscriptions') : this.$t('t_delete_subscription') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Renew dialog -->
		<v-dialog v-model="dialogs.activate" max-width="500px" persistent v-if="$owgate.allow($auth.user, 'access', 'subscriptions')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_renew_subscription') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.activate = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>
							<v-flex xs12>
								<v-text-field
									v-model="subscription.days"
									color="grey lighten-1"
									:label="$t('t_renew_days')"
									:placeholder="$t('t_enter_renew_days')"
									type="number"
									:rules="errors.days"
									:error="errors.days ? true : false"
									></v-text-field>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn :color="$vuetify.theme.dark ? '#bfbfbf' : '#2e3131'" text @click="renew()" :disabled="!subscription.days">{{ $t('t_renew') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_subscriptions') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'access', 'subscriptions')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Restore -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'access', 'subscriptions')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="subscriptions"
					item-key="id"
      				hide-default-footer
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- User -->
					<template v-slot:item.user="{ item }">
						<nuxt-link :to="'/administrator/users/edit/' + item.user.username" target="_blank">
							<v-avatar size="36px">
								<img :src="avatar(item.user.avatar)">
							</v-avatar>
						</nuxt-link>
					</template>

					<!-- Plan -->
					<template v-slot:item.plan="{ item }">
						<div class="font-weight-bold text-uppercase">
							{{ item.plan.title }}
						</div>
					</template>
					

					<!-- Transaction -->
					<template v-slot:item.transaction="{ item }">
						<div class="caption">{{ item.transaction_id }}</div>
					</template>

					<!-- Subscribed at -->
					<template v-slot:item.subscribed="{ item }">{{ $ago(item.subscribed_at) }}</template>

					<!-- Expires -->
					<template v-slot:item.expires="{ item }">{{ $dateString(item.expires_at) }}</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Expired -->
							<v-btn text color="warning" v-if="item.isExpired">
								{{ $t('t_expired') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="green accent-3" v-else>
								{{ $t('t_active') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Activate -->
								<v-list-item @click="activate(item, subscriptions.indexOf(item))" v-if="item.isExpired && $owgate.allow($auth.user, 'access', 'subscriptions')">
									<v-list-item-action>
										<v-icon>mdi-check-all</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_activate') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item @click="remove([item], subscriptions.indexOf(item))" v-if="item.deleted_at === null && $owgate.allow($auth.user, 'access', 'subscriptions')">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item @click="restore([item], subscriptions.indexOf(item))" v-if="item.deleted_at !== null && $owgate.allow($auth.user, 'access', 'subscriptions')">
									<v-list-item-action>
										<v-icon>mdi-delete-restore</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_restore') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

							</v-list>
						</v-menu>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="subscriptionsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'subscriptions')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/membership/subscriptions')
			return {
				subscriptionsData: response.data,
				subscriptions: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_user'), value: 'user', sortable: false, align: 'center'},
		          	{ text: this.$t('t_plan'), value: 'plan', sortable: false, align: 'center'},
		          	{ text: this.$t('t_transaction'), value: 'transaction', sortable: false, align: 'center'},
		          	{ text: this.$t('t_subscribed'), value: 'subscribed', sortable: false, align: 'center'},
		          	{ text: this.$t('t_expires_after'), value: 'expires', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        	activate: false
		        },
		        subscription: {
		        	id: null,
		        	index: null,
		        	days: null
		        },
		        errors: [],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_subscriptions'),
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
				.get('administrator/membership/subscriptions?page=' + page)
				.then(response => {
					this.selected          = []
					this.subscriptionsData = response.data
					this.subscriptions     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading           = false
				})
			},

		   	avatar: function(avatar) {
		   		if (avatar === null) {
		   			return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
		   		}else{
		   			return this.$homeApi('storage/app/' + avatar)
		   		}
		   	},

		   	activate: function(subscription, index) {
				this.subscription.id    = subscription.unique_id
				this.subscription.index = index
				this.dialogs.activate   = true
		   	},

		   	renew: function() {
		   		this.loading
		   		this.$axios
		   		.post('administrator/membership/subscriptions/options/renew', {
		   			id: this.subscription.id,
		   			days: this.subscription.days
		   		})
		   		.then(response => {
		   			this.$toasted.show(this.$t('t_toasted_subscription_renewed'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.subscriptions[this.subscription.index].expires_at = response.data.expires_at.date
					this.subscriptions[this.subscription.index].isExpired  = response.data.isExpired
					this.dialogs.activate                                  = false
					this.subscription.id                                   = null
					this.subscription.index                                = null
					this.subscription.days                                 = null
					this.loading                                           = false
		   		})
		   		.catch(error => {
		   			if (error.response.data.errors) {
		   				this.errors = error.response.data.errors
		   			}
					this.$toasted.error(this.$t('t_toasted_shops_restored'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
		   		})
		   	},

		   	remove: function(subscriptions = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/membership/subscriptions/options/delete', {
					subscriptions: subscriptions
				})
				.then(response => {
					if (index !== null) {
						this.subscriptions[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedSubscription, selectedIndex) {
							vm.subscriptions.forEach(function(value, ind) {
								if (selectedSubscription.unique_id === value.unique_id) {
									vm.subscriptions[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_subscriptions_deleted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.dialogs.delete = false
					vm.loading        = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_shops_restored'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			restore: function(subscriptions = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/membership/subscriptions/options/restore', {
					subscriptions: subscriptions
				})
				.then(response => {
					if (index !== null) {
						this.subscriptions[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedSubscription, selectedIndex) {
							vm.subscriptions.forEach(function(value, ind) {
								if (selectedSubscription.unique_id === value.unique_id) {
									vm.subscriptions[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_subscriptions_restored'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_shops_restored'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			}
		}
  	}
</script>