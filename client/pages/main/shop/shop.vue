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
					<v-btn color="#2e3131" text @click="message" :disabled="loading.message || !form.message || !form.subject" :loading="loading.message">{{ $t('t_send') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-content>
      		<v-container fluid grid-list-xl>
      			<v-layout wrap>

      				<!-- Shop details && map -->
      				<v-flex xs12>
      					<v-card class="m-card mb-4" text>
							<v-img :src="cover" height="220px" gradient="to top right, rgba(10, 10, 10, 0.6), rgba(31, 31, 31, 0.73)">
								<v-layout column fill-height>
									<v-card-title>
										<v-btn dark icon class="mr-3" @click="dialogs.message = true" v-if="$megate.allow($auth.user, 'contact', 'shops')">
											<v-icon>mdi-message</v-icon>
										</v-btn>
										<v-spacer></v-spacer>

										<!-- Follow -->
										<v-tooltip top v-if="$megate.allow($auth.user, 'follow', 'shops') && !isFollowing">
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" dark icon @click="follow" :disabled="loading.follow" :loading="loading.follow">
													<v-icon>mdi-account-plus</v-icon>
												</v-btn>
											</template>
											<span>{{ $t('t_follow_shop', {shop: shop.title}) }}</span>
										</v-tooltip>

										<!-- Unfollow -->
										<v-tooltip top v-else>
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" dark icon @click="unfollow" :disabled="loading.follow" :loading="loading.follow">
													<v-icon>mdi-account-check</v-icon>
												</v-btn>
											</template>
											<span>{{ $t('t_you_are_following_shop', {shop: shop.title}) }}</span>
										</v-tooltip>

									</v-card-title>
									<v-spacer></v-spacer>
									<v-card-title class="white--text justify-center">
										<v-avatar class="text-center" size="70px">
										      <img :src="logo">
										</v-avatar>
									</v-card-title>
									<v-card-title class="white--text justify-center pt-0">
										<div class="headline">{{ shop.title }}</div>
									</v-card-title>
								</v-layout>
							</v-img>
							<div class="pa-3">
								<no-ssr>
									<truncate v-if="shop.description" action-class="text-center caption text-uppercase" :clamp="$t('t_show_more')" :length="1200" :less="$t('t_show_less')" type="html" :text="shop.description"></truncate>
								</no-ssr>
							</div>
							<v-list two-line>
								<v-list-item v-if="shop.phone">
									<v-list-item-action>
										<v-icon color="grey darken-4">mdi-phone</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ shop.phone }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>
								<v-divider inset></v-divider>
								<v-list-item v-if="shop.customer_email">
									<v-list-item-action>
										<v-icon color="grey darken-4">mail</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ shop.customer_email }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>
								<v-divider inset></v-divider>
								<v-list-item v-if="shop.address1 || shop.country">
									<v-list-item-action>
										<v-icon color="grey darken-4">location_on</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title v-if="shop.address1">{{ shop.address1 }}</v-list-item-title>
										<v-list-item-title v-if="shop.address2">{{ shop.address2 }}</v-list-item-title>
										<v-list-item-subtitle>{{ shop.city ? shop.city.name + ' - ' : '' }} {{ shop.state ? shop.state.name + ' - ' : '' }} {{ shop.country ? shop.country.name : '' }}</v-list-item-subtitle>
									</v-list-item-content>
								</v-list-item>
							</v-list>
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

      							<v-flex xs12 sm6 md6 lg3 class="mb-12" v-for="deal in deals" :key="deal.unique_id">
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
								</v-flex>

      							<!-- Pagination -->
								<v-flex xs12>
									<div class="text-center pt-2">
										<pagination :data="dealsData" @pagination-change-page="getDeals" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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