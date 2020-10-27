<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_pending_deal') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Publish all deals -->
					<v-scroll-x-transition>
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="publish()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_activate') }}</span>
				        </v-tooltip>
				    </v-scroll-x-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="deals"
					item-key="id"
      				hide-default-footer
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Preview -->
					<template v-slot:item.preview="{ item }">
						<v-avatar size="36px">
							<img :src="preview(item)">
						</v-avatar>
					</template>

					<!-- Title -->
					<template v-slot:item.title="{ item }">
						<nuxt-link :to="'/listing/' + item.unique_slug" target="_blank" class="table-wrap-title">{{ item.title }}</nuxt-link>
						<small class="pb-1 font-weight-regular text-uppercase d-block">{{ item.user.username }}</small>
					</template>

					<!-- Price -->
					<template v-slot:item.price="{ item }">
						<div v-if="item.price === null && item.currency === null && item.locale === null"></div>
						<div v-else>{{ $getCurrency(item.price, item.currency, item.locale) }}</div>
					</template>

					<!-- Category -->
					<template v-slot:item.category="{ item }">{{ item.category.title }}</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small slot="activator" icon @click.prevent="publish([item], deals.indexOf(item))">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-check</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="dealsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'approve', 'deals')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/pending/deals')
			return {
				dealsData: response.data,
				deals: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_preview'), value: 'preview', sortable: false, align: 'center'},
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_category'), value: 'category', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_deal'),
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
				axios
				.get('administrator/pending/deals?page=' + page)
				.then(response => {
					this.selected  = []
					this.dealsData = response.data
					this.deals     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			publish: function(deals = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/deals/options/publish', {
					deals: deals
				})
				.then(response => {
					if (index !== null) {
						this.$delete(this.deals, index)
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, index) {
								if (selectedDeal.unique_id === value.unique_id) {
									vm.$delete(vm.deals, index)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_published'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			preview: function(deal) {
				if (deal.photosNumber == 0) {
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');
		        }else{
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg');
		        }
			}
		}
  	}
</script>