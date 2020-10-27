<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<v-card class="m-card" flat>
				<v-card-title primary-title class="pa-4">
		          <div>
		            <div class="title">{{ $t('t_geo_settings') }}</div>
		            <span class="card-description">{{ $t('t_geo_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Enable multiple countries -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.multiple_countries ? 'has-danger' : '']">
								<v-switch v-model="form.multiple_countries" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_multi_countries') }}</span>
							</label>
							<small class="red-text" v-if="errors.multiple_countries">{{ errors.multiple_countries[0] }}</small>
						</v-flex>

						<!-- Enable States -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_states ? 'has-danger' : '']">
								<v-switch v-model="form.enable_states" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_states') }}</span>
								<small class="form-text" v-if="errors.enable_states">{{ errors.enable_states[0] }}</small>
							</label>
						</v-flex>

						<!-- Enable Cities -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_cities ? 'has-danger' : '']">
								<v-switch v-model="form.enable_cities" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_cities') }}</span>
								<small class="form-text" v-if="errors.enable_cities">{{ errors.enable_cities[0] }}</small>
							</label>
						</v-flex>

						<!-- Default Country -->
						<v-flex xs12>
							<v-autocomplete
								@change="fetchStates()"
								autocomplete="off"
								v-model="form.default_country"
								:items="countries"
					          	item-text="name"
					          	item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_country')"
								:placeholder="$t('t_choose_default_country')"
								:rules="errors.default_country"
								:error="errors.default_country ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Default State -->
						<v-flex xs12>
							<v-autocomplete
								@change="fetchCities()"
								autocomplete="off"
								v-model="form.default_state"
								:items="states"
					          	item-text="name"
					          	item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_state')"
								:placeholder="$t('t_choose_default_state')"
								:rules="errors.default_state"
								:error="errors.default_state ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Default City -->
						<v-flex xs12>
							<v-autocomplete
								autocomplete="off"
								v-model="form.default_city"
								:items="cities"
					          	item-text="name"
					          	item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_city')"
								:placeholder="$t('t_choose_default_city')"
								:rules="errors.default_city"
								:error="errors.default_city ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Default currency -->
						<v-flex xs12>
							<v-select
								v-model="form.default_currency"
								:items="currencies"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_currency')"
								:placeholder="$t('t_choose_default_currency')"
								:rules="errors.default_currency"
								:error="errors.default_currency ? true : false"
								dense
								></v-select>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
						</v-flex>

					</v-layout>

				</v-container>

			</v-card>
		</v-container>

	</v-app>
</template>

<script>

	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'geo', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/geo')
			return {
				form: {
					multiple_countries : response.data.settings.isMultipleCountries,
					enable_states      : response.data.settings.enableStates,
					enable_cities      : response.data.settings.enableCities,
					default_country    : response.data.settings.defaultCountry,
					default_state      : response.data.settings.defaultState,
					default_city       : response.data.settings.defaultCity,
					default_currency   : response.data.settings.defaultCurrency
				},
				countries: response.data.countries,
				currencies: response.data.currencies
			}
		},

		data: function() {
			return {
				states: [],
				cities: [],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_geo_settings'),
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
			update: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'geo', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/geo', {
					multiple_countries: this.form.multiple_countries,
					enable_states: this.form.enable_states,
					enable_cities: this.form.enable_cities,
					default_country: this.form.default_country,
					default_state: this.form.default_state,
					default_city: this.form.default_city,
					default_currency: this.form.default_currency
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_geo_settings_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
				.catch(error => {
					this.errors   = error.response.data.errors
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			fetchStates: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/states', {
					country: this.form.default_country
				})
				.then(response => {
					this.states = response.data.states
					this.cities = response.data.cities
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					console.log(error.response.data.errors)
				})
			},

			fetchCities: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/cities', {
					state: this.form.default_state
				})
				.then(response => {
					this.cities  = response.data
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					console.log(error.response.data.errors)
				})
			}
		},

		created() {
			this.fetchStates()
			this.fetchCities()
		}
	}
</script>