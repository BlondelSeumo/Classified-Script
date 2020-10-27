<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$mogate.allow($auth.user, 'delete', 'shops')">
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
						{{ selected.length > 1 ? $t('t_delete_shops') : $t('t_delete_shop') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Details dialog -->
		<v-dialog v-model="dialogs.details" max-width="600" persistent v-if="Object.keys(store.details.shop).length !== 0">
			<v-card
				class="mx-auto no-border-radius"
				>
				<v-card
					dark
					flat
					>
					<v-card-title class="pa-2">
						<v-avatar>
							<v-img :src="logo(store.details.shop.logo)"></v-img>
						</v-avatar>
						<v-spacer></v-spacer>
						<v-btn icon text :to="'/shop/' + store.details.shop.store" target="_blank" :color="$vuetify.theme.dark ? 'white' : '#4C4A48'">
							<v-icon>mdi-open-in-new</v-icon>
						</v-btn>
						<v-btn icon v-if="$mogate.allow($auth.user, 'edit', 'shops')" text :to="'/moderator/shops/edit/' + store.details.shop.store" :color="$vuetify.theme.dark ? 'white' : '#4C4A48'">
							<v-icon>mdi-square-edit-outline</v-icon>
						</v-btn>
						<v-btn icon text @click="remove([store.details.shop], store.details.index)" :color="$vuetify.theme.dark ? 'white' : '#4C4A48'" v-if="$mogate.allow($auth.user, 'delete', 'shops') && store.details.shop.deleted_at === null">
							<v-icon>mdi-delete</v-icon>
						</v-btn>
						<v-btn icon text @click="restore([store.details.shop], store.details.index)" :color="$vuetify.theme.dark ? 'white' : '#4C4A48'" v-if="$mogate.allow($auth.user, 'delete', 'shops') && store.details.shop.deleted_at !== null">
							<v-icon>mdi-delete-restore</v-icon>
						</v-btn>
						<v-btn icon text @click="dialogs.details = false" :color="$vuetify.theme.dark ? 'white' : '#4C4A48'">
							<v-icon>mdi-close</v-icon>
						</v-btn>
					</v-card-title>
					<v-img
						:src="cover(store.details.shop.cover)"
						gradient="to top, rgba(0,0,0,.44), rgba(0,0,0,.44)"
						max-height="250px"
						>
						<v-container fill-height>
							<v-layout align-center>
								<v-layout column justify-end>
									<div class="headline font-weight-light">{{ store.details.shop.title }}</div>
									<div class="text-uppercase font-weight-light">{{ store.details.shop.subtitle }}</div>
								</v-layout>
							</v-layout>
						</v-container>
					</v-img>
				</v-card>
				<v-card-text class="py-5">
					<p class="font-weight-bold" v-html="store.details.shop.description"></p>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_shops') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Activate -->
					<v-fade-transition v-if="$mogate.allow($auth.user, 'approve', 'shops')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="activate()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							 </template>
				          	<span>{{ $t('t_activate') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Delete -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'delete', 'shops')">
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
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'delete', 'shops')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="shops"
					item-key="id"
					:no-data-text="$t('t_table_no_data_available')"
      				hide-default-footer
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					>
					<!-- Shop -->
					<template v-slot:item.shop="{ item }">
						<v-list two-line class="transparent">
							<v-list-item :to="'/shop/' + item.store" target="_blank" nuxt :ripple="false">
								<v-list-item-avatar>
									<img :src="logo(item.logo)">
								</v-list-item-avatar>
								<v-list-item-content class="table-wrap-title">
									<v-list-item-title class="table-wrap-title" v-html="item.title"></v-list-item-title>
									<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="item.store"></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Country -->
					<template v-slot:item.country="{ item }">
						<v-avatar size="36px" v-if="item.country">
							<img :src="$homeApi('public/images/flags/large/' + item.country.code.toUpperCase() + '.png')">
						</v-avatar>
						<v-avatar size="36px" v-else>
							<img :src="$homeApi('public/images/flags/large/unknown.png')">
						</v-avatar>
					</template>

					<!-- Created -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Active -->
							<v-btn text color="#26c281" v-if="item.isActive && !item.isExpired">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="warning" v-else-if="item.isPending">
								{{ $t('t_pending') }}
							</v-btn>
							<!-- Expired -->
							<v-btn text color="#7f8c8d" v-else-if="item.isExpired">
								{{ $t('t_expired') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Details -->
								<v-list-item @click="details(item, shops.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-eye</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_details') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Activate -->
								<v-list-item v-if="$mogate.allow($auth.user, 'approve', 'shops') && (item.isPending || item.isExpired)" @click="activate([item], shops.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-check-all</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_activate') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Edit -->
								<v-list-item :to="'/moderator/shops/edit/' + item.store" v-if="$mogate.allow($auth.user, 'edit', 'shops')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item v-if="$mogate.allow($auth.user, 'delete', 'shops') && item.deleted_at === null" @click="remove([item], shops.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item v-if="$mogate.allow($auth.user, 'delete', 'shops') && item.deleted_at !== null" @click="restore([item], shops.indexOf(item))">
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
		layout: 'default/moderator',

		middleware: 'moderator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$mogate.allow(app.$auth.user, 'access', 'shops')) {
				redirect('/moderator')
			}

			let response = await $axios.get('moderator/shops')
			return {
				shopsData: response.data,
				shops: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
		        dialogs: {
		        	delete: false,
		        	details: false
		        },
		        store: {
		        	details: {
		        		shop: {},
		        		index: null
		        	}
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_shops'),
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
		          	{ text: this.$t('t_shop'), value: 'shop', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_country'), value: 'country', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('moderator/shops?page=' + page)
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

			details: function(shop, index) {
				this.store.details.shop  = shop
				this.store.details.index = index
				this.dialogs.details     = true
			},

			logo: function(logo) {
				if (logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			},

			cover: function(cover) {
				if (cover === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-cover.png')
				}else{
					return this.$homeApi('storage/app/' + cover)
				}
			},

			activate: function(shops = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'approve', 'shops')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/shops/options/activate', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.shops[index].isPending  = false
						this.shops[index].isActive   = true
						this.shops[index].isExpired  = false
						this.shops[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedShop, selectedIndex) {
							vm.shops.forEach(function(value, ind) {
								if (selectedShop.unique_id === value.unique_id) {
									vm.shops[ind].isActive   = true
									vm.shops[ind].isExpired  = false
									vm.shops[ind].isPending  = false
									vm.shops[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_shops_activated'), {
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

			remove: function(shops = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'delete', 'shops')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/shops/options/delete', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.shops[index].isPending  = false
						this.shops[index].isActive   = false
						this.shops[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedShop, selectedIndex) {
							vm.shops.forEach(function(value, ind) {
								if (selectedShop.unique_id === value.unique_id && value.user_id !== 1) {
									vm.shops[ind].isActive   = false
									vm.shops[ind].isPending  = false
									vm.shops[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_shops_moved_to_trash'), {
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

			restore: function(shops = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'delete', 'shops')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/shops/options/restore', {
					shops: shops
				})
				.then(response => {
					if (index !== null) {
						this.shops[index].isPending  = false
						this.shops[index].isActive   = true
						this.shops[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedShop, selectedIndex) {
							vm.shops.forEach(function(value, ind) {
								if (selectedShop.unique_id === value.unique_id) {
									vm.shops[ind].isActive   = true
									vm.shops[ind].isPending  = false
									vm.shops[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_shops_restored'), {
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