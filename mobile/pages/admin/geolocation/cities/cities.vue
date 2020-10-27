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
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_edit_city') }}</h3>
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
									v-model="edit.city.name"
									color="grey lighten-1"
									:label="$t('t_city_name')"
									:placeholder="$t('t_enter_city_name')"
									counter="60"
									maxlength="60"
									:rules="errors.name"
									:error="errors.name ? true : false"
									></v-text-field>
							</v-flex>

							<!-- Country -->
							<v-flex xs12>
								<v-autocomplete
									@change="fetchStates()"
									autocomplete="off"
									:items="countries"
							        item-text="name"
							        item-value="id"
									v-model="edit.city.country"
									color="grey lighten-1"
									:label="$t('t_country')"
									:placeholder="$t('t_choose_country')"
									:rules="errors.country"
									:error="errors.country ? true : false"
									dense
									></v-autocomplete>
							</v-flex>

							<!-- State -->
							<v-flex xs12>
								<v-autocomplete
									autocomplete="off"
									:items="states"
							        item-text="name"
							        item-value="id"
									v-model="edit.city.state"
									color="grey lighten-1"
									:label="$t('t_state')"
									:placeholder="$t('t_choose_state')"
									:rules="errors.state"
									:error="errors.state ? true : false"
									dense
									></v-autocomplete>
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
					<v-toolbar-title>{{ $t('t_cities') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Create city -->
			        <v-tooltip top>
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/geolocation/cities/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					:headers="headers"
					:items="cities"
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
					<template v-slot:item.state="{ item }">
						<div v-if="item.division">{{ item.division.name }}</div>
					</template>

					<!-- City -->
					<template v-slot:item.city="{ item }">{{ item.name }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small icon @click="change(item, cities.indexOf(item))" v-if="$owgate.allow($auth.user, 'edit', 'geo')">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-square-edit-outline</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="citiesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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

			let response = await $axios.get('administrator/geolocation/cities')
			return {
				citiesData: response.data,
				cities: response.data.data
			}
		},

		data: function() {
			return {
				countries: [],
				states: [],
				headers: [
		          	{ text: this.$t('t_flag'), value: 'flag', sortable: false, align: 'center'},
		          	{ text: this.$t('t_country'), value: 'country', sortable: false, align: 'center'},
		          	{ text: this.$t('t_state'), value: 'state', sortable: false, align: 'center'},
		          	{ text: this.$t('t_city'), value: 'city', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        edit: {
		        	city: {
		        		id: '',
		        		name: '',
		        		country: '',
		        		state: ''
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
				title: this.$t('t_cities'),
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
				.get('administrator/geolocation/cities?page=' + page)
				.then(response => {
					this.selected   = []
					this.citiesData = response.data
					this.cities     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
			},

			change: function(city, index) {
				this.edit.city.id      = city.id
				this.edit.city.name    = city.name
				this.edit.city.country = city.country_id
				this.edit.city.state   = city.division_id
				this.edit.index        = index
				this.fetchStates(city.country_id)
				this.edit.dialog       = true
		   	},

		   	update: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'edit', 'geo')) {
					this.$router.push('/administrator')	
					return
				}

		   		this.loading = true
		   		this.$axios
		   		.post('administrator/geolocation/cities/options/edit', {
		   			id: this.edit.city.id,
		   			name: this.edit.city.name,
		   			country: this.edit.city.country,
		   			state: this.edit.city.state,
		   		})
		   		.then(response => {
					this.cities[this.edit.index].name        = response.data.name
					this.cities[this.edit.index].country_id  = response.data.country_id
					this.cities[this.edit.index].country     = response.data.country
					this.cities[this.edit.index].division_id = response.data.division_id
					this.cities[this.edit.index].division    = response.data.division
					this.edit.dialog                         = false
					this.edit.city.name                      = ''
					this.edit.city.country                   = ''
					this.edit.city.state                     = ''
					this.edit.city.index                     = null
		   			this.$toasted.show(this.$t('t_toasted_city_updated'), {
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
		   	},

		   	fetchStates: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/states', {
					country: this.edit.city.country
				})
				.then(response => {
					this.states = response.data.states
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					console.log(error.response.data.errors)
				})
			}
		},

		created() {
			this.getCountries()
		}
  	}
</script>