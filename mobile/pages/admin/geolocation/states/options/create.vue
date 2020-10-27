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
		            <div class="title">{{ $t('t_create_state') }}</div>
		            <span class="card-description">{{ $t('t_create_state_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Country -->
						<v-flex xs12>
							<v-autocomplete
								v-model="form.country"
								:items="countries"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_country')"
								:placeholder="$t('t_choose_country')"
								:rules="errors.country"
								:error="errors.country ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- State name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_state_name')"
								:placeholder="$t('t_enter_state_name')"
								counter="60"
								maxlength="60"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- State has cities -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.has_cities ? 'has-danger' : '']">
								<v-switch :true-value="1" :false-value="0" v-model="form.has_cities" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_state_has_cities') }}</span>
							</label>
							<small class="error-text" v-if="errors.has_cities">{{ errors.has_cities[0] }}</small>
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

			let response = await $axios.get('administrator/geolocation/states/options/create')
			return {
				countries: response.data
			}
		},

		data: function() {
			return {
				form: {
					name: '',
					country: '',
					has_cities: 0
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_state'),
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
				this.$axios
				.post('administrator/geolocation/states/options/create',{
					name: this.form.name,
					country: this.form.country,
					has_cities: this.form.has_cities
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						country: '',
						has_cities: 0
					}
					this.$toasted.show(this.$t('t_toasted_state_created'), {
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
			}
		}
	}
</script>