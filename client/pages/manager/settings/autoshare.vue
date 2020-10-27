<template>
	<v-app id="inspire">

		<v-dialog v-model="loading" persistent elevation-0 width="100" content-class="elevation-0">
			<v-card flat>
				<v-card-text class="pa-4 text-center">
                    <v-progress-circular
                        :size="45"
						width="2"
                        color="light-blue"
                        indeterminate
                    ></v-progress-circular>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-container>
			<v-card class="m-card" flat>
				
				<v-tabs
					centered
					color="white"
					icons-and-text
					>
					<v-tabs-slider color="#6ecfff"></v-tabs-slider>
					<v-tab href="#facebook">
						{{ $t('t_facebook') }}
						<v-icon>mdi-facebook</v-icon>
					</v-tab>
					<v-tab href="#twitter">
						{{ $t('t_twitter') }}
						<v-icon>mdi-twitter</v-icon>
					</v-tab>
					<v-tab href="#telegram">
						{{ $t('t_telegram') }}
						<v-icon>mdi-telegram</v-icon>
					</v-tab>

					<!-- Facebook -->
					<v-tab-item value="facebook"
						>
						<v-container grid-list-xl fluid class="pa-4 mt-4">
							<v-layout wrap>

								<!-- Enable Facebook -->
								<v-flex xs12>
									<label class="form-group has-float-label" :class="[errors.enable_facebook ? 'has-danger' : '']">
										<v-switch v-model="form.enable_facebook" :true-value="1" :false-value="0" class="ma-0 form-group form-control" color="blue"></v-switch>
										<span>{{ $t('t_enable_facebook') }}</span>
									</label>
									<small class="red--text" v-if="errors.enable_facebook">{{ errors.enable_facebook[0] }}</small>
								</v-flex>

								<!-- App Id -->
								<v-flex xs12>
									<v-text-field
										v-model="form.facebook_app_id"
										color="grey lighten-1"
										:label="$t('t_facebook_app_id')"
										:placeholder="$t('t_enter_facebook_app_id')"
										:rules="errors.facebook_app_id"
										:error="errors.facebook_app_id ? true : false"
										></v-text-field>
								</v-flex>

								<!-- App secret -->
								<v-flex xs12>
									<v-text-field
										v-model="form.facebook_app_secret"
										color="grey lighten-1"
										:label="$t('t_facebook_app_secret')"
										:placeholder="$t('t_enter_facebook_app_secret')"
										:rules="errors.facebook_app_secret"
										:error="errors.facebook_app_secret ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Page access token -->
								<v-flex xs12>
									<v-text-field
										v-model="form.facebook_page_access_token"
										color="grey lighten-1"
										:label="$t('t_facebook_page_access_token')"
										:placeholder="$t('t_enter_facebook_page_access_token')"
										:rules="errors.facebook_page_access_token"
										:error="errors.facebook_page_access_token ? true : false"
										></v-text-field>
								</v-flex>

								<v-flex xs12>
									<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
								</v-flex>

							</v-layout>

						</v-container>
					</v-tab-item>

					<!-- Twitter -->
					<v-tab-item value="twitter"
						>
						<v-container grid-list-xl fluid class="pa-4 mt-4">
							<v-layout wrap>

								<!-- Enable Twitter -->
								<v-flex xs12>
									<label class="form-group has-float-label" :class="[errors.enable_twitter ? 'has-danger' : '']">
										<v-switch v-model="form.enable_twitter" :true-value="1" :false-value="0" class="ma-0 form-group form-control" color="blue"></v-switch>
										<span>{{ $t('t_enable_twitter') }}</span>
									</label>
									<small class="red--text" v-if="errors.enable_twitter">{{ errors.enable_twitter[0] }}</small>
								</v-flex>

								<!-- Consumer key -->
								<v-flex xs12>
									<v-text-field
										v-model="form.twitter_consumer_key"
										color="grey lighten-1"
										:label="$t('t_twitter_customer_key')"
										:placeholder="$t('t_enter_twitter_customer_key')"
										:rules="errors.twitter_consumer_key"
										:error="errors.twitter_consumer_key ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Consumer secret -->
								<v-flex xs12>
									<v-text-field
										v-model="form.twitter_consumer_secret"
										color="grey lighten-1"
										:label="$t('t_twitter_customer_secret')"
										:placeholder="$t('t_enter_twitter_customer_secret')"
										:rules="errors.twitter_consumer_secret"
										:error="errors.twitter_consumer_secret ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Access token -->
								<v-flex xs12>
									<v-text-field
										v-model="form.twitter_access_token"
										color="grey lighten-1"
										:label="$t('t_twitter_access_token')"
										:placeholder="$t('t_enter_twitter_access_token')"
										:rules="errors.twitter_access_token"
										:error="errors.twitter_access_token ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Access token secret -->
								<v-flex xs12>
									<v-text-field
										v-model="form.twitter_access_token_secret"
										color="grey lighten-1"
										:label="$t('t_twitter_access_token_secret')"
										:placeholder="$t('t_enter_twitter_access_token_secret')"
										:rules="errors.twitter_access_token_secret"
										:error="errors.twitter_access_token_secret ? true : false"
										></v-text-field>
								</v-flex>

								<v-flex xs12>
									<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
								</v-flex>

							</v-layout>

						</v-container>
					</v-tab-item>

					<!-- Telegram -->
					<v-tab-item value="telegram"
						>
						<v-container grid-list-xl fluid class="pa-4 mt-4">
							<v-layout wrap>

								<!-- Enable Telegram -->
								<v-flex xs12>
									<label class="form-group has-float-label" :class="[errors.enable_telegram ? 'has-danger' : '']">
										<v-switch v-model="form.enable_telegram" :true-value="1" :false-value="0" class="ma-0 form-group form-control" color="blue"></v-switch>
										<span>{{ $t('t_enable_telegram') }}</span>
									</label>
									<small class="red--text" v-if="errors.enable_telegram">{{ errors.enable_telegram[0] }}</small>
								</v-flex>

								<!-- Api token -->
								<v-flex xs12>
									<v-text-field
										v-model="form.telegram_api_token"
										color="grey lighten-1"
										:label="$t('t_telegram_api_token')"
										:placeholder="$t('t_enter_telegram_api_token')"
										:rules="errors.telegram_api_token"
										:error="errors.telegram_api_token ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Bot username -->
								<v-flex xs12>
									<v-text-field
										v-model="form.telegram_bot_username"
										color="grey lighten-1"
										:label="$t('t_telegram_bot')"
										:placeholder="$t('t_enter_telegram_bot')"
										:rules="errors.telegram_bot_username"
										:error="errors.telegram_bot_username ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Channel id -->
								<v-flex xs12>
									<v-text-field
										v-model="form.telegram_channel_username"
										color="grey lighten-1"
										:label="$t('t_telegram_channel')"
										:placeholder="$t('t_enter_telegram_channel')"
										:rules="errors.telegram_channel_username"
										:error="errors.telegram_channel_username ? true : false"
										></v-text-field>
								</v-flex>

								<!-- Channel signature -->
								<v-flex xs12>
									<v-textarea
										v-model="form.telegram_channel_signature"
										color="grey lighten-1"
										:label="$t('t_telegram_signature')"
										:placeholder="$t('t_enter_telegram_signature')"
										no-resize
										:rules="errors.telegram_channel_signature"
										:error="errors.telegram_channel_signature ? true : false"
										></v-textarea>
								</v-flex>

								<v-flex xs12>
									<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
								</v-flex>

							</v-layout>

						</v-container>
					</v-tab-item>

				</v-tabs>

			</v-card>
		</v-container>

	</v-app>
