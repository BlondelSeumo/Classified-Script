<template>
  	<v-app id="inspire">

  		<!-- Laoder -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

  		<v-content>
  			<vue-stripe-checkout
		      	ref="checkoutRef"
		      	:image="plan.icon ? $homeApi('storage/app/' + plan.icon) : false"
		      	:name="plan.title"
		      	:description="plan.description"
		      	:currency="plan.currency"
		      	:amount=amount
		      	:allow-remember-me="true"
		      	:auto-open-modal="true"
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
		layout: 'default/main',

		middleware: 'auth',

		async asyncData ({ $axios, params }) {
			let response = await $axios.post(`pricing/checkout/${params.plan}`)
			return {
				plan: response.data.plan,
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
				dontClose: false,
				loading: true
			}
		},

		head () {
			return {
				title: this.plan.title,
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.plan.description },
			      	{ name: 'robots', content: 'index, follow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		computed: {
			amount() {
				return this.plan.price * 100
			}
		},

		methods: {
			async checkout () {
		      	const { token, args } = await this.$refs.checkoutRef.open()
		    },	

		    done ({token, args}) {
		    	this.dontClose = true
		    	this.loading   = true
		      	this.$axios
		      	.post('subscription/create/account', {
		      		token : token,
		      		plan: this.plan.slug
		      	})
		      	.then(response => {
		      		if (response.data.status === "paid") {
		      			this.$toasted.show(this.$t('t_toasted_upgrade_requires_review'), {
							icon: 'done_all',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
		      			this.$router.push({name: 'mainIndex'})
		      		}
		      		this.loading = false
		      	})
		      	.catch(error => {
		      		if (error.response.data.status === "not_found") {
		      			this.$toasted.error(this.$t('t_toasted_plan_not_found'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
		      		}else if (error.response.data.status === "failed") {
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
		      	this.loading = false
		    },

		    closed () {
		    	if (!this.dontClose) {
		    		this.$router.push({name: 'mainPricing'})
		    	}
		    },

		    canceled () {
		      	this.$router.push({name: 'mainPricing'})
		    }
		}
	}
</script>