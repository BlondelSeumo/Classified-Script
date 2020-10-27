<template>
	<v-app id="inspire">
		<v-overlay v-model="loading.dialog" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>
		
		<v-container>
			<v-card class="m-card" flat>
				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Avatar -->
						<v-flex xs12 class="text-center mb-4">
	                        <v-avatar size="110" class="mb-3">
	                            <img :src="form.preview ? form.preview : preview()" alt="Avatar">
	                        </v-avatar>
	                        <client-only>
		                        <upload-btn @file-update="avatar" accept="image/*" :title="$t('t_change_avatar')" color="grey lighten-3" class="pa-0 text-small"></upload-btn>
		                    </client-only>
						</v-flex>

						<!-- Username -->
						<v-flex xs4>
							<v-text-field
								v-model="form.username"
								color="grey lighten-1"
								:label="$t('t_username')"
								:placeholder="$t('t_enter_username')"
								:rules="errors.username"
								:error="errors.username ? true : false"
								></v-text-field>
						</v-flex>

						<!-- E-mail address -->
						<v-flex xs4>
							<v-text-field
								v-model="form.email"
								color="grey lighten-1"
								:label="$t('t_email')"
								:placeholder="$t('t_enter_email')"
								:rules="errors.email"
								:error="errors.email ? true : false"
								type="email"
								></v-text-field>
						</v-flex>

						<!-- Phone number -->
						<v-flex xs4>
							<v-text-field
								v-model="form.phone"
								color="grey lighten-1"
								:label="$t('t_phone')"
								:placeholder="$t('t_enter_phone')"
								:rules="errors.phone"
								:error="errors.phone ? true : false"
								></v-text-field>
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

						<!-- Current password -->
						<v-flex xs6>
							<v-text-field
								v-model="form.current_password"
								color="grey lighten-1"
								:label="$t('t_current_password')"
								:placeholder="$t('t_enter_current_password')"
								persistent-hint
								:hint="$t('t_password_leave_empty')"
								:rules="errors.current_password"
								:error="errors.current_password ? true : false"
								type="password"
								></v-text-field>
						</v-flex>

						<!-- New password -->
						<v-flex xs6>
							<v-text-field
								v-model="form.new_password"
								color="grey lighten-1"
								:label="$t('t_new_password')"
								:placeholder="$t('t_enter_new_password')"
								persistent-hint
								:hint="$t('t_password_leave_empty')"
								:rules="errors.new_password"
								:error="errors.new_password ? true : false"
								type="password"
								></v-text-field>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update()" depressed :loading="loading.button" :disabled="loading.button" block color="light-blue lighten-1" class="white--text mt-0">{{ $t('t_update') }}</v-btn>
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
			let response = await $axios.post('ajax/fetch/countries')
			return {
				countries: response.data
			}
		},

		data: function() {
			return {
				form: {
					username: this.$auth.user.username,
					current_password: '',
					new_password: '',
					email: this.$auth.user.email,
					phone: this.$auth.user.phone,
					avatar: '',
					preview: '',
					country: this.$auth.user.country,
					state: this.$auth.user.state,
					city: this.$auth.user.city
				},
				states: [],
				cities: [],
				errors: [],
				loading: {
					dialog: false,
					button: false
				}
			}
		},

		head() {
			return {
				title: this.$t('t_my_profile'),
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
				this.loading.dialog = true
				this.loading.button = true
				let data     = new FormData()
				data.append('username', this.form.username)
				data.append('current_password', this.form.current_password)
				data.append('new_password', this.form.new_password)
				data.append('email', this.form.email)
				data.append('avatar', this.form.avatar)
				if (this.form.phone) {
					data.append('phone', this.form.phone)
				}
				if (this.form.country) {
					data.append('country', this.form.country)
				}
				if (this.form.state) {
					data.append('state', this.form.state)
				}
				if (this.form.city) {
					data.append('city', this.form.city)
				}
				this.$axios
				.post('administrator/profile',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					this.errors     = []
					this.$auth.fetchUser()
					this.$toasted.show(this.$t('t_toasted_profile_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading.dialog = false
					this.loading.button = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors   = error.response.data.errors
					}
					if (error.response.data === 'error_password') {
						this.$toasted.error(this.$t('t_toasted_password_not_match'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})	
					}else{
						this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
							icon: 'error',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
					}
					this.loading.dialog = false
					this.loading.button = false
				})
			},

			fetchStates: function() {
				this.loading.dialog = true
				this.$axios
				.post('ajax/fetch/states', {
					country: this.form.country
				})
				.then(response => {
					this.states = response.data.states
					this.cities = response.data.cities
					this.loading.dialog = false
				})
				.catch(error => {
					this.loading.dialog = false
				})
			},

			fetchCities: function() {
				this.loading.dialog = true
				this.$axios
				.post('ajax/fetch/cities', {
					state: this.form.state
				})
				.then(response => {
					this.cities         = response.data
					this.loading.dialog = false
				})
				.catch(error => {
					this.loading.dialog = false
				})
			},

			avatar: function(file) {
				this.form.preview = URL.createObjectURL(file);
				this.form.avatar  = file
			},

			preview() {
                if (this.$auth.user.avatar !== null) {
                    return this.$homeApi('storage/app/' + this.$auth.user.avatar)
                }else{
                    return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
                }
            }
		},

		created: function() {
			if (this.$auth.user.country) {
				this.fetchStates()
			}
			if (this.$auth.user.state) {
				this.fetchCities()
			}
		}
	}
</script>