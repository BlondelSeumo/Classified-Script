<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Edit dialog -->
		<v-dialog v-model="edit.dialog" max-width="500px" persistent v-if="$owgate.allow($auth.user, 'edit', 'geo')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_edit_state') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="edit.dialog = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>

							<!-- Name -->
							<v-flex xs12>
								<v-text-field
									v-model="edit.state.name"
									color="grey lighten-1"
									:label="$t('t_state_name')"
									:placeholder="$t('t_enter_state_name')"
									counter="60"
									maxlength="60"
									:rules="errors.name"
									:error="errors.name ? true : false"
									></v-text-field>
							</v-flex>

							<!-- Country -->
							<v-flex xs12>
								<v-autocomplete
									autocomplete="off"
									:items="countries"
							        item-text="name"
							        item-value="id"
									v-model="edit.state.country"
									color="grey lighten-1"
									:label="$t('t_country')"
									:placeholder="$t('t_choose_country')"
									:rules="errors.country"
									:error="errors.country ? true : false"
									dense
									></v-autocomplete>
							</v-flex>

							<!-- Cities -->
							<v-flex xs12>
								<label class="form-group has-float-label" :class="[errors.has_city ? 'has-danger' : '']">
									<v-switch v-model="edit.state.has_city" class="ma-0 form-group form-control" color="blue"></v-switch>
									<span>{{ $t('t_state_has_cities') }}</span>
									<small class="form-text" v-if="errors.has_city">{{ errors.has_city[0] }}</small>
								</label>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn :color="$vuetify.theme.dark ? '#bfbfbf' : '#2e3131'" text @click="update()">{{ $t('t_update') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_states') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Create state -->
			        <v-tooltip top>
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/geolocation/states/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					:headers="headers"
					:items="states"
					item-key="id"
      				hide-default-footer
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Flag -->
					<template v-slot:item.flag="{ item }">
						<v-avatar size="36px">
							<img :src="$homeApi('public/images/flags/large/' + item.country.code.toUpperCase() + '.png')">
						</v-avatar>
					</template>

					<!-- Country -->
					<template v-slot:item.country="{ item }">{{ item.country.name }}</template>

					<!-- State -->
					<template v-slot:item.state="{ item }">{{ item.name }}</template>

					<!-- Cities -->
					<template v-slot:item.cities="{ item }">
						<v-tooltip top v-if="item.has_city">
							<template v-slot:activator="{ on }">
								<v-btn text icon color="green accent-3" v-on="on">
									<i class="mdi mdi-check-all fs-20"></i>
								</v-btn>
							</template>
							<span>{{ $t('t_has_cities') }}</span>
						</v-tooltip>
						<v-tooltip top v-else>
							<template v-slot:activator="{ on }">
								<v-btn text icon color="error" v-on="on">
									<i class="mdi mdi-close fs-20"></i>
								</v-btn>
							</template>
							<span>{{ $t('t_no_cities') }}</span>
						</v-tooltip>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small icon @click="change(item, states.indexOf(item))" v-if="$owgate.allow($auth.user, 'edit', 'geo')">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-square-edit-outline</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="statesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'geo')) {
				redirect('/administrator')
			}
			
			let response = await $axios.get('administrator/geolocation/states')
			return {
				statesData: response.data,
				states: response.data.data
			}
		},

		data: function() {
			return {
				countries: [],
				headers: [
		          	{ text: this.$t('t_flag'), value: 'flag', sortable: false, align: 'center'},
		          	{ text: this.$t('t_country'), value: 'country', sortable: false, align: 'center'},
		          	{ text: this.$t('t_state'), value: 'state', sortable: false, align: 'center'},
		          	{ text: this.$t('t_cities'), value: 'cities', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        edit: {
		        	state: {
		        		id: '',
		        		name: '',
		        		country: '',
		        		has_city: false
		        	},
		        	index: null,
		        	dialog: false
		        },
		        errors: [],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_states'),
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
				.get('administrator/geolocation/states?page=' + page)
				.then(response => {
					this.selected   = []
					this.statesData = response.data
					this.states     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
			},

		   	change: function(state, index) {
				this.edit.state.id       = state.id
				this.edit.state.name     = state.name
				this.edit.state.country  = state.country_id
				this.edit.state.has_city = state.has_city
				this.edit.index          = index
				this.edit.dialog         = true
		   	},

		   	update: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'edit', 'geo')) {
					this.$router.push('/administrator')	
					return
				}

		   		this.loading = true
		   		this.$axios
		   		.post('administrator/geolocation/states/options/edit', {
		   			id: this.edit.state.id,
		   			name: this.edit.state.name,
		   			country: this.edit.state.country,
		   			has_city: this.edit.state.has_city,
		   		})
		   		.then(response => {
					this.states[this.edit.index].name       = response.data.name
					this.states[this.edit.index].country    = response.data.country
					this.states[this.edit.index].country_id = response.data.country_id
					this.states[this.edit.index].has_city   = response.data.has_city
					this.edit.dialog                        = false
					this.edit.state.name                    = ''
					this.edit.state.country                 = ''
					this.edit.state.has_city                = false
					this.edit.state.index                   = null
		   			this.$toasted.show(this.$t('t_toasted_state_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
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

		   	getCountries: function() {
		   		this.$axios
		   		.post('ajax/fetch/countries')
		   		.then(response => {
					this.countries = response.data
		   		})
		   		.catch(error => {
		   			console.log(error)
		   		})
		   	}
		},

		created() {
			this.getCountries()
		}
  	}
</script>