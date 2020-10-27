<template>
	<v-app id="inspire">
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap>

      				<!-- Deal -->
          			<v-flex xs3>
				        <v-card class="m-card ad-box" flat :class="deal.isUpgraded && deal.upgradedTo === 'highlight' ? 'deal-highlight' : ''">
							<v-img cover height="230px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="preview(deal)">
								<v-container fill-height class="pa-0">
									<v-layout align-center justify-center>
										<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
								    </v-layout>
								</v-container>
							</v-img>
							<v-card-title>
								<div class="text-xs-left text-truncate">
									<span class="deal-urgent" v-if="deal.isUpgraded && deal.upgradedTo === 'urgent'">urgent</span>
									<nuxt-link class="deal-title" :to="'/listing/' + deal.unique_slug">{{ deal.title }}</nuxt-link>
								</div>
							</v-card-title>
							<v-card-actions>
								<v-list-item class="grow deal-avatar px-2">
									<v-list-item-avatar color="white" size="25px" :class="$vuetify.rtl ? 'ml-2' : 'mr-2'">
										<v-img :src="avatar(deal.user.avatar)"></v-img>
									</v-list-item-avatar>
									<v-list-item-content>
										<v-list-item-title class="deal-username">{{ deal.user.username }}</v-list-item-title>
									</v-list-item-content>
									<v-layout align-center justify-end style="margin-bottom: 14px">
										<span class="deal-price" v-if="deal.price && deal.currency && deal.locale">{{ $getCurrency(deal.price, deal.currency, deal.locale) }}</span>
									</v-layout>
								</v-list-item>
							</v-card-actions>
						</v-card>
					</v-flex>

					<!-- Packages -->
					<v-flex xs9>
						<v-container fluid class="pa-0">
							<v-layout column wrap align-center>
								<v-flex xs12 style="width: 100%">
									<v-container grid-list-xl class="pa-0">
										<v-layout row wrap align-start justify-start>

											<!-- Packages --> 
											<v-flex xs2 v-for="(p, index) in packages" :key="index">
												<v-card flat style="box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.08);" class="cursor-pointer pt-3" :color="selected === p.id ? '#878d8b' : 'white'" :class="selected === p.id ? 'selected-package' : ''">
													<v-icon @click="selected = null" v-show="p.id === selected" style="position:absolute;top:10px;right:10px;" size="16px" color="white">mdi-close</v-icon>
													<v-tooltip top>
														<template v-slot:activator="{ on }">
															<v-icon v-on="on" @click="selected = null" v-show="selected !== p.id" style="position:absolute;top:10px;left:10px;" size="16px" color="#7a7a7a">mdi-information-variant</v-icon>
														</template>
														<span>{{ p.title }}</span>
													</v-tooltip>
													<v-card-title @click="select(p)" primary-title class="layout justify-center pb-4">
														<v-chip v-show="p.type === 'highlight'" small label color="amber lighten-4" text-color="amber darken-2">{{ $t('t_highlight') }}</v-chip>
														<v-chip v-show="p.type === 'urgent'" small label color="red lighten-4" text-color="red darken-2">{{ $t('t_urgent') }}</v-chip>
														<v-chip v-show="p.type === 'featured'" small label color="light-green lighten-4" text-color="light-green darken-2">{{ $t('t_featured') }}</v-chip>
													</v-card-title>
													<v-card-text @click="select(p)" class="text-center pb-0">
														<span class="package-price pb-3" style="display: block;font-family:'Open sans',sans-serif;font-size:16px!important;letter-spacing:1px;font-weight:700;">{{ $getCurrency(p.price, p.currency, p.locale) }}</span>
														<div class="grey--text">
															<span class="package-days" style="font-family:Hind,sans-serif;font-weight:500;text-transform:uppercase;font-size:10px;letter-spacing:1px;color:#595959;">{{ p.days | numeralFormat }} days</span><br/>
															<br/>
														</div>
													</v-card-text>
												</v-card>
											</v-flex>

										</v-layout>
									</v-container>
								</v-flex>
							</v-layout>
						</v-container>
					</v-flex>

				</v-layout>
			</v-container>

			<!-- Checkout -->
			<vue-stripe-checkout
				ref="checkout"
				:image="stripe.image"
				:name="stripe.name"
				:description="stripe.description"
				:currency="stripe.currency"
				:amount="stripe.amount"
				:allow-remember-me="true"
				@done="done"
				@opened="opened"
				@closed="closed"
				@canceled="canceled"
				></vue-stripe-checkout>

		</v-content>
	</v-app>
</template>

<script>
	export default {
		middleware: 'auth',

		layout: 'default/main',

		async asyncData({ $axios, params, query }) {
			let response = await $axios.post('account/deals/promote', { id: params.id, package: query.package })
			return {
				packages: response.data.packages,
				deal: response.data.deal,
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
				stripe: {
					image: null,
		      		name: null,
		      		description: null,
		      		currency: null,
		      		amount: null,
				},
		      	selected: null,
		      	loading: false,
		      	pageLoaded: false,
		    }
		},

		head() {
			return {
				title: this.$t('t_promote_your_deal'),
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

		watch: {
			current: {
				handler: function(object) {
					this.select(object)
				},
				deep: true
			}
		},

		methods: {
			select: function(plan) {
				this.selected           = plan.id
				this.stripe.image       = this.preview(this.deal)
				this.stripe.name        = this.$t('t_promote_your_deal')
				this.stripe.description = plan.title
				this.stripe.currency    = plan.currency
				this.stripe.amount      = plan.price * 100
				this.$nextTick(function () {
			        this.checkout()
			    })
			},

			async checkout () {
		      const { token, args } = await this.$refs.checkout.open();
		    },

		    done ({token, args}) {
		      	this.loading = true
		      	this.$axios
		      	.post('account/deals/promote/checkout', {
		      		id: this.deal.unique_id,
		      		package: this.selected,
		      		token: token.id
		      	})
		      	.then(response => {
		      		if (response.data.status === 'paid') {
		      			this.$toasted.show(this.$t('t_toasted_payment_made'), {
							icon: 'done_all',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
		      			this.$router.push('/account/deals')
		      		}
		      		this.loading = false
		      	})
		      	.catch(error => {
		      		if (error.response.data.status === 'failed') {
		      			this.$toasted.error(this.$t('t_toasted_payment_failed'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
		      		}else{
		      			this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
		      		}
		      		this.loading = false
		      	})
		    },

		    opened () {
		      // do stuff 
		    },
		    closed () {
		      // do stuff 
		    },
		    canceled () {
		      // do stuff 
		    },

		    preview: function(deal) {
				if (deal.photosNumber === 0) {
					return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png')
				}else{
					return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg')
				}
			},

			avatar: function(avatar) {
				if (avatar === null) {
					return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
				}else{
					return this.$homeApi('storage/app/' + avatar)
				}
			},
		}
	}
</script>