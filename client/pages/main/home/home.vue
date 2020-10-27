<template>
  <v-app id="inspire" class="index-content">

		<!-- Loader bar -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
			<v-container fluid grid-list-xl>
				<v-layout wrap>

					<!-- Featured deals -->
					<section style="width: 100%" v-if="deals.featured.length !== 0">
						<v-flex xs12 class="mb-4 text-center">
							<h2 class="headline font-weight-black text-uppercase">{{ $t('t_featured_deals') }}</h2>
						</v-flex>

						<!-- Deals -->
						<div v-swiper:mySwiper="dealsSwiper">
						    <div class="swiper-wrapper">
						      	<div class="swiper-slide" v-for="deal in deals.featured" :key="deal.id">
						        	<v-flex xs12 class="mb-12">
								        <v-card class="m-card ad-box" flat>
											<v-img cover height="230px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="preview(deal)">
												<v-container fill-height class="pa-0">
													<v-layout align-center justify-center>
														<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
												    </v-layout>
												</v-container>
											</v-img>
											<v-card-title>
												<div class="text-xs-left text-truncate">
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
														<span class="deal-price" v-if="deal.price && deal.currency && deal.locale">{{ $getCurrency(deal.price, deal.currency, deal.locale, deal.id) }}</span>
													</v-layout>
												</v-list-item>
											</v-card-actions>
										</v-card>
								    </v-flex>
						      	</div>
						    </div>
						    <div class="swiper-button-prev" slot="button-prev">
								<i class="mdi mdi-chevron-right" v-if="$vuetify.rtl"></i>
								<i class="mdi mdi-chevron-left" v-else></i>
							</div>
							<div class="swiper-button-next" slot="button-next">
								<i class="mdi mdi-chevron-left" v-if="$vuetify.rtl"></i>
								<i class="mdi mdi-chevron-right" v-else></i>
							</div>
						</div>
					</section>

					<!-- Shops -->
					<section style="width: 100%">
					  	<v-flex xs12 class="mb-4 text-center">
							<h2 class="headline font-weight-black text-uppercase">{{ $t('t_popular_shops') }}</h2>
							<v-btn rounded depressed dark color="#24252a" class="px-5 mt-3" to="browse/shops">
					  			{{ $t('t_browse_shops') }}
					  		</v-btn>
						</v-flex>

						<!-- Shops -->
						<div v-swiper:mySwiperr="shopsSwiper">
						    <div class="swiper-wrapper">
						      	<div class="swiper-slide" v-for="shop in shops" :key="shop.id">
						        	<v-flex xs12 class="mb-12">
										<v-card class="m-card store-card" flat>
											<v-img :aspect-ratio="16/9" height="150" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="cover(shop.cover)"  class="text-center">
												<v-container fill-height class="pa-0">
													<v-layout align-center justify-center>
														<nuxt-link :to="'/shop/' + shop.store">
															<v-avatar size="60" color="grey lighten-4" class="my-4">
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
											    <div class="pb-4"></div>

											    <div class="follow-btn">
											    	<nuxt-link :to="'/shop/' + shop.store">
												    	<v-tooltip top>
															<template v-slot:activator="{ on }">
																<v-btn v-on="on" icon text color="#c0c0c0" ripple>
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
						      	</div>
						    </div>
						    <div class="swiper-button-prev" slot="button-prev">
								<i class="mdi mdi-chevron-right" v-if="$vuetify.rtl"></i>
								<i class="mdi mdi-chevron-left" v-else></i>
							</div>
							<div class="swiper-button-next" slot="button-next">
								<i class="mdi mdi-chevron-left" v-if="$vuetify.rtl"></i>
								<i class="mdi mdi-chevron-right" v-else></i>
							</div>
						</div>
					</section>

					<!-- Latest deals -->
					<v-flex xs12 class="mb-4 text-center">
						<h2 class="headline font-weight-black text-uppercase">{{ $t('t_recent_deals') }}</h2>
						<v-btn rounded depressed dark color="#24252a" class="px-5 mt-3" to="/browse/deals">
				  			{{ $t('t_browse_deals') }}
				  		</v-btn>
					</v-flex>

					<!-- Deals -->
					<v-flex xs12 sm6 md6 lg3 class="mb-12" v-for="deal in deals.latest" :key="deal.unique_id">
						<v-skeleton-loader
							transition="fade-transition"
							type="card-avatar"
							class="m-card"
							>
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
						</v-skeleton-loader>
				    </v-flex>

				    <!-- Categories -->
					<v-flex xs12 class="mb-4 text-center">
						<h2 class="headline font-weight-black text-uppercase" v-t="'t_categories'"></h2>
						<v-btn rounded depressed dark color="#24252a" class="px-5 mt-3" v-if="isChilds" @click="categoriesParents">
							<template v-if="$vuetify.rtl">
								<v-icon left dark>mdi-arrow-right</v-icon>
								{{ $t('t_categories') }}
							</template>
							<template v-else>
								<v-icon left dark>mdi-arrow-left</v-icon>
								{{ $t('t_categories') }}
							</template>
				  		</v-btn>
				  		<v-btn rounded depressed dark color="#24252a" class="px-5 mt-3" v-if="isChilds" :to="'/category/' + previous.slug">
						  	<template v-if="$vuetify.rtl">
								{{ $t('t_browse_category', { category: previous.title }) }}
								<v-icon right dark>mdi-arrow-left</v-icon>
							</template>
							<template v-else>
								{{ $t('t_browse_category', { category: previous.title }) }}
								<v-icon right dark>mdi-arrow-right</v-icon>
							</template>
				  		</v-btn>
					</v-flex>

					<v-flex xs3 v-for="(category, index) in categories" :key="index">
						<v-list two-line class="grey lighten-4">
							<v-list-item @click="categoriesChilds(category)">
								<v-list-item-avatar>
									<img v-if="category.icon" :src="$homeApi('storage/app/' + category.icon)">
									<v-icon class="grey lighten-1 white--text" v-else>mdi-buffer</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title class="category-name">{{ category.title }}</v-list-item-title>
									<v-list-item-subtitle class="category-stats">{{ category.deals_count > 0 ? $t('t_deals_counter', {number: category.deals_count}) : $t('t_deal_counter', {number: category.deals_count}) }}</v-list-item-subtitle>
								</v-list-item-content>
								<v-list-item-action v-if="category.childs_count !== 0">
									<v-icon v-if="$vuetify.rtl">mdi-arrow-left</v-icon>
									<v-icon v-else>mdi-arrow-right</v-icon>
								</v-list-item-action>
							</v-list-item>
						</v-list>
					</v-flex>

				</v-layout>
			</v-container>
		</v-content>

	</v-app>
