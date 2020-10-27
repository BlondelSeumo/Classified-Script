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

					<v-flex xs12 class="my-3 pt-5">
						<div class="text-center">
							<h1 class="pt-5 display-1 text-uppercase font-weight-black mb-5">{{ $t('t_recent_deals') }}</h1>
						</div>
					</v-flex>

					<!-- Deals -->
					<v-flex xs12 sm6 md6 lg3 class="mb-12" v-for="(deal, index) in deals" :key="index">
				        <v-card class="m-card ad-box" text :class="deal.isUpgraded && deal.upgradedTo === 'highlight' ? 'deal-highlight' : ''">
							<v-img cover height="230px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="preview(deal)">
								<v-container fill-height class="pa-0">
									<v-layout align-center justify-center>
										<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
								    </v-layout>
								</v-container>
							</v-img>
							<v-card-title>
								<div class="text-xs-left text-truncate">
									<span class="deal-urgent" v-if="deal.isUpgraded && deal.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span>
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

				    <!-- Pagination -->
					<v-flex xs12>
						<div class="text-center pt-2">
					      	<pagination :data="dealsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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