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
		            <div class="title">{{ $t('t_create_user') }}</div>
		            <span class="card-description">{{ $t('t_fill_to_create_user') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Username -->
						<v-flex xs6>
							<v-text-field
								v-model="form.username"
								color="grey lighten-1"
								:label="$t('t_username')"
								:placeholder="$t('t_enter_username')"
								counter="60"
								maxlength="60"
								:rules="errors.username"
								:error="errors.username ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Password -->
						<v-flex xs6>
							<v-text-field
								v-model="form.password"
								color="grey lighten-1"
								:label="$t('t_password')"
								:placeholder="$t('t_enter_password')"
								type="password"
								:rules="errors.password"
								:error="errors.password ? true : false"
								></v-text-field>
						</v-flex>

						<!-- E-mail address -->
						<v-flex xs6>
							<v-text-field
								v-model="form.email"
								color="grey lighten-1"
								:label="$t('t_email')"
								:placeholder="$t('t_enter_email')"
								type="email"
								:rules="errors.email"
								:error="errors.email ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Phone number -->
						<v-flex xs6>
							<v-text-field
								v-model="form.phone"
								color="grey lighten-1"
								:label="$t('t_phone')"
								:placeholder="$t('t_enter_phone')"
								:rules="errors.phone"
								:error="errors.phone ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Avatar -->
						<v-flex xs12 class="mb-3">
							<upload-btn @file-update="avatar" block accept="image/*" :title="$t('t_upload_avatar')" color="grey lighten-3" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Role -->
						<v-flex xs6>
							<v-select
								v-model="form.role"
								:items="roles"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_role')"
								:placeholder="$t('t_choose_role')"
								:rules="errors.role"
								:error="errors.role ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Plan -->
						<v-flex xs6>
							<v-select
								v-model="form.plan"
								:items="plans"
						        item-text="title"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_plan')"
								:placeholder="$t('t_choose_default_subscription_plan')"
								:rules="errors.plan"
								:error="errors.plan ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Country -->
						<v-flex xs4>
							<v-autocomplete
								@change="fetchStates()"
								autocomplete="off"
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

						<!-- State -->
						<v-flex xs4>
							<v-autocomplete
								@change="fetchCities()"
								autocomplete="off"
								v-model="form.state"
								:items="states"
					          	item-text="name"
					          	item-value="id"
								color="grey lighten-1"
								:label="$t('t_state')"
								:placeholder="$t('t_choose_state')"
								:rules="errors.state"
								:error="errors.state ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- City -->
						<v-flex xs4>
							<v-autocomplete
								autocomplete="off"
								v-model="form.city"
								:items="cities"
					          	item-text="name"
					          	item-value="id"
								color="grey lighten-1"
								:label="$t('t_city')"
								:placeholder="$t('t_choose_city')"
								:rules="errors.city"
								:error="errors.city ? true : false"
								dense
								></v-autocomplete>
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
			if (!app.$adgate.allow(app.$auth.user, 'create', 'users')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/users/options/create')
			return {
				roles: response.data.roles,
				plans: response.data.plans,
				countries: response.data.countries
			}
		},

		data: function() {
			return {
				form: {
					username: '',
					password: '',
					email: '',
					phone: '',
					avatar: '',
					role: '',
					plan: '',
					country: '',
					state: '',
					city: '',
				},
				states: [],
				cities: [],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_user'),
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
				if (!this.$adgate.allow(this.$auth.user, 'create', 'users')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				let data     = new FormData()
				data.append('username', this.form.username)
				data.append('password', this.form.password)
				data.append('email', this.form.email)
				data.append('phone', this.form.phone)
				data.append('avatar', this.form.avatar)
				data.append('role', this.form.role)
				data.append('plan', this.form.plan)
				data.append('country', this.form.country)
				data.append('state', this.form.state)
				data.append('city', this.form.city)
				this.$axios
				.post('administrator/users/options/create',
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
						username: '',
						password: '',
						email: '',
						phone: '',
						role: '',
						plan: '',
						country: '',
						state: '',
						city: ''
					}
					this.$toasted.show(this.$t('t_toasted_user_created'), {
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

			avatar: function(file) {
				this.form.avatar = file
			},

			fetchStates: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/states', {
					country: this.form.country
				})
				.then(response => {
					this.states = response.data.states
					this.cities = response.data.cities
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					alert(error.response.data.errors)
				})
			},

			fetchCities: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/cities', {
					state: this.form.state
				})
				.then(response => {
					this.cities = response.data
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					alert(error.response.data.errors)
				})
			}
		}
  	}
</script>