</template>

<script>
	export default {
	  	layout: (ctx) => ctx.isMobile ? 'mobile/main' : 'default/main',

	  	async asyncData({ $axios }) {
	  		let response = await $axios.get('home')
	  		return {
	  			deals: {
	  				featured: response.data.featured,
	  				latest: response.data.latest
	  			},
	  			shops: response.data.shops,
	  			categories: response.data.categories,
	  			seo: {
	  				title: response.data.seo.title,
	  				tagline: response.data.seo.tagline,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon
	  			}
	  		}
	  	},

	  	data: function() {
	  		return {
	  			dealsSwiper: {
					slidesPerGroup: 4,
	      			loopFillGroupWithBlank: true,
			        slidesPerView: 4,
			        spaceBetween: 20,
		          	//loop: true,
		          	breakpoints: {
			            1024: {
			              slidesPerView: 4,
			              spaceBetween: 40
			            },
			            768: {
			              slidesPerView: 3,
			              spaceBetween: 30
			            },
			            640: {
			              slidesPerView: 2,
			              spaceBetween: 20
			            },
			            320: {
			              slidesPerView: 1,
			              spaceBetween: 10
			            }
		          	},
		          	navigation: {
			            nextEl: '.swiper-button-next',
			            prevEl: '.swiper-button-prev'
			        },
			        autoplay: {
					    delay: 2500,
					    disableOnInteraction: true
				  	}
		        },
		        shopsSwiper: {
					slidesPerGroup: 4,
	      			loopFillGroupWithBlank: true,
			        slidesPerView: 4,
			        spaceBetween: 20,
		          	//loop: true,
		          	breakpoints: {
			            1024: {
			              slidesPerView: 4,
			              spaceBetween: 40
			            },
			            768: {
			              slidesPerView: 3,
			              spaceBetween: 30
			            },
			            640: {
			              slidesPerView: 2,
			              spaceBetween: 20
			            },
			            320: {
			              slidesPerView: 1,
			              spaceBetween: 10
			            }
		          	},
		          	navigation: {
			            nextEl: '.swiper-button-next',
			            prevEl: '.swiper-button-prev'
			        },
			        autoplay: {
					    delay: 2500,
					    disableOnInteraction: true
				  	}
				},
				slideshow: {
		          	pagination: {
			            el: '.swiper-pagination'
			        },
			        autoplay: true
		        },
		        previous: null,
		        isChilds: false,
		        loading: false,
	  		}
	  	},

		head () {
		    return { 
		    	title: this.seo.title,
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.tagline}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.seo.title} ${this.seo.separator} ${this.seo.tagline}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.$store.state.settings.logo.white ? this.$homeApi(`storage/app/${this.$store.state.settings.logo.white}`) : this.$homeApi(`storage/app/uploads/default/settings/logo/white.png`) },
			      	{ property: 'og:url', content: this.$home() },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.seo.title} ${this.seo.separator} ${this.seo.tagline}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.$store.state.settings.logo.white ? this.$homeApi(`storage/app/${this.$store.state.settings.logo.white}`) : this.$homeApi(`storage/app/uploads/default/settings/logo/white.png`) }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon },
      				{ rel: 'canonical', href: this.$home() },
      			]
		    }
		},

		methods: {
			categoriesChilds: function(category) {
				if (category.childs_count === 0) {
					return this.$router.push('/category/' + category.slug)
				}
				this.loading  = true
				this.previous = category
				this.$axios
				.post('ajax/fetch/categories/childs', {
					id: category.id
				})
				.then(response => {
					this.isChilds   = true
					this.categories = response.data
					this.loading    = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
				})
			},

			categoriesParents: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/categories')
				.then(response => {
					this.isChilds   = false
					this.categories = response.data
					this.loading    = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
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

			cover: function(cover) {
				if (cover === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-cover.png')
				}else{
					return this.$homeApi('storage/app/' + cover)
				}
			},

			logo: function(logo) {
				if (logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			}
		}
	}
</script>