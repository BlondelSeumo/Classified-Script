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
		            <div class="title">{{ $t('t_social_settings') }}</div>
		            <span class="card-description">{{ $t('t_social_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Enable Facebook -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_facebook ? 'has-danger' : '']">
								<v-switch v-model="form.enable_facebook" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_facebook') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_facebook">{{ errors.enable_facebook[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.facebook_key"
								color="grey lighten-1"
								:label="$t('t_facebook_app_id')"
								:placeholder="$t('t_enter_facebook_app_id')"
								:rules="errors.facebook_key"
								:error="errors.facebook_key ? true : false"
								:disabled="!form.enable_facebook"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.facebook_secret"
								color="grey lighten-1"
								:label="$t('t_facebook_app_secret')"
								:placeholder="$t('t_enter_facebook_app_secret')"
								:rules="errors.facebook_secret"
								:error="errors.facebook_secret ? true : false"
								:disabled="!form.enable_facebook"
								></v-text-field>
						</v-flex>

						<!-- Enable Twitter -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_twitter ? 'has-danger' : '']">
								<v-switch v-model="form.enable_twitter" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_twitter') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_twitter">{{ errors.enable_twitter[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.twitter_key"
								color="grey lighten-1"
								:label="$t('t_twitter_customer_key')"
								:placeholder="$t('t_enter_twitter_customer_key')"
								:rules="errors.twitter_key"
								:error="errors.twitter_key ? true : false"
								:disabled="!form.enable_twitter"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.twitter_secret"
								color="grey lighten-1"
								:label="$t('t_twitter_customer_secret')"
								:placeholder="$t('t_enter_twitter_customer_secret')"
								:rules="errors.twitter_secret"
								:error="errors.twitter_secret ? true : false"
								:disabled="!form.enable_twitter"
								></v-text-field>
						</v-flex>

						<!-- Enable Google -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_google ? 'has-danger' : '']">
								<v-switch v-model="form.enable_google" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_google') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_google">{{ errors.enable_google[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.google_key"
								color="grey lighten-1"
								:label="$t('t_enter_google_key')"
								:placeholder="$t('t_enter_google_key')"
								:rules="errors.google_key"
								:error="errors.google_key ? true : false"
								:disabled="!form.enable_google"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.google_secret"
								color="grey lighten-1"
								:label="$t('t_google_secret')"
								:placeholder="$t('t_enter_google_secret')"
								:rules="errors.google_secret"
								:error="errors.google_secret ? true : false"
								:disabled="!form.enable_google"
								></v-text-field>
						</v-flex>

						<!-- Enable Instagram -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_instagram ? 'has-danger' : '']">
								<v-switch v-model="form.enable_instagram" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_instagram') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_instagram">{{ errors.enable_instagram[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.instagram_key"
								color="grey lighten-1"
								:label="$t('t_instagram_key')"
								:placeholder="$t('t_enter_instagram_key')"
								:rules="errors.instagram_key"
								:error="errors.instagram_key ? true : false"
								:disabled="!form.enable_instagram"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.instagram_secret"
								color="grey lighten-1"
								:label="$t('t_instagram_secret')"
								:placeholder="$t('t_enter_instagram_secret')"
								:rules="errors.instagram_secret"
								:error="errors.instagram_secret ? true : false"
								:disabled="!form.enable_instagram"
								></v-text-field>
						</v-flex>

						<!-- Enable Pinterest -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_pinterest ? 'has-danger' : '']">
								<v-switch v-model="form.enable_pinterest" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_pinterest') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_pinterest">{{ errors.enable_pinterest[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.pinterest_key"
								color="grey lighten-1"
								:label="$t('t_pinterest_key')"
								:placeholder="$t('t_enter_pinterest_key')"
								:rules="errors.pinterest_key"
								:error="errors.pinterest_key ? true : false"
								:disabled="!form.enable_pinterest"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.pinterest_secret"
								color="grey lighten-1"
								:label="$t('t_pinterest_secret')"
								:placeholder="$t('t_enter_pinterest_secret')"
								:rules="errors.pinterest_secret"
								:error="errors.pinterest_secret ? true : false"
								:disabled="!form.enable_pinterest"
								></v-text-field>
						</v-flex>

						<!-- Enable Linkedin -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_linkedin ? 'has-danger' : '']">
								<v-switch v-model="form.enable_linkedin" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_linkedin') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_linkedin">{{ errors.enable_linkedin[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.linkedin_key"
								color="grey lighten-1"
								:label="$t('t_linkedin_key')"
								:placeholder="$t('t_enter_linkedin_key')"
								:rules="errors.linkedin_key"
								:error="errors.linkedin_key ? true : false"
								:disabled="!form.enable_linkedin"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.linkedin_secret"
								color="grey lighten-1"
								:label="$t('t_linkedin_secret')"
								:placeholder="$t('t_enter_linkedin_secret')"
								:rules="errors.linkedin_secret"
								:error="errors.linkedin_secret ? true : false"
								:disabled="!form.enable_linkedin"
								></v-text-field>
						</v-flex>

						<!-- Enable Vk -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_vk ? 'has-danger' : '']">
								<v-switch v-model="form.enable_vk" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_vk') }}</span>
							</label>
							<small class="red--text" v-if="errors.enable_vk">{{ errors.enable_vk[0] }}</small>
						</v-flex>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.vk_key"
								color="grey lighten-1"
								:label="$t('t_vk_key')"
								:placeholder="$t('t_enter_vk_key')"
								:rules="errors.vk_key"
								:error="errors.vk_key ? true : false"
								:disabled="!form.enable_vk"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.vk_secret"
								color="grey lighten-1"
								:label="$t('t_vk_secret')"
								:placeholder="$t('t_enter_vk_secret')"
								:rules="errors.vk_secret"
								:error="errors.vk_secret ? true : false"
								:disabled="!form.enable_vk"
								></v-text-field>
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
			if (!app.$owgate.allow(app.$auth.user, 'social', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/social')
			return {
				form: {
					enable_facebook : response.data.settings.isFacebook,
					enable_twitter  : response.data.settings.isTwitter,
					enable_google   : response.data.settings.isGoogle,
					enable_instagram: response.data.settings.isInstagram,
					enable_pinterest: response.data.settings.isPinterest,
					enable_linkedin : response.data.settings.isLinkedin,
					enable_vk       : response.data.settings.isVk,
					
					facebook_key :  response.data.config.facebook.client_id,
					twitter_key  :  response.data.config.twitter.client_id,
					google_key   :  response.data.config.google.client_id,
					instagram_key:  response.data.config.instagram.client_id,
					pinterest_key:  response.data.config.pinterest.client_id,
					linkedin_key :  response.data.config.linkedin.client_id,
					vk_key       :  response.data.config.vkontakte.client_id,
					
					facebook_secret : response.data.config.facebook.client_secret,
					twitter_secret  : response.data.config.twitter.client_secret,
					google_secret   : response.data.config.google.client_secret,
					instagram_secret: response.data.config.instagram.client_secret,
					pinterest_secret: response.data.config.pinterest.client_secret,
					linkedin_secret : response.data.config.linkedin.client_secret,
					vk_secret       : response.data.config.vkontakte.client_secret,
				}
			}
		},

		data: function() {
			return {
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_social_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'social', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/social', {
					enable_facebook: this.form.enable_facebook,
					enable_twitter: this.form.enable_twitter,
					enable_google: this.form.enable_google,
					enable_instagram: this.form.enable_instagram,
					enable_pinterest: this.form.enable_pinterest,
					enable_linkedin: this.form.enable_linkedin,
					enable_vk: this.form.enable_vk,

					facebook_key: this.form.facebook_key,
					twitter_key: this.form.twitter_key,
					google_key: this.form.google_key,
					instagram_key: this.form.instagram_key,
					pinterest_key: this.form.pinterest_key,
					linkedin_key: this.form.linkedin_key,
					vk_key: this.form.vk_key,

					facebook_secret: this.form.facebook_secret,
					twitter_secret: this.form.twitter_secret,
					google_secret: this.form.google_secret,
					instagram_secret: this.form.instagram_secret,
					pinterest_secret: this.form.pinterest_secret,
					linkedin_secret: this.form.linkedin_secret,
					vk_secret: this.form.vk_secret,
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_social_settings_updated'), {
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
			}
		}
	}
</script>