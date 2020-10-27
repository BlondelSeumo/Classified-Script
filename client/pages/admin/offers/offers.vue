<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'deals')">
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
						{{ selected.length > 1 ? $t('t_delete_offers') : $t('t_delete_offer') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_offers') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'deals')">
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
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'deals')">
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
					:items="offers"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Deal -->
					<template v-slot:item.deal="{ item }">
						<nuxt-link :to="'/listing/' + item.deal.unique_slug" target="_blank" class="table-wrap-title">{{ item.deal.title }}</nuxt-link>
					</template>

					<!-- Offer price -->
					<template v-slot:item.price="{ item }">
						<div v-if="item.price === null && item.currency === null && item.locale === null"></div>
						<div v-else>{{ $getCurrency(item.price, item.currency, item.locale) }}</div>
					</template>

					<!-- Offer from -->
					<template v-slot:item.from="{ item }">{{ item.from.username }}</template>

					<!-- Offer to -->
					<template v-slot:item.to="{ item }">{{ item.to.username }}</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Pending -->
							<v-btn text color="warning" v-if="item.isPending && !item.isAccepted && !item.isRefused">
								{{ $t('t_pending') }}
							</v-btn>
							<!-- Accepted -->
							<v-btn text color="#2ecc71" v-else-if="item.isAccepted">
								{{ $('t_accepted') }}
							</v-btn>
							<!-- Refused -->
							<v-btn text color="#bdc3c7" v-else-if="item.isRefused">
								{{ $t('t_declined') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<div v-else>
							<v-btn text color="error">
								{{ $t('t_deleted') }}
							</v-btn>
						</div>
					</template>

					<!-- Created -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<!-- Delete -->
						<v-btn text icon color="#2e3131" @click="remove([item], offers.indexOf(item))" v-if="item.deleted_at === null && $adgate.allow($auth.user, 'delete', 'deals')">
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" small>mdi-delete</v-icon>
								</template>
								<span>{{ $t('t_delete') }}</span>
							</v-tooltip>
						</v-btn>
						<!-- Restore -->
						<v-btn text icon color="#2e3131" @click="restore([item], offers.indexOf(item))" v-if="item.deleted_at !== null && $adgate.allow($auth.user, 'delete', 'deals')">
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" small>mdi-delete-restore</v-icon>
								</template>
								<span>{{ $t('t_restore') }}</span>
							</v-tooltip>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="offersData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'access', 'deals')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/deals/offers')
			return {
				offersData: response.data,
				offers: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_from'), value: 'from', sortable: false, align: 'center'},
		          	{ text: this.$t('t_to'), value: 'to', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
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
				title: this.$t('t_offers'),
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
				.get('administrator/deals/offers?page=' + page)
				.then(response => {
					this.selected   = []
					this.offersData = response.data
					this.offers     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
			},

		   	remove: function(offers = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'deals')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/deals/offers/options/delete', {
					offers: offers
				})
				.then(response => {
					if (index !== null) {
						this.offers[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedOffer, selectedIndex) {
							vm.offers.forEach(function(value, ind) {
								if (selectedOffer.unique_id === value.unique_id) {
									vm.offers[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_offers_deleted'), {
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
					vm.dialogs.delete = false
					vm.loading        = false
				})
			},

			restore: function(offers = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'deals')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/deals/offers/options/restore', {
					offers: offers
				})
				.then(response => {
					if (index !== null) {
						this.offers[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedOffer, selectedIndex) {
							vm.offers.forEach(function(value, ind) {
								if (selectedOffer.unique_id === value.unique_id) {
									vm.offers[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_offers_restored'), {
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
			}
		}
  	}
</script>