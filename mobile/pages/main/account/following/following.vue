<template>
	<v-app id="inspire">
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>
					  
					<v-flex xs12>
						<v-card class="m-card pb-5">
							<v-toolbar flat>
								<v-toolbar-title class="title">{{ $t('t_following_shops') }}</v-toolbar-title>
								<v-spacer></v-spacer>

								<!-- Unfollow -->
								<v-fade-transition>
							        <v-tooltip top v-if="selected.length > 0">
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon @click="unfollow()">
												<v-icon>mdi-account-remove</v-icon>
											</v-btn>
										</template>
							          	<span>{{ $t('t_unfollow') }}</span>
							        </v-tooltip>
							    </v-fade-transition>

							    <!-- Enable email notification -->
								<v-fade-transition>
							        <v-tooltip top v-if="selected.length > 0">
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon @click="enable()">
												<v-icon>mdi-email-check</v-icon>
											</v-btn>
										</template>
							          	<span>{{ $t('t_enable_email_notification') }}</span>
							        </v-tooltip>
							    </v-fade-transition>

							    <!-- Disable email notification -->
								<v-fade-transition>
							        <v-tooltip top v-if="selected.length > 0">
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon @click="disable()">
												<v-icon>mdi-email-lock</v-icon>
											</v-btn>
										</template>
							          	<span>{{ $t('t_disable_email_notification') }}</span>
							        </v-tooltip>
							    </v-fade-transition>
							</v-toolbar>

					        <v-data-table
								v-model="selected"
								:items="shops"
								:headers="headers"
								item-key="id"
				  				hide-default-footer
								show-select
								disable-pagination
								:mobile-breakpoint="1"
								:no-data-text="$t('t_table_no_data_available')"
								>
								<!-- Shop -->
								<template v-slot:item.shop="{ item }">
									<v-list two-line class="transparent">
										<v-list-item nuxt :to="'/shop/' + item.shop.store" target="_blank" :ripple="false">
											<v-list-item-avatar>
												<img :src="logo(item.shop.logo)">
											</v-list-item-avatar>
											<v-list-item-content class="table-wrap-title">
												<v-list-item-title class="table-wrap-title" v-html="item.shop.title"></v-list-item-title>
												<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="item.shop.store"></v-list-item-subtitle>
											</v-list-item-content>
										</v-list-item>
									</v-list>
								</template>

								<!-- E-mail notifications -->
								<template v-slot:item.email="{ item }">
									<v-btn depressed dark color="#00b16a" text v-if="item.isEmailNotifications">
										{{ $t('t_enabled') }}
									</v-btn>
									<v-btn depressed dark color="#cf000f" text v-else>
										{{ $t('t_disabled') }}
									</v-btn>
								</template>

								<!-- Followed at -->
								<template v-slot:item.created="{ item }">{{ $ago(item.followed_at) }}</template>

								<!-- Options -->
								<template v-slot:item.options="{ item }">
									<!-- Unfollow -->
									<v-icon small class="mr-2" @click="unfollow([item], shops.indexOf(item))">
										mdi-account-remove
									</v-icon>

									<!-- Enable email notifications -->
									<v-icon v-if="!item.isEmailNotifications" small @click="enable([item], shops.indexOf(item))">
										mdi-email-check
									</v-icon>

									<!-- Disable email notifications -->
									<v-icon v-else small @click="disable([item], shops.indexOf(item))">
										mdi-email-lock
									</v-icon>
								</template>
							</v-data-table>
						</v-card>
						<div class="text-center pt-2">
							<pagination :data="shopsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
	import sidebar from '~/pages/main/account/layout/account'

	export default {
		middleware: 'auth',

		layout: 'default/main',

		components: {
			'v-sidebar': sidebar
		},

		async asyncData ({ $axios }) {
			let response = await $axios.get('account/following')
			return {
				shopsData: response.data.shops,
				shops: response.data.shops.data,
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
				selected: [],
				loading: false
			}
		},

		head () {
			return {
				title: this.$t('t_following_shops'),
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		computed: {
			headers(){
				return [
					{ text: this.$t('t_shop'), value: 'shop', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
					{ text: this.$t('t_email_notifications'), value: 'email', sortable: false, align: 'center'},
					{ text: this.$t('t_followed_at'), value: 'created', sortable: false, align: 'center'},
					{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('account/following?page=' + page)
				.then(response => {
					this.selected  = []
					this.shopsData = response.data.shops
					this.shops     = response.data.data.shops
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			logo: function(logo) {
				if (logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			},

			unfollow: function(shops = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/following/unfollow', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.$delete(this.shops, index)
					}else{
						vm.selected.forEach(function (selectedShop, selectedIndex) {
							vm.shops.forEach(function(value, ind) {
								if (selectedShop.id === value.id) {
									vm.$delete(vm.shops, ind)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_shops_unfollowed'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading        = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			enable: function(shops = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/following/enable', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.shops[index].isEmailNotifications = true
					}else{
						vm.selected.forEach(function (selectedShop, selectedIndex) {
							vm.shops.forEach(function(value, ind) {
								if (selectedShop.id === value.id) {
									vm.shops[ind].isEmailNotifications = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_email_noti_enabled'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading        = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			disable: function(shops = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/following/disable', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.shops[index].isEmailNotifications = false
					}else{
						vm.selected.forEach(function (selectedShop, selectedIndex) {
							vm.shops.forEach(function(value, ind) {
								if (selectedShop.id === value.id) {
									vm.shops[ind].isEmailNotifications = false
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_email_noti_disabled'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading        = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			}
		}
	}
</script>