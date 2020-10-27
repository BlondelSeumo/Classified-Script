<template>
	<v-app id="inspire">
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
								<v-toolbar-title class="title">{{ $t('t_subscriptions') }}</v-toolbar-title>
								<v-spacer></v-spacer>

								<!-- Upgrade account -->
					          	<v-btn to="/pricing" depressed color="error" dark>
					            	{{ $t('t_subscribe_renew') }}
					         	</v-btn>

							</v-toolbar>

					        <v-data-table
								:headers="headers"
								:items="subscriptions"
								item-key="id"
				  				hide-default-footer
								disable-pagination
								:no-data-text="$t('t_table_no_data_available')"
								>
								<!-- Plan -->
								<template v-slot:item.plan="{ item }">
									<div class="text-center font-weight-bold text-uppercase black--text">{{ item.plan.title }}</div>
								</template>

								<!-- Price -->
								<template v-slot:item.price="{ item }">
									<div class="text-center font-weight-bold" v-if="item.price && item.currency && item.locale">
										{{ $getCurrency(item.price, item.currency, item.locale) }}
									</div>
								</template>

								<!-- Subscribed at -->
								<template v-slot:item.created="{ item }">{{ $ago(item.subscribed_at) }}</template>

								<!-- Expires -->
								<template v-slot:item.expires="{ item }">{{ $dateString(item.expires_at) }}</template>

								<!-- Status -->
								<template v-slot:item.status="{ item }">
									<!-- Expired -->
									<v-btn text small depressed dark color="#ff4444" v-if="item.isExpired">
										{{ $t('t_expired') }}
									</v-btn>
									<!-- Expires soon -->
									<v-btn text small depressed dark color="warning" v-if="!item.isExpired && item.expiresSoon">
										{{ $t('t_expire_soon') }}
									</v-btn>
									<!-- Active -->
									<v-btn text small depressed dark color="#2ecc71" v-if="!item.isExpired && !item.expiresSoon">
										{{ $t('t_active') }}
									</v-btn>
								</template>
							</v-data-table>
						</v-card>
						<div class="text-center pt-2">
							<pagination :data="subscriptionsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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

		async asyncData ({ $axios }) {
			let response = await $axios.get('account/subscription')
			return {
				subscriptionsData: response.data.subscriptions,
				subscriptions: response.data.subscriptions.data,
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
				loading: false
			}
		},

		head() {
			return {
				title : this.$t('t_subscriptions'),
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
					{ text: this.$t('t_plan'), value: 'plan', sortable: false, align: 'center'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_subscribed_at'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_expires'), value: 'expires', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				axios
				.get('account/subscription?page=' + page)
				.then(response => {
					this.subscriptionsData = response.data.subscriptions
					this.subscriptions     = response.data.subscriptions.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading           = false
				})
			}
		}
	}
</script>