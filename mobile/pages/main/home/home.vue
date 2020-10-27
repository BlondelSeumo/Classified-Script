<template>
  <v-app id="inspire" class="index-content">

		<!-- Loader bar -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content class="home_content_wrap">
			<v-container fluid grid-list-xl>
				<v-layout wrap>

					<!-- Featured deals -->
					<v-flex xs12 class="mb-2" v-if="deals.featured.length !== 0">
						<div class="section-title">
		                    <h3>{{ $t('t_featured_deals') }}</h3>
		                    <div class="more">
		                    	<nuxt-link to="/browse/deals">{{ $t('t_see_more') }}</nuxt-link>
		                    </div>
		                </div>
					</v-flex>

					<div v-swiper:mySwiper="dealsSwiper">
						<div class="swiper-wrapper">
							<div class="swiper-slide" v-for="(deal, index) in deals.featured" :key="index">
								<v-flex xs12 class="mb-5">
									<v-card class="m-card ad-box" flat>
										<v-img cover height="100px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="preview(deal)">
											<v-container fill-height class="pa-0">
												<v-layout align-center justify-center>
													<nuxt-link :to="'/listing/' + deal.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
												</v-layout>
											</v-container>
										</v-img>
										<v-card-title class="pb-0">
											<div class="text-truncate">
												<nuxt-link class="deal-title" :to="'/listing/' + deal.unique_slug">{{ deal.title }}</nuxt-link>
											</div>
										</v-card-title>
										<v-card-actions>
											<v-list-item class="grow deal-avatar">
												<v-list-item-avatar size="25px">
													<v-img :src="avatar(deal.user.avatar)"></v-img>
												</v-list-item-avatar>
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
							<i class="mdi mdi-chevron-right"></i>
						</div>
						<div class="swiper-button-next" slot="button-next">
							<i class="mdi mdi-chevron-left"></i>
						</div>
					</div>

					<!-- Categories -->
					<v-flex xs12 class="mb-2">
						<div class="section-title">
		                    <h3>{{ $t('t_categories') }}</h3>
		                    <div class="more" v-if="isChilds">
		                    	<nuxt-link :to="'/category/' + previous.slug">{{ $t('t_browse_category', {category: previous.title}) }}</nuxt-link>
		                    </div>
		                </div>
					</v-flex>

					<v-flex xs4 sm3 v-for="(category, index) in categories" :key="index">
						<v-card class="m-card px-2 py-3 text-center category-box" @click="categoriesChilds(category)">
							<v-avatar size="45px" class="mb-2">
								<img v-if="category.icon" :src="$homeApi('storage/app/' + category.icon)">
								<v-icon class="grey lighten-1" v-else>mdi-buffer</v-icon>
							</v-avatar>
							<h5 class="pt-2" v-text="category.title"></h5>
						</v-card>
					</v-flex>

					<!-- Shops -->
					<v-flex xs12 class="mb-2 mt-4">
						<div class="section-title">
		                    <h3>{{ $t('t_popular_shops') }}</h3>
		                    <div class="more">
		                    	<nuxt-link to="/browse/shops">{{ $t('t_see_more') }}</nuxt-link>
		                    </div>
		                </div>
					</v-flex>
					<div v-swiper:mySwiperr="shopsSwiper">
						<div class="swiper-wrapper">
							<div class="swiper-slide" v-for="shop in shops" :key="shop.store">
								<v-flex xs12 class="mb-5">
									<v-card class="m-card store-card" flat>
										<v-img :aspect-ratio="16/9" height="100px" :lazy-src="$homeApi('public/images/default/lazy.jpg')" :src="cover(shop.cover)"  class="text-xs-center">
											<v-container fill-height class="pa-0">
												<v-layout align-center justify-center>
													<nuxt-link :to="'/shop/' + shop.store">
														<v-avatar size="40px" color="grey lighten-4" class="my-4">
															<img :src="logo(shop.logo)">
														</v-avatar>
													</nuxt-link>
												</v-layout>
											</v-container>
										</v-img>
										<div class="store-deatils text-center px-3 py-2">
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
														<span>{{ $t('t_visit_shop') }}</span>
													</v-tooltip>
												</nuxt-link>
											</div>
										</div>
									</v-card>
								</v-flex>
							</div>
						</div>
						<div class="swiper-button-prev" slot="button-prev">
							<i class="mdi mdi-chevron-right"></i>
						</div>
						<div class="swiper-button-next" slot="button-next">
							<i class="mdi mdi-chevron-left"></i>
						</div>
					</div>

					<!-- Recent deals -->
					<v-flex xs12 class="mb-2">
						<div class="section-title">
		                    <h3>{{ $t('t_recent_deals') }}</h3>
		                    <div class="more">
		                    	<nuxt-link to="/browse/deals">{{ $t('t_see_more') }}</nuxt-link>
		                    </div>
		                </div>
					</v-flex>

					<v-flex xs12 v-for="deal in deals.latest" :key="deal.unique_id">
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

					<!-- Browse all deals -->
					<v-flex xs12>
						<v-btn depressed nuxt block color="blue lighten-1" dark to="/browse/deals" class="mb-4">{{ $t('t_browse_all_deals') }}</v-btn>
					</v-flex>

					<!-- Post new -->
				  	<v-flex xs12 class="text-center my-5">
      					<div class="subheading font-weight-black text-uppercase mb-3 mt-4">{{ $t('t_do_have_something') }}</div>
      					<div class="body-1 font-weight-light mb-2">{{ $t('t_do_have_something_para') }}</div>
      					<v-btn color="green accent-4" large :to="$auth.loggedIn ? '/create' : '/auth/register'" dark depressed block class="mt-4">
      						{{ $t('t_create_deal') }}
      					</v-btn>
      				</v-flex>

				</v-layout>
			</v-container>
		</v-content>

	</v-app>
</template>

<script>
	export default {
	  	layout: 'default/main',

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