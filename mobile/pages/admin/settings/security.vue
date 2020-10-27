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
		            <div class="title">{{ $t('t_security_settings') }}</div>
		            <span class="card-description">{{ $t('t_security_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Blacklist username -->
						<v-flex xs12>
							<v-textarea
								v-model="form.blacklist_usernames"
								color="grey lighten-1"
								:label="$t('t_blacklist_username')"
								:placeholder="$t('t_enter_blacklist_username')"
								no-resize
								:rules="errors.blacklist_usernames"
								:error="errors.blacklist_usernames ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Blacklist words -->
						<v-flex xs12>
							<v-textarea
								v-model="form.blacklist_words"
								color="grey lighten-1"
								:label="$t('t_blacklist_words')"
								:placeholder="$t('t_enter_blacklist_words')"
								no-resize
								:rules="errors.blacklist_words"
								:error="errors.blacklist_words ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Blacklist emails -->
						<v-flex xs12>
							<v-textarea
								v-model="form.blacklist_emails"
								color="grey lighten-1"
								:label="$t('t_blacklist_emails')"
								:placeholder="$t('t_enter_blacklist_emails')"
								no-resize
								:rules="errors.blacklist_emails"
								:error="errors.blacklist_emails ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Auto approve deals -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.auto_approve_classifieds ? 'has-danger' : '']">
								<v-switch v-model="form.auto_approve_classifieds" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_auto_approve_deals') }}</span>
								<small class="form-text" v-if="errors.auto_approve_classifieds">{{ errors.auto_approve_classifieds[0] }}</small>
							</label>
						</v-flex>

						<!-- Auto approve comments -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.auto_approve_comments ? 'has-danger' : '']">
								<v-switch v-model="form.auto_approve_comments" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_auto_approve_comments') }}</span>
								<small class="form-text" v-if="errors.auto_approve_comments">{{ errors.auto_approve_comments[0] }}</small>
							</label>
						</v-flex>

						<!-- Auto approve shops -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.auto_approve_stores ? 'has-danger' : '']">
								<v-switch v-model="form.auto_approve_stores" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_auto_approve_shops') }}</span>
								<small class="form-text" v-if="errors.auto_approve_stores">{{ errors.auto_approve_stores[0] }}</small>
							</label>
						</v-flex>

						<!-- Enable Spam protection -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.spam ? 'has-danger' : '']">
								<v-switch v-model="form.spam" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_spam_protection') }}</span>
								<small class="form-text" v-if="errors.spam">{{ errors.spam[0] }}</small>
							</label>
						</v-flex>

						<!-- Enable Invisible reCaptcha -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_recaptcha ? 'has-danger' : '']">
								<v-switch v-model="form.enable_recaptcha" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_recaptcha') }}</span>
								<small class="form-text" v-if="errors.enable_recaptcha">{{ errors.enable_recaptcha[0] }}</small>
							</label>
						</v-flex>

						<!-- reCaptcha key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.recaptcha_key"
								color="grey lighten-1"
								:label="$t('t_recaptcha_key')"
								:placeholder="$t('t_enter_recaptcha_key')"
								:rules="errors.recaptcha_key"
								:error="errors.recaptcha_key ? true : false"
								></v-text-field>
						</v-flex>

						<!-- reCaptcha secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.recaptcha_secret"
								color="grey lighten-1"
								:label="$t('t_recaptcha_secret')"
								:placeholder="$t('t_enter_recaptcha_secret')"
								:rules="errors.recaptcha_secret"
								:error="errors.recaptcha_secret ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'security', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/security')
			return {
				form: {
					blacklist_usernames     : response.data.settings.blockedUsername,
					blacklist_emails        : response.data.settings.blockedEmails,
					blacklist_words         : response.data.settings.blockedWords,
					auto_approve_classifieds: response.data.settings.autoApproveClassifieds,
					auto_approve_comments   : response.data.settings.autoApproveComments,
					auto_approve_stores     : response.data.settings.autoApproveStores,
					enable_recaptcha        : response.data.settings.isRecaptcha,
					recaptcha_key           : response.data.settings.siteKey,
					recaptcha_secret        : response.data.settings.secretKey,
					spam                    : response.data.settings.spamFilter,
					recaptcha_key           : response.data.captcha.key,
					recaptcha_secret        : response.data.captcha.secret
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
				title: this.$t('t_security_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'security', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/security', {
					blacklist_usernames: this.form.blacklist_usernames,
					blacklist_emails: this.form.blacklist_emails,
					blacklist_words: this.form.blacklist_words,
					auto_approve_classifieds: this.form.auto_approve_classifieds,
					auto_approve_comments: this.form.auto_approve_comments,
					auto_approve_stores: this.form.auto_approve_stores,
					enable_recaptcha: this.form.enable_recaptcha,
					recaptcha_key: this.form.recaptcha_key,
					recaptcha_secret: this.form.recaptcha_secret,
					spam: this.form.spam
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_security_settings_updated'), {
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