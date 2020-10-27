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
							<h1 class="display-1 text-uppercase font-weight-black mb-5">{{ $t('t_browse_shops') }}</h1>
						</div>
					</v-flex>

					<!-- Shops -->
					<v-flex xs6 v-for="(shop, index) in shops" :key="index">
						<v-card class="m-card store-card" flat>
							<v-img :aspect-ratio="16/9" height="100" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="cover(shop.cover)"  class="text-center">
								<v-container fill-height class="pa-0">
									<v-layout align-center justify-center>
										<nuxt-link :to="'/shop/' + shop.store">
											<v-avatar size="60" color="grey lighten-4" class="my-4" :class="!shop.logo ? 'no-shop-logo' : ''">
									          	<img :src="logo(shop.logo)">
									        </v-avatar>
										</nuxt-link>
								    </v-layout>
								</v-container>
							</v-img>
							<div class="text-center px-3 py-3">
								<div class="pb-2">
									<nuxt-link :to="'/shop/' + shop.store" class="store-title">
										{{ shop.title }}
							    	</nuxt-link>
							        <div class="tagline">{{ shop.subtitle }}</div>
							    </div>
							    <div class="follow-btn">
							    	<nuxt-link :to="'/shop/' + shop.store">
								    	<v-tooltip top>
											<template v-slot:activator="{ on }">
												<v-btn icon text color="#c0c0c0" ripple v-on="on">
													<v-icon size="20px">mdi-chevron-right</v-icon>
												</v-btn>
											</template>
								    		<span>{{ $t('t_visit_shop', {shop: shop.title}) }}</span>
								    	</v-tooltip>
							    	</nuxt-link>
							    </div>
							</div>
						</v-card>
					</v-flex>

					<!-- Pagination -->
					<v-flex xs12>
						<div class="text-center pt-2">
							<pagination :data="shopsData" @pagination-change-page="getNextPage" :limit=-1 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			let response = await $axios.get('browse/shops')
			return {
				shopsData: response.data.shops,
				shops: response.data.shops.data,
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
	  			title: this.$t('t_browse_shops'),
	  			titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.$t('t_browse_shops')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home('browse/shops') },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.$t('t_browse_shops')} ${this.seo.separator} ${this.seo.title}` },
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
				.get('browse/shops?page=' + page)
				.then(response => {
					console.log(response.data)
					this.shopsData  = response.data.shops
					this.shops      = response.data.shops.data
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

			cover: function(cover) {
				if (cover === null) {
					if (this.$vuetify.theme.dark) {
						return this.$homeApi('storage/app/uploads/default/store/no-cover-dark.png')						
					}
					return this.$homeApi('storage/app/uploads/default/store/no-cover.png')
				}else{
					return this.$homeApi('storage/app/' + cover)
				}
			},

			logo: function(logo) {
				if (logo === null) {
					if (this.$vuetify.theme.dark) {
						return this.$homeApi('storage/app/uploads/default/store/no-logo-dark.png')						
					}
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			}
	  	}
	}
</script>