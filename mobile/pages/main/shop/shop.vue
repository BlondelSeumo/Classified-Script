<template>
	<v-app id="inspire">

		<v-overlay v-model="loading.app" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Message dialog -->
		<v-dialog v-model="dialogs.message" max-width="500px" persistent v-if="$auth.loggedIn && $megate.allow($auth.user, 'contact', 'shops')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_contact_user', {user: shop.title}) }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.message = false" class="mx-0">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>

							<!-- Subject -->
							<v-flex xs12>
								<v-text-field
									v-model="form.subject"
									color="grey lighten-1"
									:label="$t('t_subject')"
									:placeholder="$t('t_enter_subject')"
									counter="100"
									maxlength="100"
									:rules="errors.subject"
									:error="errors.subject ? true : false"
									></v-text-field>
							</v-flex>

								<!-- Message -->
							<v-flex xs12>
								<v-textarea
									v-model="form.message"
									color="grey lighten-1"
									:label="$t('t_message')"
									:placeholder="$t('t_enter_message')"
									counter="750"
									maxlength="750"
									:rules="errors.message"
									:error="errors.message ? true : false"
									no-resize
									required
									></v-textarea>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn :color="$vuetify.theme.dark ? '#bfbfbf' : '#2e3131'" text @click="message" :disabled="loading.message || !form.message || !form.subject" :loading="loading.message">{{ $t('t_send') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-content>
      		<v-container fluid grid-list-xl>
      			<v-layout wrap>

      				<!-- Shop details && map -->
      				<v-flex xs12>
						
						<v-card class="m-card store-card pb-3 mb-4">
							<v-img height="200" :src="cover">
								<v-layout column fill-height>
									<v-spacer></v-spacer>
									<v-card-title class="justify-center">
										<v-avatar class="text-center" :class="!shop.logo ? 'no-shop-logo' : ''" size="70px">
										      <img :src="logo">
										</v-avatar>
									</v-card-title>
								</v-layout>
							</v-img>
							<v-card-title class="px-4">{{ shop.title }}</v-card-title>
							<v-card-text class="px-4">
								<client-only>
									<truncate v-if="shop.description" action-class="text-center caption text-uppercase" :clamp="$t('t_show_more')" :length="350" :less="$t('t_show_less')" type="html" :text="shop.description"></truncate>
								</client-only>
							</v-card-text>
							<v-divider class="mx-4"></v-divider>
							<v-card-text class="px-4 text-center">

								<!-- Phone number -->
								<v-chip label color="#2196f3" v-if="shop.phone" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.phone }}
										<v-icon right size="19">mdi-phone</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-phone</v-icon>
										{{ shop.phone }}
									</div>
								</v-chip>

								<!-- Customers email -->
								<v-chip label color="#2196f3" v-if="shop.customer_email" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.customer_email }}
										<v-icon right size="19">mdi-at</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-at</v-icon>
										{{ shop.customer_email }}
									</div>
								</v-chip>

								<!-- Country -->
								<v-chip label color="#2196f3" v-if="shop.country" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.country.name }}
										<v-icon right size="19">mdi-flag</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-flag</v-icon>
										{{ shop.country.name }}
									</div>
								</v-chip>

								<!-- State -->
								<v-chip label color="#2196f3" v-if="shop.state" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.state.name }}
										<v-icon right size="19">mdi-flag</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-flag</v-icon>
										{{ shop.state.name }}
									</div>
								</v-chip>

								<!-- City -->
								<v-chip label color="#2196f3" v-if="shop.city" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.city.name }}
										<v-icon right size="19">mdi-flag</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-flag</v-icon>
										{{ shop.city.name }}
									</div>
								</v-chip>

								<!-- Address one -->
								<v-chip label color="#2196f3" v-if="shop.address1" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.address1 }}
										<v-icon right size="19">mdi-map</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-map</v-icon>
										{{ shop.address1 }}
									</div>
								</v-chip>

								<!-- Address two -->
								<v-chip label color="#2196f3" v-if="shop.address2" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.address2 }}
										<v-icon right size="19">mdi-map</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-map</v-icon>
										{{ shop.address2 }}
									</div>
								</v-chip>

								<!-- ZIP -->
								<v-chip label color="#2196f3" v-if="shop.zip" class="mb-2 mx-2">
									<div v-if="$vuetify.rtl">
										{{ shop.zip }}
										<v-icon right size="19">mdi-barcode</v-icon>
									</div>
									<div v-else>
										<v-icon left size="19">mdi-barcode</v-icon>
										{{ shop.zip }}
									</div>
								</v-chip>

							</v-card-text>
							<v-card-actions class="follow-btn px-4">
								<v-btn text class="px-4" @click="dialogs.message = true" v-if="$megate.allow($auth.user, 'contact', 'shops')">
									{{ $t('t_contact_us') }}
								</v-btn>
								<v-spacer></v-spacer>
								<v-btn text class="px-4" @click="follow" :disabled="loading.follow" :loading="loading.follow" v-if="$megate.allow($auth.user, 'follow', 'shops') && !isFollowing">
									{{ $t('t_follow_shop', {shop: shop.title}) }}
								</v-btn>
							</v-card-actions>
						</v-card>

						<v-card class="m-card mb-4" text v-if="shop.latitude && shop.longitude">
							<iframe
								style="margin-bottom: -6px;" 
							  	width="100%" 
							  	height="250" 
							  	frameborder="0" 
							  	scrolling="no" 
							  	marginheight="0" 
							  	marginwidth="0" 
							  	:src="'https://maps.google.com/maps?q=' + shop.latitude + ',' + shop.longitude + '&hl=es;z=14&amp;output=embed'"
							 	>
							 	</iframe>
						</v-card>
      				</v-flex>

      				<!-- Deals -->
      				<v-flex xs12>
      						<v-layout wrap>

								<v-flex xs12 class="mb-2">
									<div class="section-title">
										<h3>{{ $t('t_deals') }}</h3>
										<div class="more">
											<nuxt-link to="/browse/deals">{{ $t('t_see_more') }}</nuxt-link>
										</div>
									</div>
								</v-flex>

      							<v-flex xs12 v-for="deal in deals" :key="deal.unique_id">
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
										<pagination :data="dealsData" @pagination-change-page="getDeals" :limit=-1 :align="!$vuetify.rtl ? 'right' : 'left'">
											<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
											<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
										</pagination>
									</div>
								</v-flex>

      						</v-layout>
      				</v-flex>

      			</v-layout>
      		</v-container>
      	</v-content>

	</v-app>
