<template>
  	<v-app id="inspire">

  		<!-- Laoder -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

  		<v-content>
			<v-container fluid grid-list-xl>
				<v-layout align-center wrap>

					<v-flex xs12>
						<div class="text-center">
							<h1 class="display-1 text-uppercase font-weight-black mb-5">{{ $t('t_select_plan') }}</h1>
						</div>
					</v-flex>

					<v-flex xs12>
						<v-container grid-list-xl>
							<v-layout row wrap>

								<template v-for="(plan, index) in plans">
									<v-flex xs6 sm4 :key="index" v-if="plan.price">
										<v-card class="m-card mb-3 plan" flat>
											<v-card-title primary-title class="layout justify-center pb-0">
												<div class="plan-title">{{ plan.title }}</div>
												
											</v-card-title>
											<v-card-text class="text-center px-0">

												<!-- Price -->
												<div class="plan-price">
													<div class="plan-price-currency">
														<div class="plan-currency">{{ plan.currency }}</div>{{ plan.price }}
													</div>
												</div>

												<!-- Time -->
												<div class="plan-billing">{{ $t('t_billed', {frequency: frequency(plan.frequency)}) }}</div>

												<!-- Checkout -->
												<div class="text-center py-4">
													<v-btn block text :color="plan.isRecommended ? '#ff2e2e' : '#24b8ff'" depressed dark class="px-5 plan-get-started no-border-radius" :to="`/pricing/checkout/${plan.slug}`">{{ $t('t_get_started') }}</v-btn>
												</div>

												<!-- Features -->
												<v-list dense class="transparent" :class="$vuetify.rtl ? 'text-right' : 'text-left'">

													<v-list-item class="px-1">
														<v-list-item-avatar class="ml-0 mr-0">
															<v-icon size="20px" color="green accent-3">mdi-check</v-icon>
														</v-list-item-avatar>
														<v-list-item-content>
															<span class="plan-feature plan-feature-enabled">{{ $t('t_powerful_dashboard') }}</span>      
														</v-list-item-content>
													</v-list-item>

													<v-list-item class="px-1">
														<v-list-item-avatar class="ml-0 mr-0">
															<v-icon :color="plan.isStores ? 'green accent-3' : 'grey'">{{ plan.isStores ? 'check' : 'close' }}</v-icon>
														</v-list-item-avatar>
														<v-list-item-content>
															<span class="plan-feature" :class="plan.isStores ? 'plan-feature-enabled' : 'plan-feature-disabled'">{{ $t('t_create_shops') }}</span>      
														</v-list-item-content>
													</v-list-item>

													<v-list-item class="px-1">
														<v-list-item-avatar class="ml-0 mr-0">
															<v-icon :color="plan.isWorkingHours ? 'green accent-3' : 'grey'">{{ plan.isWorkingHours ? 'check' : 'close' }}</v-icon>
														</v-list-item-avatar>
														<v-list-item-content>
															<span class="plan-feature" :class="plan.isWorkingHours ? 'plan-feature-enabled' : 'plan-feature-disabled'">{{ $t('t_business_hours') }}</span>
														</v-list-item-content>
													</v-list-item>

													<v-list-item class="px-1">
														<v-list-item-avatar class="ml-0 mr-0">
															<v-icon :color="plan.isCustomWatermark ? 'green accent-3' : 'grey'">{{ plan.isCustomWatermark ? 'check' : 'close' }}</v-icon>
														</v-list-item-avatar>
														<v-list-item-content>
															<span class="plan-feature" :class="plan.isCustomWatermark ? 'plan-feature-enabled' : 'plan-feature-disabled'">{{ $t('t_custom_watermark') }}</span>
														</v-list-item-content>
													</v-list-item>

													<v-list-item class="px-1">
														<v-list-item-avatar class="ml-0 mr-0">
															<v-icon :color="plan.isAutoshare ? 'green accent-3' : 'grey'">{{ plan.isAutoshare ? 'check' : 'close' }}</v-icon>
														</v-list-item-avatar>
														<v-list-item-content>
															<span class="plan-feature" :class="plan.isAutoshare ? 'plan-feature-enabled' : 'plan-feature-disabled'">{{ $t('t_social_autoshare') }}</span>
														</v-list-item-content>
													</v-list-item>

													<v-list-item class="px-1">
														<v-list-item-avatar class="ml-0 mr-0">
															<v-icon size="20px" color="green accent-3">mdi-check</v-icon>
														</v-list-item-avatar>
														<v-list-item-content>
															<span class="plan-feature plan-feature-enabled">{{ $t('t_24_support') }}</span>      
														</v-list-item-content>
													</v-list-item>

												</v-list>
											</v-card-text>
										</v-card>
									</v-flex>
								</template>

							</v-layout>
						</v-container>
					</v-flex>
				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/main',

		async asyncData ({ $axios }) {
			let response = await $axios.post('pricing')
			return {
				plans: response.data.plans,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
	  				image: response.data.seo.image,
				}
			}
		},

		data: function() {
			return {
			    loading: false
			}
		},

		head () {
			return {
				title: this.$t('t_pricing'),
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.$t('t_pricing')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home('pricing') },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.$t('t_pricing')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},
		
		methods: {
			icon: function(icon) {
				return this.$homeApi('storage/app/' + icon)
			},

			frequency(frequency) {
				switch (frequency) {
					case 'daily':
						return this.$t('t_daily')
						break;
					case 'weekly':
						return this.$t('t_weekly')
						break;
					case 'monthly':
						return this.$t('t_monthly')
						break;
					case 'yearly':
						return this.$t('t_yearly')
						break;
				
					default:
						break;
				}
			}
		}
	}	
