<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" flat>
					<v-toolbar-title>{{ $t('t_pending_shops') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Publish selected shops -->
					<v-fade-transition>
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="publish()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_activate') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="shops"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Logo -->
					<template v-slot:item.logo="{ item }">
						<v-avatar size="36px">
							<img :src="logo(item.logo)">
						</v-avatar>
					</template>

					<!-- Title -->
					<template v-slot:item.title="{ item }">
						<nuxt-link class="black--text font-weight-medium" target="_blank" :to="'/shop/' + item.store">{{ item.title }}</nuxt-link>
						<small class="mt-1 font-weight-regular text-uppercase d-block">{{ item.store }}</small>
					</template>

					<!-- Subtitle -->
					<template v-slot:item.subtitle="{ item }">
						{{ item.subtitle }}
					</template>

					<!-- Country -->
					<template v-slot:item.country="{ item }">
						<v-avatar size="36px">
							<img :src="$homeApi('public/images/flags/large/' + item.country.code.toUpperCase() + '.png')">
						</v-avatar>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small slot="activator" icon @click.prevent="publish([item], shops.indexOf(item))">
							<v-icon size="18px" color="grey darken-1">mdi-check</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="shopsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
		      	</pagination>
		    </div>
		</v-container>

	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'approve', 'shops')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/pending/shops')
			return {
				shopsData: response.data,
				shops: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_logo'), value: 'logo', sortable: false, align: 'center'},
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_shop_subtitle'), value: 'subtitle', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_country'), value: 'country', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_shops'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('administrator/pending/shops?page=' + page)
				.then(response => {
					this.selected  = []
					this.shopsData = response.data
					this.shops     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			publish: function(shops = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/shops/options/publish', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.$delete(this.shops, index)
					}else{
						vm.selected.forEach(function (selectedShops, selectedIndex) {
							vm.shops.forEach(function(value, index) {
								if (selectedShops.unique_id === value.unique_id) {
									vm.$delete(vm.shops, index)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_shops_activated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
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