<template>
	<v-app id="inspire">
		
		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
	  		<v-container grid-list-xl fluid >
	  			<v-layout wrap>

					<v-flex xs12 class="my-3">
						<div class="text-center">
							<h1 class="display-1 text-uppercase font-weight-black mb-5">{{ category.title }}</h1>
						</div>
					</v-flex>

					<!-- Categories -->
					<v-flex xs12 class="mb-2">
						<div class="section-title">
		                    <h3>{{ $t('t_sub_categories') }}</h3>
		                    <div class="more" v-if="isChilds">
		                    	<nuxt-link :to="'/category/' + previous.slug">{{ $t('t_browse_category', {category: previous.title}) }}</nuxt-link>
		                    </div>
		                </div>
					</v-flex>

					<v-flex xs4 sm3 v-for="category in childs" :key="category.id" class="mb-3">
						<v-card class="m-card px-2 py-3 text-center category-box" @click="categoriesChilds(category)">
							<v-avatar size="45px" class="mb-2">
								<img v-if="category.icon" :src="$homeApi('storage/app/' + category.icon)">
								<v-icon class="grey lighten-1" v-else>mdi-buffer</v-icon>
							</v-avatar>
							<h5 class="pt-2" v-text="category.title"></h5>
						</v-card>
					</v-flex>

				</v-layout>

				<v-layout wrap>

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

				    <!-- No deals -->
				    <v-flex xs12 v-if="deals.length === 0">
				    	<v-alert :color="$vuetify.theme.dark ? '#ffeb3b' : 'info'" text icon="mdi-alert-circle-check-outline">
					      	{{ $t('t_no_deals_right_now') }}
					    </v-alert>
				    </v-flex>

				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	export default {
		middleware: undefined,

		layout: 'default/main',

		async asyncData ({ $axios, params }) {
			let response = await $axios.get(`category/${params.slug}`)
			return {
				dealsData: response.data.deals,
				deals: response.data.deals.data,
				childs: response.data.childs,
				category: response.data.category,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
	  				image: response.data.seo.image
				}
			}
		},

	  	data: function() {
	  		return {
				previous: null,
		        isChilds: false,
				loading: false
	  		}
	  	},

		head () {
			return {
				title: this.category.title,
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.category.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home(`category/${this.category.slug}`) },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.category.title} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
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
					this.childs     = response.data
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
					this.childs     = response.data
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
	  	}
	}
</script>