</template>

<script>
	export default {
		layout: 'default/main',

		async asyncData ({ $axios, params }) {
			let shop  = await $axios.post(`shop/${params.shop}`)
			let deals = await $axios.get(`shop/${params.shop}/deals?page=1`)
			return {
				shop: shop.data.shop,
				isFollowing: shop.data.isFollowing,
				dealsData: deals.data,
				deals: deals.data.data,
				seo: {
	  				title: shop.data.seo.title,
	  				image: shop.data.seo.image,
	  				separator: shop.data.seo.separator,
	  				description: shop.data.seo.description,
	  				favicon: shop.data.seo.favicon,
	  			}

			}
		},

	  	data: function() {
	  		return {
	  			form: {
	  				message: null,
	  				subject: null
	  			},
	  			loading: {
	  				app: false,
	  				follow: false,
	  				message: false
	  			},
	  			dialogs: {
	  				message: false
				},
				selection: 1,  
	  			errors: []
	  		}
	  	},

	  	head () {
	  		return {
	  			title: this.shop.title,
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.shop.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home(`shop/${this.shop.store}`) },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.shop.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
	  		}
	  	},

	  	computed: {
	  		cover: function() {
	  			if (this.shop.cover === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-cover.png')
				}else{
					return this.$homeApi('storage/app/' + this.shop.cover)
				}
	  		},

	  		logo: function() {
				if (this.shop.logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + this.shop.logo)
				}
			}
	  	},

	  	methods: {
	  		follow: function() {
	  			if (!this.$auth.loggedIn) {
	  				this.$toasted.error("Oops! You need to login to follow this shop.", {
						icon: 'alert',
						action: null
					})
					return
	  			}
	  			this.loading.follow = true
	  			this.$axios
	  			.post('shop/options/follow', {
	  				shop: this.shop.store
	  			})
	  			.then(response => {
	  				this.isFollowing    = true
	  				this.loading.follow = false
	  			})
	  			.catch(error => {
	  				console.log(error)
	  				this.loading.follow = false
	  			})
	  		},

	  		unfollow: function() {
	  			if (!this.$auth.loggedIn) {return}
	  			this.loading.follow = true
	  			this.$axios
	  			.post('shop/options/unfollow', {
	  				shop: this.shop.store
	  			})
	  			.then(response => {
	  				this.isFollowing    = false
	  				this.loading.follow = false
	  			})
	  			.catch(error => {
	  				console.log(error)
	  				this.loading.follow = false
	  			})
	  		},

	  		message: function() {
	  			if (!this.$auth.loggedIn || (!this.form.message && !this.form.subject)) {
	  				return
	  			}
	  			this.loading.message = true
	  			this.$axios
	  			.post('shop/options/message', {
	  				shop: this.shop.store,
	  				subject: this.form.subject,
	  				message: this.form.message
	  			})
	  			.then(response => {
	  				this.errors   = []
					this.$toasted.show("Your message has been successfully sent.", {
						icon: 'done_all'
					})
	  				this.loading.message = false
	  				this.dialogs.message = false
	  				this.form.subject    = null
	  				this.form.message    = null
	  			})
	  			.catch(error => {
	  				if (error.response.data.errors) {
	  					this.errors = error.response.data.errors
	  				}
	  				this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						action: null
					})
	  				this.loading.message = false
	  			})
	  		},

	  		getDeals: function(page = 1) {
	  			this.loading.app = true
				this.$axios
				.get('shop/' + this.shop.store + '/deals?page=' + page)
				.then(response => {
					this.dealsData   = response.data
					this.deals       = response.data.data
					this.loading.app = false
				})
				.catch(error => {
					console.log(error)
					this.loading.app = false
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
			}
	  	}
	}
</script>