<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'categories')">
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
						{{ selected.length > 1 ? $t('t_delete_categories') : $t('t_delete_category') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_categories') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'categories')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Restore -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'categories')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create category -->
			        <v-tooltip top v-if="$adgate.allow($auth.user, 'create', 'categories')">
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/categories/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="categories"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
						<!-- Icon -->
						<template v-slot:item.icon="{ item }">
							<v-avatar size="36px">
								<img :src="icon(item.icon)">
							</v-avatar>
						</template>

						<!-- Title -->
						<template v-slot:item.title="{ item }">
							<nuxt-link :to="'/category/' + item.slug" target="_blank" class="table-wrap-title">{{ item.title }}</nuxt-link>
							<small class="pb-1 font-weight-regular text-uppercase d-block">{{ item.slug }}</small>
						</template>

						<!-- Childs -->
						<template v-slot:item.subcategories="{ item }">{{ item.childs.length }}</template>

						<!-- Deals -->
						<template v-slot:item.deals="{ item }">{{ item.deals.length }}</template>

						<!-- Created at -->
						<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

						<!-- Options -->
						<template v-slot:item.options="{ item }">
							<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
								<template v-slot:activator="{ on }">
									<v-btn small v-on="on" icon>
										<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-dots-vertical</v-icon>
									</v-btn>
								</template>
								<v-list dense>

									<!-- Edit -->
									<v-list-item :to="'/administrator/categories/edit/' + item.slug" v-if="$adgate.allow($auth.user, 'edit', 'categories')">
										<v-list-item-action>
											<v-icon>mdi-square-edit-outline</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

									<!-- Delete -->
									<v-list-item v-if="item.deleted_at === null && $adgate.allow($auth.user, 'delete', 'categories')" @click="remove([item], categories.indexOf(item))">
										<v-list-item-action>
											<v-icon>mdi-delete</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

									<!-- Restore -->
									<v-list-item v-if="item.deleted_at !== null && $adgate.allow($auth.user, 'delete', 'categories')" @click="restore([item], categories.indexOf(item))">
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
		      	<pagination :data="categoriesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'access', 'categories')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/categories')
			return {
				categoriesData: response.data,
				categories: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_icon'), value: 'icon', sortable: false, align: 'center'},
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_sub_categories'), value: 'subcategories', sortable: false, align: 'center'},
		          	{ text: this.$t('t_deals'), value: 'deals', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_categories'),
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
				.get('administrator/categories?page=' + page)
				.then(response => {
					this.selected       = []
					this.categoriesData = response.data
					this.categories     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading        = false
				})
			},

		   	icon: function(icon) {
				if (icon === null) {
					return this.$homeApi('storage/app/uploads/default/category/no-icon.png')
				}else{
					return this.$homeApi('storage/app/' + icon);
				}
			},

		   	remove: function(deals = this.selected) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'categories')) {
					this.$router.push('/administrator')	
					return
				}

				this.$axios
				.post('administrator/deals/options/delete', {
					deals: deals
				})
				.then(response => {
					let vm = this
					vm.selected.forEach(function (selectedDeals, selectedIndex) {
						vm.deals.forEach(function(value, index) {
							if (selectedDeals.unique_id === value.unique_id) {
								/*value.isBlocked  = false
								value.isActive   = false
								value.isPending  = false*/
								value.deleted_at = true
							}
						})
					})
					this.selected    = []
					this.dialogs.delete = false
					this.$toasted.show(`Selected ${deals.length > 1 ? 'deals' : 'deal'} has been successfully deleted.`, {
						icon: 'done_all'
					})
				})
				.catch(error => {
					console.log(error)
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						action: null
					})
				})
			},
		}
  	}
</script>