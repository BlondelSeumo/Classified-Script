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
		            <div class="title">{{ $t('t_create_country') }}</div>
		            <span class="card-description">{{ $t('t_create_country_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Continent -->
						<v-flex xs12>
							<v-select
								v-model="form.continent"
								:items="continents"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_continent')"
								:placeholder="$t('t_choose_continent')"
								:rules="errors.continent"
								:error="errors.continent ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Country name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_country_name')"
								:placeholder="$t('t_enter_country_name')"
								counter="60"
								maxlength="60"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Country code -->
						<v-flex xs12>
							<v-text-field
								v-model="form.code"
								color="grey lighten-1"
								:label="$t('t_country_code')"
								:placeholder="$t('t_enter_country_code')"
								counter="2"
								maxlength="2"
								:rules="errors.code"
								:error="errors.code ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Country capital -->
						<v-flex xs12>
							<v-text-field
								v-model="form.capital"
								color="grey lighten-1"
								:label="$t('t_capital')"
								:placeholder="$t('t_enter_capital')"
								counter="60"
								maxlength="60"
								:rules="errors.capital"
								:error="errors.capital ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Country calling code -->
						<v-flex xs12>
							<v-text-field
								v-model="form.callingcode"
								color="grey lighten-1"
								:label="$t('t_calling_code')"
								:placeholder="$t('t_enter_country_calling_code')"
								type="number"
								:rules="errors.callingcode"
								:error="errors.callingcode ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Flag -->
						<v-flex xs12 class="mb-3">
							<upload-btn @file-update="flag" block accept="image/*" :title="$t('t_upload_flag')" :color="$vuetify.theme.dark ? 'grey darken-4' : 'grey lighten-3'" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Country has states -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.has_states ? 'has-danger' : '']">
								<v-switch :true-value="1" :false-value="0" v-model="form.has_states" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_country_has_states') }}</span>
							</label>
							<small class="error-text" v-if="errors.has_states">{{ errors.has_states[0] }}</small>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="create" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_create') }}</v-btn>
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
			if (!app.$owgate.allow(app.$auth.user, 'create', 'geo')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/geolocation/countries/options/create')
			return {
				continents: response.data
			}
		},

		data: function() {
			return {
				form: {
					name: '',
					capital: '',
					continent: '',
					flag: '',
					code: '',
					has_states: 0,
					callingcode: ''
				},
				errors: [],
				loading: false,
				pageLoaded: true
			}
		},

		head() {
			return {
				title: this.$t('t_create_country'),
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
			create: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'create', 'geo')) {
					this.$router.push('/administrator')	
					return
				}
				
				this.loading  = true
				let data      = new FormData()
				data.append('name', this.form.name)
				data.append('continent', this.form.continent)
				data.append('callingcode', this.form.callingcode)
				data.append('code', this.form.code)
				data.append('capital', this.form.capital)
				data.append('flag', this.form.flag)
				data.append('has_states', this.form.has_states)
				this.$axios
				.post('administrator/geolocation/countries/options/create',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						capital: '',
						continent: '',
						flag: '',
						code: '',
						has_states: 0,
						callingcode: ''
					}
					this.$toasted.show(this.$t('t_toasted_country_created'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors   = error.response.data.errors
					}
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			flag: function(file) {
				this.form.flag = file
			}
		}
	}
</script>