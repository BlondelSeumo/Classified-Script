<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'pages')">
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
						{{ selected.length > 1 ? $t('t_delete_pages') : $t('t_delete_page') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_pages') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'pages')">
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
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'pages')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create page -->
			        <v-tooltip top v-if="$adgate.allow($auth.user, 'create', 'pages')">
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/pages/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="pages"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
						<!-- Title -->
						<template v-slot:item.title="{ item }">{{ item.title }}</template>

						<!-- Slug -->
						<template v-slot:item.slug="{ item }">
							<nuxt-link :to="'/page/' + item.slug" target="_blank">{{ item.slug }}</nuxt-link>
						</template>

						<!-- Type -->
						<template v-slot:item.type="{ item }">
							<v-tooltip top v-if="item.isLink">
								<template v-slot:activator="{ on }">
									<v-btn text icon color="blue" v-on="on">
										<v-icon>mdi-link</v-icon>
									</v-btn>
								</template>
					            <span>{{ $t('t_link') }}</span>
					        </v-tooltip>
					        <v-tooltip top v-else>
								<template v-slot:activator="{ on }">
									<v-btn text icon color="grey" v-on="on">
										<v-icon>mdi-file</v-icon>
									</v-btn>
								</template>
					            <span>{{ $t('t_page') }}</span>
					        </v-tooltip>
						</template>

						<!-- status -->
						<template v-slot:item.status="{ item }">
							<!-- Active -->
							<v-btn text color="#2ecc71" v-if="item.deleted_at === null">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Deleted -->
							<v-btn text color="error" v-if="item.deleted_at !== null">
								{{ $t('t_deleted') }}
							</v-btn>
						</template>

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
									<v-list-item :to="'/administrator/pages/edit/' + item.slug" v-if="$adgate.allow($auth.user, 'edit', 'pages')">
										<v-list-item-action>
											<v-icon>mdi-square-edit-outline</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

									<!-- Delete -->
									<v-list-item @click="remove([item], pages.indexOf(item))" v-if="item.deleted_at === null && $adgate.allow($auth.user, 'delete', 'pages')">
										<v-list-item-action>
											<v-icon>mdi-delete</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

									<!-- Restore -->
									<v-list-item @click="restore([item], pages.indexOf(item))" v-if="item.deleted_at !== null && $adgate.allow($auth.user, 'delete', 'pages')">
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
		      	<pagination :data="pagesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'access', 'pages')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/pages')
			return {
				pagesData: response.data,
				pages: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: 'center'},
		          	{ text: this.$t('t_slug'), value: 'slug', sortable: false, align: 'center'},
		          	{ text: this.$t('t_type'), value: 'type', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
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
				title: this.$t('t_pages'),
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
				.get('administrator/pages?page=' + page)
				.then(response => {
					this.selected  = []
					this.pagesData = response.data
					this.pages     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

		   	remove: function(pages = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'pages')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/pages/options/delete', {
					pages: pages
				})
				.then(response => {
					if (index !== null) {
						this.pages[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedPage, selectedIndex) {
							vm.pages.forEach(function(value, ind) {
								if (selectedPage.id === value.id) {
									vm.pages[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_pages_deleted'), {
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

			restore: function(pages = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'pages')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/pages/options/restore', {
					pages: pages
				})
				.then(response => {
					if (index !== null) {
						this.pages[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedPage, selectedIndex) {
							vm.pages.forEach(function(value, ind) {
								if (selectedPage.id === value.id) {
									vm.pages[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_pages_restored'), {
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
			}
		}
  	}
</script>