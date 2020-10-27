<template>
	<v-app id="inspire">
		
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>
		
		<v-content>
			<v-container fluid grid-list-xl>
				<v-layout align-center wrap>

					<v-flex xs12 class="my-3">
						<div class="text-center">
							<h1 class="display-1 text-uppercase font-weight-black mb-5">{{ $t('t_recent_deals') }}</h1>
						</div>
					</v-flex>

					<!-- Deals -->
					<v-flex xs12 v-for="(deal, index) in deals" :key="index">
						<v-card class="m-card" :class="deal.isUpgraded && deal.upgradedTo === 'highlight' ? 'deal-highlight' : ''">
							<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
							<v-container fluid grid-list-xl>
								<v-layout>
									<v-flex xs4>
										<v-img
											@click="$router.push('/listing/' + deal.unique_slug)"
											:src="preview(deal)"
											height="80px"
											contain
											></v-img>
									</v-flex>
									<v-flex xs8 class="pr-4 pl-0">
										<v-card-title primary-title class="pa-0">
											<div>
												<div class="deal-title"><span class="deal-urgent" v-if="deal.isUpgraded && deal.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span> {{ deal.title }}</div>
												<div class="deal-description">{{ $description(deal.description) }}</div>
											</div>
										</v-card-title>
									</v-flex>
								</v-layout>
							</v-container>
							<v-divider light></v-divider>
							<v-card-actions class="py-0 px-2">

								<v-list-item class="grow deal-avatar">
									<v-list-item-avatar size="25px">
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

				    <!-- Pagination -->
					<v-flex xs12>
						<div class="text-center pt-2">
							<pagination :data="dealsData" @pagination-change-page="getNextPage" :limit=-1 :align="!$vuetify.rtl ? 'right' : 'left'">
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
	export default {
		layout: 'default/main',

		async asyncData ({ $axios }) {
			let response = await $axios.get('browse/deals')
			return {
				deals: response.data.deals.data,
				dealsData: response.data.deals,
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
	  			title: this.$t('t_recent_deals'),
	  			titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.$t('t_recent_deals')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home('browse/deals') },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.$t('t_recent_deals')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
	  	},

	  	methods: {
	  		getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('browse/deals?page=' + page)
				.then(response => {
					this.dealsData  = response.data.deals
					this.deals      = response.data.deals.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
				.catch(error => {
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading    = false
				})
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
	  	},
	}
</script>