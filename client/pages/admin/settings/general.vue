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
		            <div class="title">{{ $t('t_general_settings') }}</div>
		            <span class="card-description">{{ $t('t_general_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Title -->
						<v-flex xs12>
							<v-text-field
								v-model="form.title"
								color="grey lighten-1"
								:label="$t('t_site_title')"
								:placeholder="$t('t_enter_site_title')"
								counter="100"
								maxlength="100"
								:rules="errors.title"
								:error="errors.title ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Tagline -->
						<v-flex xs12>
							<v-text-field
								v-model="form.tagline"
								color="grey lighten-1"
								:label="$t('t_site_tagline')"
								:placeholder="$t('t_enter_site_tagline')"
								counter="100"
								maxlength="100"
								:rules="errors.tagline"
								:error="errors.tagline ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Primary E-mail Address -->
						<v-flex xs12>
							<v-text-field
								v-model="form.email"
								color="grey lighten-1"
								:label="$t('t_site_email')"
								:placeholder="$t('t_enter_site_email')"
								counter="60"
								maxlength="60"
								:rules="errors.email"
								:error="errors.email ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Default Locale -->
						<v-flex xs4>
							<v-select
								v-model="form.locale"
								:items="locales"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_locale')"
								:placeholder="$t('t_choose_default_locale')"
								:rules="errors.locale"
								:error="errors.locale ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Default site direction -->
						<v-flex xs4>
							<v-select
								v-model="form.direction"
								:items="directions"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_direction')"
								:placeholder="$t('t_choose_default_direction')"
								:rules="errors.direction"
								:error="errors.direction ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Default timezone -->
						<v-flex xs4>
							<v-autocomplete
								autocomplete="off"
								v-model="form.timezone"
								:items="timezones"
								color="grey lighten-1"
								:label="$t('t_default_timezone')"
								:placeholder="$t('t_choose_default_timezone')"
								:rules="errors.timezone"
								:error="errors.timezone ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Transparent Header Logo -->
						<v-flex xs4>
							<upload-btn uniqueId @file-update="transparent" block accept="image/*" :title="$t('t_transparent_header_logo')" color="grey lighten-3" class="pa-0"></upload-btn>
						</v-flex>

						<!-- White Header Logo -->
						<v-flex xs4>
							<upload-btn uniqueId @file-update="white" block accept="image/*" :title="$t('t_white_header_logo')" color="grey lighten-3" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Favicon -->
						<v-flex xs4>
							<upload-btn uniqueId @file-update="favicon" block accept="image/*" :title="$t('t_upload_favicon')" color="grey lighten-3" class="pa-0"></upload-btn>
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
			if (!app.$owgate.allow(app.$auth.user, 'general', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/general')
			return {
				form: {
					title    : response.data.settings.title,
					tagline  : response.data.settings.tagline,
					email    : response.data.settings.email,
					locale   : response.data.settings.locale,
					timezone : response.data.settings.timezone,
					direction: response.data.settings.isRTL
				},
				timezones: response.data.timezones
			}
		},

		data: function() {
			return {
				locales: [
		          	{ id: 'en', name: this.$t('t_english') },
		          	{ id: 'fr', name: this.$t('t_french') },
		          	{ id: 'ar', name: this.$t('t_arabic') }
		        ],
				directions: [
		          	{ id: 1, name: this.$t('t_right_to_left') },
		          	{ id: 0, name: this.$t('t_left_to_right') }
		        ],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_general_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'general', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				let data     = new FormData()
				data.append('title', this.form.title)
				data.append('tagline', this.form.tagline)
				data.append('email', this.form.email)
				data.append('locale', this.form.locale)
				data.append('timezone', this.form.timezone)
				data.append('direction', this.form.direction)
				data.append('transparent', this.form.transparent)
				data.append('white', this.form.white)
				data.append('favicon', this.form.favicon)
				this.$axios
				.post('administrator/settings/general',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					if (this.form.direction) {
						this.$vuetify.rtl = true
					}else{
						this.$vuetify.rtl = false
					}
					this.errors  = []
					this.$toasted.show(this.$t('t_toasted_general_settings_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors  = error.response.data.errors
					}
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			transparent: function(file) {
				this.form.transparent = file
			},

			white: function(file) {
				this.form.white       = file
			},

			favicon: function(file) {
				this.form.favicon     = file
			}
		}
	}
</script>