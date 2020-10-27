<template>
	<v-app id="inspire">
		
		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>
          			<!-- Account page -->
					<v-flex xs12>
						<v-card class="m-card" text>
							<v-toolbar flat>
								<v-toolbar-title class="title">{{ $t('t_saved_search') }}</v-toolbar-title>
								<v-spacer></v-spacer>

							    <!-- Delete -->
						        <v-fade-transition>
							        <v-tooltip top v-if="selected.length > 0">
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="remove()">
												<v-icon>mdi-delete</v-icon>
											</v-btn>
										</template>
							          	<span>{{ $t('t_delete') }}</span>
							        </v-tooltip>
							    </v-fade-transition>

							</v-toolbar>

					        <v-data-table
								v-model="selected"
								:headers="headers"
								:items="search"
								item-key="id"
				  				hide-default-footer
								show-select
								disable-pagination
								:mobile-breakpoint="1"
								:no-data-text="$t('t_table_no_data_available')"
								>
								<!-- Search -->
								<template v-slot:item.search="{ item }">
									<div class="font-weight-bold">{{ item.search }}</div>
								</template>

								<!-- Saved -->
								<template v-slot:item.saved="{ item }">{{ $ago(item.created_at) }}</template>

								<!-- Options -->
								<template v-slot:item.options="{ item }">
									<!-- Delete -->
									<v-icon small :class="$vuetify.rtl ? 'ml-2' : 'mr-2'" @click="remove([item], search.indexOf(item))">
										mdi-delete
									</v-icon>

									<!-- Launch -->
									<v-icon small @click="launch(item)">
										mdi-launch
									</v-icon>
								</template>
							</v-data-table>
						</v-card>
						<div class="text-center pt-2">
							<pagination :data="searchData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			let response = await $axios.get('account/search')
			return {
				search: response.data.saved.data,
				searchData: response.data.saved,
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
		        dialogs: {
		        	delete: false
		        },
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_saved_search'),
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
					{ text: this.$t('t_search'), value: 'search', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_saved_at'), value: 'saved', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('account/search?page=' + page)
				.then(response => {
					this.selected   = []
					this.searchData = response.data.saved
					this.search     = response.data.saved.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
			},

			remove: function(search = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/search/options/delete', {
					search: search
				})
				.then(response => {
					if (index !== null) {
						this.search.splice(index)
					}else{
						vm.selected.forEach(function (selectedSearch, selectedIndex) {
							vm.search.forEach(function(value, ind) {
								if (selectedSearch.id === value.id) {
									vm.search.splice(ind)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_search_deleted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.dialogs.delete = false
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

			launch: function(search) {
				this.$root.$emit('launchSearch', search.search)
			}
		}
	}
</script>