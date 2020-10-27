<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$mogate.allow($auth.user, 'delete', 'deals')">
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

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_deals') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Publish -->
					<v-fade-transition v-if="$mogate.allow($auth.user, 'approve', 'deals')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="publish()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_publish') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Delete -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'delete', 'deals')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Restore -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'delete', 'deals')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Archive -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'edit', 'deals')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="archive()">
									<v-icon>mdi-archive</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_archive') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create deal -->
			        <v-tooltip top>
						<template v-slot:activator="{ on }">
							<v-btn to="/create" target="_blank" v-on="on" text icon>
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
					:no-data-text="$t('t_table_no_data_available')"
      				hide-default-footer
					show-select
					disable-pagination
					>
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
						<div class="font-weight-bold" v-if="item.price === null && item.currency === null && item.locale === null"></div>
						<div class="font-weight-bold" v-else>{{ $getCurrency(item.price, item.currency, item.locale) }}</div>
					</template>

					<!-- Category -->
					<template v-slot:item.category="{ item }">{{ item.category.title }}</template>

					<!-- status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Featured -->
							<v-btn text color="#19b5fe" v-if="item.isUpgraded && !item.isPending && !item.isArchived">
								<span v-if="item.upgradedTo === 'urgent'" v-t="'t_urgent'"></span>
								<span v-if="item.upgradedTo === 'highlight'" v-t="'t_highlight'"></span>
								<span v-if="item.upgradedTo === 'featured'" v-t="'t_featured'"></span>
							</v-btn>
							<!-- Active -->
							<v-btn text color="#2ecc71" v-else-if="item.isActive">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="warning" v-else-if="item.isPending">
								{{ $t('t_pending') }}
							</v-btn>
							<!-- Archived -->
							<v-btn text color="#95a5a6" v-else-if="item.isArchived">
								{{ $t('t_archived') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Expires -->
					<template v-slot:item.expires="{ item }">{{ $dateString(item.expires_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Statistics -->
								<v-list-item :to="'/moderator/deals/statistics/' + item.unique_id" v-if="$mogate.allow($auth.user, 'stats', 'deals')">
									<v-list-item-action>
										<v-icon>mdi-chart-bar</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_statistics') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Publish -->
								<v-list-item v-if="$mogate.allow($auth.user, 'approve', 'deals') && (item.isPending || item.isArchived)" @click="publish([item], deals.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-check-all</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_publish') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Archive -->
								<v-list-item v-if="$mogate.allow($auth.user, 'edit', 'deals') && item.isActive" @click="archive([item], deals.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-archive</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_archive') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Edit -->
								<v-list-item :to="'/moderator/deals/edit/' + item.unique_id" v-if="$mogate.allow($auth.user, 'edit', 'deals')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item v-if="$mogate.allow($auth.user, 'delete', 'deals') && item.deleted_at === null" @click="remove([item], deals.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item v-if="$mogate.allow($auth.user, 'delete', 'deals') && item.deleted_at !== null" @click="restore([item], deals.indexOf(item))">
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
		layout: 'default/moderator',

		middleware: 'moderator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$mogate.allow(app.$auth.user, 'access', 'deals')) {
				redirect('/moderator')
			}

			let response = await $axios.get('moderator/deals')
			return {
				dealsData: response.data,
				deals: response.data.data
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
				title: this.$t('t_deals'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},

		computed: {
			headers() {
				return [
					{ text: this.$t('t_preview'), value: 'preview', sortable: false, align: 'center'},
					{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
					{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
					{ text: this.$t('t_category'), value: 'category', sortable: false, align: 'center'},
					{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
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
				.get('moderator/deals?page=' + page)
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
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'access', 'deals')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/deals/options/activate', {
					deals: deals
				})
				.then(response => {
					if (index !== null  && this.deals[index].user.id !== 1) {
						this.deals[index].isPending  = false
						this.deals[index].isActive   = true
						this.deals[index].isArchived = false
						this.deals[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id && value.user.id !== 1) {
									vm.deals[ind].isArchived = false
									vm.deals[ind].isActive   = true
									vm.deals[ind].isPending  = false
									vm.deals[ind].deleted_at = null
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

			archive: function(deals = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'edit', 'deals')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/deals/options/archive', {
					deals: deals
				})
				.then(response => {
					if (index !== null && this.deals[index].user.id !== 1) {
						this.deals[index].isPending  = false
						this.deals[index].isActive   = false
						this.deals[index].isArchived = true
						this.deals[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id && value.user.id !== 1) {
									vm.deals[ind].isArchived = true
									vm.deals[ind].isActive   = false
									vm.deals[ind].isPending  = false
									vm.deals[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_archived'), {
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

			remove: function(deals = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'delete', 'deals')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/deals/options/delete', {
					deals: deals
				})
				.then(response => {
					if (index !== null && this.deals[index].user.id !== 1) {
						this.deals[index].isPending  = false
						this.deals[index].isActive   = false
						this.deals[index].isArchived = false
						this.deals[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id && value.user.id !== 1) {
									vm.deals[ind].isArchived = false
									vm.deals[ind].isActive   = false
									vm.deals[ind].isPending  = false
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
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			restore: function(deals = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'delete', 'deals')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/deals/options/restore', {
					deals: deals
				})
				.then(response => {
					if (index !== null && this.deals[index].user.id !== 1) {
						this.deals[index].isPending  = false
						this.deals[index].isActive   = true
						this.deals[index].isArchived = false
						this.deals[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedDeal, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedDeal.unique_id === value.unique_id && value.user.id !== 1) {
									vm.deals[ind].isArchived = false
									vm.deals[ind].isActive   = true
									vm.deals[ind].isPending  = false
									vm.deals[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_restored'), {
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
            
		            // get default image
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');

		        }else{

		            // get first image
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg');

		        }
		   	},
		}
  	}
</script>