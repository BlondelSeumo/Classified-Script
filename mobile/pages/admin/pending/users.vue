<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_pending_users') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Activate all users -->
					<v-fade-transition>
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="activate(selected)">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_activate') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="users"
					item-key="id"
      				hide-default-footer
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Username -->
					<template v-slot:item.username="{ item }">
						{{ item.username }}
					</template>

					<!-- E-mail address -->
					<template v-slot:item.email="{ item }">
						{{ item.email }}
					</template>

					<!-- Subscription -->
					<template v-slot:item.subscription="{ item }">Free</template>

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
									<v-btn text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-4'" v-on="on">
										<i class="mdi mdi-at fs-20"></i>
									</v-btn>
								</template>
								<span>{{ $t('t_registered_via_email') }}</span>
							</v-tooltip>
						</div>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small slot="activator" icon @click.prevent="activate([item], users.indexOf(item))">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-check</v-icon>
						</v-btn>
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
			if (!app.$adgate.allow(app.$auth.user, 'approve', 'users')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/pending/users')
			return {
				usersData: response.data,
				users: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_username'), value: 'username', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_email'), value: 'email', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_subscription'), value: 'subscription', sortable: false, align: 'center'},
		          	{ text: this.$t('t_registered_via'), value: 'via', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_users'),
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
				.get('administrator/pending/users?page=' + page)
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

			activate: function(users = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/users/options/activate', {
					users: users
				})
				.then(response => {
					if (index !== null) {
						this.$delete(this.users, index)
					}else{
						vm.selected.forEach(function (selectedUsers, selectedIndex) {
							vm.users.forEach(function(value, index) {
								if (selectedUsers.unique_id === value.unique_id) {
									vm.$delete(vm.users, index)
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
		}
  	}
</script>