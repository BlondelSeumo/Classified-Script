<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$owgate.allow($auth.user, 'delete', 'currencies')">
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
						{{ selected.length > 1 ? $t('t_delete_currencies') : $t('t_delete_currency') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Create dialog -->
		<v-dialog v-model="dialogs.create" max-width="500px" persistent v-if="$owgate.allow($auth.user, 'create', 'currencies')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_create_currency') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.create = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>

							<!-- Name -->
							<v-flex xs12>
								<v-text-field
									v-model="forms.create.name"
									color="grey lighten-1"
									:label="$t('t_currency_name')"
									:placeholder="$t('t_enter_currency_name')"
									counter="60"
									maxlength="60"
									:rules="errors.name"
									:error="errors.name ? true : false"
									></v-text-field>
							</v-flex>

							<!-- Code -->
							<v-flex xs12>
								<v-autocomplete
									autocomplete="off"
									:items="config"
									v-model="forms.create.code"
									color="grey lighten-1"
									:label="$t('t_currency_code')"
									:placeholder="$t('t_enter_currency_code')"
									:rules="errors.code"
									:error="errors.code ? true : false"
									dense
									></v-autocomplete>
							</v-flex>

							<!-- Locale -->
							<v-flex xs12>
								<v-autocomplete
									autocomplete="off"
									:items="locales"
									v-model="forms.create.locale"
									color="grey lighten-1"
									:label="$t('t_currency_locale')"
									:placeholder="$t('t_choose_currency_locale')"
									:rules="errors.locale"
									:error="errors.locale ? true : false"
									dense
									></v-autocomplete>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn color="#2e3131" text @click="insert()">{{ $t('t_create') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Edit dialog -->
		<v-dialog v-model="dialogs.edit" max-width="500px" persistent v-if="$owgate.allow($auth.user, 'edit', 'currencies')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_edit_currency') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.edit = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>

							<!-- Name -->
							<v-flex xs12>
								<v-text-field
									v-model="forms.edit.name"
									color="grey lighten-1"
									:label="$t('t_currency_name')"
									:placeholder="$t('t_enter_currency_name')"
									counter="60"
									maxlength="60"
									:rules="errors.name"
									:error="errors.name ? true : false"
									></v-text-field>
							</v-flex>

							<!-- Code -->
							<v-flex xs12>
								<v-autocomplete
									autocomplete="off"
									:items="config"
									v-model="forms.edit.code"
									color="grey lighten-1"
									:label="$t('t_currency_code')"
									:placeholder="$t('t_enter_currency_code')"
									:rules="errors.code"
									:error="errors.code ? true : false"
									dense
									></v-autocomplete>
							</v-flex>

							<!-- Locale -->
							<v-flex xs12>
								<v-autocomplete
									autocomplete="off"
									:items="locales"
									v-model="forms.edit.locale"
									color="grey lighten-1"
									:label="$t('t_currency_locale')"
									:placeholder="$t('t_choose_currency_locale')"
									:rules="errors.locale"
									:error="errors.locale ? true : false"
									dense
									></v-autocomplete>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn color="#2e3131" text @click="update()">{{ $t('t_update') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_currencies') }}</v-toolbar-title>
					<v-spacer></v-spacer>

				    <!-- Delete -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'delete', 'currencies')">
				        <v-tooltip top v-if="selected.length > 0  && currencies.length > 1">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Restore -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'delete', 'currencies')">
				        <v-tooltip top v-if="selected.length > 0  && currencies.length > 1">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon color="grey darken-3" @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create -->
			        <v-tooltip top v-if="$owgate.allow($auth.user, 'create', 'currencies')">
						<template v-slot:activator="{ on }">
							<v-btn @click="create()" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="currencies"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Name -->
					<template v-slot:item.name="{ item }">{{ item.name }}</template>

					<!-- Code -->
					<template v-slot:item.created="{ item }">{{ item.code }}</template>

					<!-- Locale -->
					<template v-slot:item.locale="{ item }">{{ item.locale }}</template>

					<!-- status -->
					<template v-slot:item.status="{ item }">
						<!-- Active -->
						<v-btn v-if="!item.deletet_at || item.deleted_at === null" text color="#2ecc71">
							{{ $t('t_active') }}
						</v-btn>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at && item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Edit -->
								<v-list-item @click="edit(item, currencies.indexOf(item))" v-if="$owgate.allow($auth.user, 'edit', 'currencies')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item v-if="item.deleted_at === null && currencies.length > 1 && $owgate.allow($auth.user, 'delete', 'currencies')" @click="remove([item], currencies.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item v-if="item.deleted_at !== null && $owgate.allow($auth.user, 'delete', 'currencies')" @click="restore([item], currencies.indexOf(item))">
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
		      	<pagination :data="currenciesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'currencies')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/currencies')
			return {
				currenciesData: response.data,
				currencies: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_currency_name'), value: 'name', sortable: false, align: 'center'},
		          	{ text: this.$t('t_currency_code'), value: 'code', sortable: false, align: 'center'},
		          	{ text: this.$t('t_currency_locale'), value: 'locale', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        forms: {
		        	create: {
		        		name: '',
		        		code: '',
		        		locale: ''
		        	},
		        	edit: {
		        		index: '',
		        		id: '',
		        		name: '',
		        		code: '',
		        		locale: ''
		        	}
		        },
		        dialogs: {
		        	delete: false,
		        	create: false,
		        	edit: false
		        },
		        config: [],
		        locales: [],
		        errors: [],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_currencies'),
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
				.get('administrator/currencies?page=' + page)
				.then(response => {
					this.selected       = []
					this.currenciesData = response.data
					this.currencies     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading        = false
				})
			},

			create: function() {
				if (this.config.length === 0 || this.locales.length === 0) {
					this.fetchSettings()
				}
				this.dialogs.create = true

			},

			insert: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'create', 'currencies')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/currencies/options/create', {
					name: this.forms.create.name,
					code: this.forms.create.code,
					locale: this.forms.create.locale
				})
				.then(response => {
					this.currencies.push(response.data)
					this.errors       = []
					this.forms.create = {
						name: '',
						code: '',
						locale: ''
					}
					this.$toasted.show(this.$t('t_toasted_currency_created'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.dialogs.create = false
					this.loading        = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors = error.response.data.errors
					}
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			edit: function(currency, index) {
				if (this.config.length === 0 || this.locales.length === 0) {
					this.fetchSettings()
				}
				this.forms.edit.index  = index
				this.forms.edit.id     = currency.id
				this.forms.edit.name   = currency.name
				this.forms.edit.code   = currency.code
				this.forms.edit.locale = currency.locale
				this.dialogs.edit      = true
			},

			update: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'edit', 'currencies')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/currencies/options/edit', {
					id: this.forms.edit.id,
					name: this.forms.edit.name,
					code: this.forms.edit.code,
					locale: this.forms.edit.locale
				})
				.then(response => {
					this.currencies[this.forms.edit.index].name   = response.data.name
					this.currencies[this.forms.edit.index].code   = response.data.code
					this.currencies[this.forms.edit.index].locale = response.data.locale
					this.errors       = []
					this.forms.edit = {
						name: '',
						code: '',
						locale: '',
						id: '',
						index: ''
					}
					this.$toasted.show(this.$t('t_toasted_currency_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.dialogs.edit = false
					this.loading      = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors = error.response.data.errors
					}
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			remove: function(currencies = this.selected, index = null) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'delete', 'currencies')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/currencies/options/delete', {
					currencies: currencies
				})
				.then(response => {
					if (index !== null) {
						this.currencies[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedCurrency, selectedIndex) {
							vm.currencies.forEach(function(value, ind) {
								if (selectedCurrency.id === value.id) {
									vm.currencies[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_currencies_deleted'), {
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

			restore: function(currencies = this.selected, index = null) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'delete', 'currencies')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/currencies/options/restore', {
					currencies: currencies
				})
				.then(response => {
					if (index !== null) {
						this.currencies[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedCurrency, selectedIndex) {
							vm.currencies.forEach(function(value, ind) {
								if (selectedCurrency.id === value.id) {
									vm.currencies[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_currencies_restored'), {
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

			fetchSettings: function() {
				this.loading = true
				this.$axios
				.get('administrator/currencies/options/fetch')
				.then(response => {
					let vm = this
					 Object.keys(response.data.config).map((key) => {
						if (key.length === 3) {
				     		vm.config.push(key)	
						}
				   	})
					this.locales = response.data.locales
					this.loading = false
				})
				.catch(error => {
					console.log(error)
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			}
		}
  	}
</script>