</template>

<script>
	export default {
        layout: 'default/manager',

        middleware: 'auth',

        async asyncData({ $axios }) {
            let response = await $axios.get('manager/settings/autoshare')
            return {
                form: {
					enable_facebook: response.data.isFacebook,
					facebook_app_id: response.data.facebook ? JSON.parse(response.data.facebook).app_id : null,
					facebook_app_secret: response.data.facebook ? JSON.parse(response.data.facebook).app_secret : null,
					facebook_page_access_token: response.data.facebook ? JSON.parse(response.data.facebook).page_access_token : null,

					enable_twitter: response.data.isTwitter,
					twitter_consumer_key: response.data.twitter ? JSON.parse(response.data.twitter).consumer_key : null,
					twitter_consumer_secret: response.data.twitter ? JSON.parse(response.data.twitter).consumer_secret : null,
					twitter_access_token: response.data.twitter ? JSON.parse(response.data.twitter).access_token : null,
					twitter_access_token_secret: response.data.twitter ? JSON.parse(response.data.twitter).access_token_secret : null,

					enable_telegram: response.data.isTelegram,
					telegram_api_token: response.data.telegram ? JSON.parse(response.data.telegram).api_token : null,
					telegram_bot_username: response.data.telegram ? JSON.parse(response.data.telegram).bot_username : null,
					telegram_channel_username: response.data.telegram ? JSON.parse(response.data.telegram).channel_username : null,
					telegram_channel_signature: response.data.telegram ? JSON.parse(response.data.telegram).channel_signature : null,
				},
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
				title: this.$t('t_autoshare_settings'),
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
				this.loading = true
				this.$axios
				.post('manager/settings/autoshare', {
					enable_facebook: this.form.enable_facebook,
					facebook_app_id: this.form.facebook_app_id,
					facebook_app_secret: this.form.facebook_app_secret,
					facebook_page_access_token: this.form.facebook_page_access_token,

					enable_twitter: this.form.enable_twitter,
					twitter_consumer_key: this.form.twitter_consumer_key,
					twitter_consumer_secret: this.form.twitter_consumer_secret,
					twitter_access_token: this.form.twitter_access_token,
					twitter_access_token_secret: this.form.twitter_access_token_secret,

					enable_telegram: this.form.enable_telegram,
					telegram_api_token: this.form.telegram_api_token,
					telegram_bot_username: this.form.telegram_bot_username,
					telegram_channel_username: this.form.telegram_channel_username,
					telegram_channel_signature: this.form.telegram_channel_signature
				})
				.then(response => {
					this.errors = [],
					this.$toasted.show(this.$t('t_toasted_settings_updated'), {
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
			}
		}
	}
</script>