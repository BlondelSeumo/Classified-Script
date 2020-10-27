<template>
	<v-app id="inspire">
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent>
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="error">mdi-alert-octagon-outline</v-icon>
					</div>
					<div class="mb-4 text-center headline font-weight-black text-uppercase">
						{{ $t('t_are_you_sure') }}
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="dialogs.delete = false">
						{{ $t('t_cancel') }}
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_deals') : $t('t_delete_deal') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>

          			<!-- Account page -->
					<v-flex xs12>
						<v-card class="m-card" flat>
							<v-toolbar flat>
								<v-toolbar-title class="title">{{ $t('t_my_deals') }}</v-toolbar-title>
								<v-spacer></v-spacer>

								    <!-- Delete -->
							        <v-fade-transition>
								        <v-tooltip top v-if="selected.length > 0">
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="dialogs.delete = true">
													<v-icon>mdi-delete</v-icon>
												</v-btn>
											</template>
								          	<span>{{ $t('t_delete') }}</span>
								        </v-tooltip>
								    </v-fade-transition>

								    <!-- Restore -->
							        <v-fade-transition>
								        <v-tooltip top v-if="selected.length > 0">
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="restore()">
													<v-icon>mdi-delete-restore</v-icon>
												</v-btn>
											</template>
								          	<span>{{ $t('t_restore') }}</span>
								        </v-tooltip>
								    </v-fade-transition>

								    <!-- Archive -->
							        <v-fade-transition>
								        <v-tooltip top v-if="selected.length > 0">
											<template v-slot:activator="{ on }">
												<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="archive()">
													<v-icon>mdi-archive</v-icon>
												</v-btn>
											</template>
								          	<span>{{ $t('t_archive') }}</span>
								        </v-tooltip>
								    </v-fade-transition>

									<!-- Create deal -->
							        <v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn to="/create" v-on="on" text icon>
												<v-icon>add</v-icon>
											</v-btn>
										</template>
							          	<span>{{ $t('t_create') }}</span>
							        </v-tooltip>

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
								<!-- Deal -->
								<template v-slot:item.deal="{ item }">
									<v-list two-line class="transparent">
										<v-list-item nuxt :to="`/listing/${item.unique_slug}`" :ripple="false">
											<v-list-item-avatar>
												<img :src="preview(item)">
											</v-list-item-avatar>
											<v-list-item-content class="table-wrap-title">
												<v-list-item-title class="table-wrap-title" v-html="item.title"></v-list-item-title>
												<div v-if="item.deleted_at === null">
													<!-- Featured -->
													<v-list-item-subtitle class="pt-2 text-uppercase text-small font-weight-regular d-block blue--text text--darken-1" v-if="item.isUpgraded && !item.isPending && !item.isArchived">
														<span v-if="item.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span>
														<span v-if="item.upgradedTo === 'highlight'">{{ $t('t_highlight') }}</span>
														<span v-if="item.upgradedTo === 'featured'">{{ $t('t_featured') }}</span>
													</v-list-item-subtitle>
													<!-- Active -->
													<v-list-item-subtitle class="pt-2 text-uppercase text-small font-weight-regular d-block green--text text--accent-4" v-else-if="item.isActive">
														{{ $t('t_active') }}
													</v-list-item-subtitle>
													<!-- Pending -->
													<v-list-item-subtitle class="pt-2 text-uppercase text-small font-weight-regular d-block amber--text text--accent-4" v-else-if="item.isPending">
														{{ $t('t_pending') }}
													</v-list-item-subtitle>
													<!-- Archived -->
													<v-list-item-subtitle class="pt-2 text-uppercase text-small font-weight-regular d-block" v-else-if="item.isArchived">
														{{ $t('t_archived') }}
													</v-list-item-subtitle>
												</div>
												<!-- Deleted -->
												<v-list-item-subtitle class="pt-2 text-uppercase text-small font-weight-regular d-block red--text text--darken-1" v-if="item.deleted_at !== null">
													{{ $t('t_deleted') }}
												</v-list-item-subtitle>
											</v-list-item-content>
										</v-list-item>
									</v-list>
								</template>

								<!-- Price -->
								<template v-slot:item.price="{ item }">
									<div class="text-center font-weight-bold" v-if="item.price && item.currency && item.locale">
										{{ $getCurrency(item.price, item.currency, item.locale) }}
									</div>
								</template>

								<!-- Created at -->
								<template v-slot:item.created="{ item }">
									{{ $ago(item.created_at) }}
								</template>

								<!-- Expires -->
								<template v-slot:item.expires="{ item }">
									{{ $dateString(item.expires_at) }}
								</template>

								<!-- Options -->
								<template v-slot:item.options="{ item }">
										<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
											<template v-slot:activator="{ on }">
												<v-btn small v-on="on" icon>
													<v-icon size="20px" color="grey darken-1">mdi-dots-vertical</v-icon>
												</v-btn>
											</template>
											<v-list dense>

												<!-- Promote -->
												<v-list-item :to="'/account/deals/promote/' + item.unique_id" v-if="!item.isPending && !item.deleted_at">
													<v-list-item-action>
														<v-icon>mdi-adchoices</v-icon>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>{{ $t('t_promote') }}</v-list-item-title>
													</v-list-item-content>
												</v-list-item>

												<!-- Statistics -->
												<v-list-item :to="'/account/deals/statistics/' + item.unique_id" v-if="$megate.allow($auth.user, 'statistics', 'see')">
													<v-list-item-action>
														<v-icon>mdi-chart-bar</v-icon>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>{{ $t('t_statistics') }}</v-list-item-title>
													</v-list-item-content>
												</v-list-item>

												<!-- Archive -->
												<v-list-item v-if="item.isActive" @click="archive([item], deals.indexOf(item))">
													<v-list-item-action>
														<v-icon>mdi-archive</v-icon>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>{{ $t('t_archive') }}</v-list-item-title>
													</v-list-item-content>
												</v-list-item>

												<!-- Edit -->
												<v-list-item :to="'/account/deals/edit/' + item.unique_id">
													<v-list-item-action>
														<v-icon>mdi-square-edit-outline</v-icon>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
													</v-list-item-content>
												</v-list-item>

												<!-- Delete -->
												<v-list-item v-if="item.deleted_at === null" @click="remove([item], deals.indexOf(item))">
													<v-list-item-action>
														<v-icon>mdi-delete</v-icon>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
													</v-list-item-content>
												</v-list-item>

												<!-- Restore -->
												<v-list-item v-if="item.deleted_at !== null" @click="restore([item], deals.indexOf(item))">
													<v-list-item-action>
														<v-icon>mdi-delete-restore</v-icon>
													</v-list-item-action>
													<v-list-item-content>
														<v-list-item-title>{{ $t('t_restore') }}</v-list-item-title>
													</v-list-item-content>
												</v-list-item>

											</v-list>
										</v-menu>
								</template>
							</v-data-table>
						</v-card>
						<div class="text-center pt-2">
							<pagination :data="dealsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.get('account/deals')
			return {
				dealsData: response.data.deals,
				deals: response.data.deals.data,
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
				title: this.$t('t_my_deals'),
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
					{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_expires_in'), value: 'expires', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('account/deals?page=' + page)
				.then(response => {
					this.selected  = []
					this.dealsData = response.data.deals
					this.deals     = response.data.deals.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			preview: function(deal) {
		   		if (deal.photosNumber == 0) {
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png')
		        }else{
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg')
		        }
		   	},

		   	remove: function(deals = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/deals/options/delete', {
					deals: deals
				})
				.then(response => {
					if (index !== null) {
						this.deals[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id) {
									vm.deals[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_deleted'), {
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

			restore: function(deals = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/deals/options/restore', {
					deals: deals
				})
				.then(response => {
					if (index !== null) {
						this.deals[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id) {
									vm.deals[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_restored'), {
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

			archive: function(deals = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('account/deals/options/archive', {
					deals: deals
				})
				.then(response => {
					if (index !== null) {
						vm.deals[index].deleted_at = null
						vm.deals[index].isActive   = false
						vm.deals[index].isArchived = true
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id && !selectedDeal.isPending) {
									vm.deals[ind].deleted_at = null
									vm.deals[ind].isActive   = false
									vm.deals[ind].isArchived = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_archived'), {
						icon: 'done_all'
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
		}
	}
</script>