</script>

<style>
	.plan-title {
		font-family: Source Sans Pro, 'Noto Kufi Arabic',Helvetica,Arial,sans-serif;
		-webkit-font-smoothing: antialiased;
		font-stretch: normal;
		line-height: 30px;
		font-size: 24px;
		font-weight: 300
	}
	.plan-price {
	    display: -webkit-box;
	    display: -ms-flexbox;
	    display: flex;
	    -webkit-box-pack: center;
	    -ms-flex-pack: center;
	    justify-content: center;
	    padding: 24px 0 6px;
	    font-family: Source Sans Pro, 'Noto Kufi Arabic',Helvetica,Arial,sans-serif;
   		-webkit-font-smoothing: antialiased;
    	font-stretch: normal;
	}
	.plan-price-currency {
	    position: relative;
	    font-size: 40px;
	    font-weight: 300;
	    line-height: 1;
	    color: #212121;
	}
	.theme--dark .plan-price-currency {
		color: #fff
	}
	.plan-currency {
	    position: absolute;
	    left: -6px;
	    top: -6px;
	    -webkit-transform: translate(-100%,10px);
	    -ms-transform: translate(-100%,10px);
	    transform: translate(-100%,10px);
	    color: #757575;
	    line-height: 18px;
	    font-size: 14px;
	    font-weight: 400
	}
	.theme--dark .plan-currency {
		color: #cdcdcd
	}
	.plan-billing {
		color: #757575;
		text-align: center;
		line-height: 18px;
		font-size: 12px;
		font-weight: 400;
		font-family: Source Sans Pro, 'Noto Kufi Arabic',Helvetica,Arial,sans-serif;
		-webkit-font-smoothing: antialiased;
		font-stretch: normal;
	}
	.theme--dark .plan-billing {
		color: #e6e6e6;
	}
.plan-get-started {
	font-family: Source Sans Pro, 'Noto Kufi Arabic',Helvetica,Arial,sans-serif;
    -webkit-font-smoothing: antialiased;
    font-stretch: normal;
    font-weight: 700 !important;
}
	.plan-feature {
		line-height: 18px;
		font-size: 10px;
		font-weight: 700;
		font-family: Source Sans Pro, 'Noto Kufi Arabic',Helvetica,Arial,sans-serif;
		-webkit-font-smoothing: antialiased;
		font-stretch: normal;
	}
	.plan-feature-enabled {
		color: #212121 !important;
	}
	.theme--dark .plan-feature-enabled {
		color: #ffffff !important
	}
	.plan-feature-disabled {
		color: #979797 !important;
	}
	.plan .v-list-item {
		height: 40px
	}
</style>