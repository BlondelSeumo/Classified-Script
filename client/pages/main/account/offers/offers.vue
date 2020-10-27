<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>

      				<!-- Account sidebar -->
          			<v-sidebar></v-sidebar>

          			<!-- Account page -->
					<v-flex xs9>
						<v-card class="m-card" flat>
							<v-toolbar color="white" flat>
								<v-toolbar-title class="title">{{ $t('t_received_offers') }}</v-toolbar-title>
								<v-spacer></v-spacer>

								    <!-- Refuse -->
							        <v-fade-transition>
								        <v-tooltip top v-if="selected.length > 0">
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" text icon color="grey darken-3" @click="refuse()">
													<v-icon>mdi-cancel</v-icon>
												</v-btn>
											 </template>
								          	<span>{{ $t('t_refuse') }}</span>
								        </v-tooltip>
								    </v-fade-transition>

								    <!-- Accept -->
							        <v-fade-transition>
								        <v-tooltip top v-if="selected.length > 0">
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" text icon color="grey darken-3" @click="accept()">
													<v-icon>mdi-check-all</v-icon>
												</v-btn>
											</template>
								          	<span>{{ $t('t_accept') }}</span>
								        </v-tooltip>
								    </v-fade-transition>

							</v-toolbar>

					        <v-data-table
								v-model="selected"
								:headers="headers"
								:items="offers"
								item-key="id"
				  				hide-default-footer
								show-select
								disable-pagination
								:no-data-text="$t('t_table_no_data_available')"
								>
								<!-- Deal -->
								<template v-slot:item.deal="{ item }">
									<v-list one-line class="transparent">
										<v-list-item nuxt :to="`/listing/${item.deal.unique_slug}`" target="_blank" :ripple="false">
											<v-list-item-avatar>
												<img :src="preview(item.deal)">
											</v-list-item-avatar>
											<v-list-item-content class="table-wrap-title">
												<v-list-item-title class="table-wrap-title" v-html="item.deal.title"></v-list-item-title>
											</v-list-item-content>
										</v-list-item>
									</v-list>
								</template>

								<!-- Offer price -->
								<template v-slot:item.price="{ item }">
									<div class="text-center font-weight-bold" v-if="item.price && item.currency && item.locale">
										{{ $getCurrency(item.price, item.currency, item.locale) }}
									</div>
								</template>

								<!-- Offer from -->
								<template v-slot:item.from="{ item }">
									<div class="text-center font-weight-bold">{{ item.from.username }}</div>
								</template>

								<!-- Status -->
								<template v-slot:item.status="{ item }">
									<div v-if="item.deleted_at === null">
										<!-- Pending -->
										<v-btn small depressed dark color="warning" block text v-if="item.isPending && !item.isAccepted && !item.isRefused">
											{{ $t('t_pending') }}
										</v-btn>
										<!-- Accepted -->
										<v-btn small block depressed text color="#2ecc71" v-else-if="item.isAccepted">
											{{ $t('t_accepted') }}
										</v-btn>
										<!-- Refused -->
										<v-btn small block depressed text color="#bdc3c7" v-else-if="item.isRefused">
											{{ $t('t_refused') }}
										</v-btn>
									</div>
									<!-- Deleted -->
									<div v-else>
										<v-btn small block depressed text color="error">
											{{ $t('t_deleted') }}
										</v-btn>
									</div>
								</template>

								<!-- Created -->
								<template v-slot:item.created="{ item }">
									{{ $ago(item.created_at) }}
								</template>

								<!-- Options -->
								<template v-slot:item.options="{ item }">
									<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px" v-if="requireAction(item)">
										<template v-slot:activator="{ on }">
											<v-btn small v-on="on" icon>
												<v-icon size="20px" color="grey darken-1">mdi-dots-vertical</v-icon>
											</v-btn>
										</template>
										<v-list dense>

											<!-- Accept -->
											<v-list-item @click="accept([item], offers.indexOf(item))">
												<v-list-item-action>
													<v-icon>mdi-check-all</v-icon>
												</v-list-item-action>
												<v-list-item-content>
													<v-list-item-title>{{ $t('t_accept') }}</v-list-item-title>
												</v-list-item-content>
											</v-list-item>

											<!-- Refuse -->
											<v-list-item @click="refuse([item], offers.indexOf(item))">
												<v-list-item-action>
													<v-icon>mdi-cancel</v-icon>
												</v-list-item-action>
												<v-list-item-content>
													<v-list-item-title>{{ $t('t_refuse') }}</v-list-item-title>
												</v-list-item-content>
											</v-list-item>

										</v-list>
									</v-menu>
								</template>
							</v-data-table>
						</v-card>
						<div class="text-center pt-2">
							<pagination :data="offersData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$megate.allow(app.$auth.user, 'make', 'offers')) {
				redirect('/administrator')
			}

			let response = await $axios.get('account/offers')
			return {
				offersData: response.data.offers,
				offers: response.data.offers.data,
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
				selected: [],
		        dialogs: {
		        	delete: false
		        },
				loading: false
			}
		},

		head () {
			return {
				title: this.$t('t_received_offers'),
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
		          	{ text: this.$t('t_offer_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_offer_from'), value: 'from', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('account/offers?page=' + page)
				.then(response => {
					this.selected   = []
					this.offersData = response.data.offers
					this.offers     = response.data.data.offers
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

		   	requireAction: function(offer) {
		   		if (offer.isPending && !offer.isAccepted && !offer.isRefused) {return true}
		   		return false
		   	},

		   	accept: function(offers = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/offers/options/accept', {
					offers: offers
				})
				.then(response => {
					if (index !== null) {
						this.offers[index].isAccepted = true
					}else{
						vm.selected.forEach(function (selectedOffer, selectedIndex) {
							vm.offers.forEach(function(value, ind) {
								if (selectedOffer.unique_id === value.unique_id) {
									vm.offers[ind].isAccepted = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_offers_accepted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.dialogs.delete = false
					vm.loading        = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			refuse: function(offers = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/offers/options/refuse', {
					offers: offers
				})
				.then(response => {
					if (index !== null) {
						this.offers[index].isRefused = true
					}else{
						vm.selected.forEach(function (selectedOffer, selectedIndex) {
							vm.offers.forEach(function(value, ind) {
								if (selectedOffer.unique_id === value.unique_id) {
									vm.offers[ind].isRefused = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_offers_refused'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.dialogs.delete = false
					vm.loading        = false
				})
				.catch(error => {
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