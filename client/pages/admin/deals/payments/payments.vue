<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_deals_payments') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Accept -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'access', 'subscriptions')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="accept()">
									<v-icon>mdi-checkbox-marked-circle</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_accept') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Decline -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'access', 'subscriptions')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="decline()">
									<v-icon>mdi-close-circle</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_decline') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="payments"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Deal -->
					<template v-slot:item.deal="{ item }">
						<v-list two-line class="transparent">
							<v-list-item :to="'/listing/' + item.deal.unique_slug" target="_blank">
								<v-list-item-avatar>
									<img :src="preview(item.deal)">
								</v-list-item-avatar>
								<v-list-item-content class="table-wrap-title">
									<v-list-item-title class="table-wrap-title" v-html="item.deal.title"></v-list-item-title>
									<v-list-item-subtitle class="pb-1 text-uppercase text-small font-weight-regular d-block" v-html="item.transaction_id"></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Package -->
					<template v-slot:item.package="{ item }">
						<!-- Highlight -->
						<v-chip small v-if="item.package.type === 'highlight'" label color="amber lighten-4" text-color="amber darken-2">{{ $t('t_highlight') }}</v-chip>
						<!-- Featured -->
						<v-chip small v-if="item.package.type === 'featured'" label color="light-green lighten-4" text-color="light-green darken-2">{{ $t('t_featured') }}</v-chip>
						<!-- Urgent -->
						<v-chip small v-if="item.package.type === 'urgent'" label color="red lighten-4" text-color="red darken-2">{{ $t('t_urgent') }}</v-chip>
					</template>

					<!-- Days -->
					<template v-slot:item.days="{ item }">
						{{ item.package.days }}
					</template>

					<!-- Price -->
					<template v-slot:item.price="{ item }">
						<div class="font-weight-bold">{{ $getCurrency(item.amount, item.currency, item.locale) }}</div>
					</template>

					<!-- Date -->
					<template v-slot:item.date="{ item }">
						{{ $ago(item.created_at) }}
					</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<!-- Pending -->
						<v-btn text color="#f6ac3e" v-if="item.isPending">
							{{ $t('t_pending') }}
						</v-btn>
						<!-- Accepted -->
						<v-btn text color="#2bd768" v-else-if="item.isAccepted">
							{{ $t('t_accepted') }}
						</v-btn>
						<!-- Declined -->
						<v-btn text color="#ff4141" v-else-if="item.isRefused">
							{{ $t('t_declined') }}
						</v-btn>
						<!-- isSucceed -->
						<v-btn text color="#2bd768" v-else-if="item.isSucceed">
							{{ $t('t_succeeded') }}
						</v-btn>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<div class="justify-center px-0">
							<!-- Accept -->
							<v-tooltip top v-if="item.isPending && $owgate.allow($auth.user, 'access', 'subscriptions')">
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" class="mr-2" color="success" @click="accept([item], payments.indexOf(item))"> check_circle </v-icon> 
								</template>
								<span>{{ $t('t_accept_payment') }}</span>
							</v-tooltip>

							<!-- Decline -->
							<v-tooltip top v-if="item.isPending && $owgate.allow($auth.user, 'access', 'subscriptions')">
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" color="error" @click="decline([item], payments.indexOf(item))"> cancel </v-icon> 
								</template>
								<span>{{ $t('t_decline_payment') }}</span>
							</v-tooltip>
						</div>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="paymentsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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

			let response = await $axios.get('administrator/deals/payments')
			return {
				paymentsData: response.data,
				payments: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_package'), value: 'package', sortable: false, align: 'center'},
		          	{ text: this.$t('t_head_days'), value: 'days', sortable: false, align: 'center'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_date'), value: 'date', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_deals_payments'),
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
				.get('administrator/deals/payments?page=' + page)
				.then(response => {
					this.selected     = []
					this.paymentsData = response.data
					this.payments     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading      = false
				})
			},

			preview: function(deal) {
		   		if (deal.photosNumber == 0) {
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png')
		        }else{
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg')
		        }
		   	},

			accept: function(payments = this.selected, index = null) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'access', 'subscriptions')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/deals/payments/options/accept', {
					payments: payments
				})
				.then(response => {
					if (index !== null) {
						this.payments[index].isRefused  = false
						this.payments[index].isAccepted = true
						this.payments[index].isPending  = false
					}else{
						vm.selected.forEach(function (selectedPayment, selectedIndex) {
							vm.payments.forEach(function(value, ind) {
								if (selectedPayment.unique_id === value.unique_id) {
									vm.payments[ind].isRefused  = false
									vm.payments[ind].isAccepted = true
									vm.payments[ind].isPending  = false
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_payments_accespted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
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
			},

			decline: function(payments = this.selected, index = null) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'access', 'subscriptions')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/deals/payments/options/decline', {
					payments: payments
				})
				.then(response => {
					if (index !== null) {
						this.payments[index].isRefused  = true
						this.payments[index].isAccepted = false
						this.payments[index].isPending  = false
					}else{
						vm.selected.forEach(function (selectedPayment, selectedIndex) {
							vm.payments.forEach(function(value, ind) {
								if (selectedPayment.unique_id === value.unique_id) {
									vm.payments[ind].isRefused  = true
									vm.payments[ind].isAccepted = false
									vm.payments[ind].isPending  = false
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_payments_declined'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
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