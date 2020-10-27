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
		            <div class="title">{{ $t('t_auth_settings') }}</div>
		            <span class="card-description">{{ $t('t_auth_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Enable Registration -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable_registration ? 'has-danger' : '']">
								<v-switch v-model="form.enable_registration" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_register') }}</span>
							</label>
							<small class="error-text" v-if="errors.enable_registration">{{ errors.enable_registration[0] }}</small>
						</v-flex>

						<!-- Default Role -->
						<v-flex xs12>
							<v-select
								v-model="form.default_role"
								:items="roles"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_role')"
								:placeholder="$t('t_choose_default_role')"
								:rules="errors.default_role"
								:error="errors.default_role ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Default subscription plan -->
						<v-flex xs12>
							<v-select
								v-model="form.default_plan"
								:items="plans"
						        item-text="title"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_subscription_plan')"
								:placeholder="$t('t_choose_default_subscription_plan')"
								:rules="errors.default_plan"
								:error="errors.default_plan ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Activation Required -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.need_activation ? 'has-danger' : '']">
								<v-switch v-model="form.need_activation" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_activation_required') }}</span>
								<small class="form-text" v-if="errors.need_activation">{{ errors.need_activation[0] }}</small>
							</label>
						</v-flex>

						<!-- Activation type -->
						<v-flex xs12>
							<v-select
								v-model="form.activation_type"
								:items="types"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_activation_type')"
								:placeholder="$t('t_choose_activation_type')"
								:rules="errors.activation_type"
								:error="errors.activation_type ? true : false"
								:disabled="!form.need_activation"
								dense
								></v-select>
						</v-flex>

						<!-- Default login type -->
						<v-flex xs12>
							<v-select
								v-model="form.login_by"
								:items="login"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_default_login_credentials')"
								:placeholder="$t('t_choose_default_login_credentials')"
								:rules="errors.login_by"
								:error="errors.login_by ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Activation code life -->
						<v-flex xs12>
							<v-text-field
								v-model="form.activation_code_life"
								color="grey lighten-1"
								:label="$t('t_activation_code_life')"
								:placeholder="$t('t_enter_activation_code_life')"
								:rules="errors.activation_code_life"
								:error="errors.activation_code_life ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Lock login after how X failed attempts -->
						<v-flex xs12>
							<v-text-field
								v-model="form.max_attempts"
								color="grey lighten-1"
								:label="$t('t_max_login_attempts')"
								:placeholder="$t('t_enter_max_login_attempts')"
								:rules="errors.max_attempts"
								:error="errors.max_attempts ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Retry after -->
						<v-flex xs12>
							<v-text-field
								v-model="form.retry_after"
								color="grey lighten-1"
								:label="$t('t_retry_login_after')"
								:placeholder="$t('t_enter_retry_login_after')"
								:rules="errors.retry_after"
								:error="errors.retry_after ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'auth', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/auth')
			return {
				form: {
					enable_registration  : response.data.settings.isRegister,
					need_activation      : response.data.settings.needActivation,
					activation_type      : response.data.settings.activationType,
					login_by             : response.data.settings.loginBy,
					default_sms_service  : response.data.settings.default_sms_service,
					activation_code_life : response.data.settings.activation_expires_after,
					max_attempts         : response.data.settings.maxAttempts,
					retry_after          : response.data.settings.retries_in,
					default_role         : response.data.settings.default_role_id,	
					default_plan         : response.data.settings.default_plan_id
				},
				roles: response.data.roles,
				plans: response.data.plans
			}
		},

		data: function() {
			return {
				types: [
		          	{ id: 'email', name: this.$t('t_email') },
		          	{ id: 'manually', name: this.$t('t_manually') }
		        ],
				login: [
		          	{ id: 'username', name: this.$t('t_username') },
		          	{ id: 'email', name: this.$t('t_email') },
		          	{ id: 'phone', name: this.$t('t_phone') },
		          	{ id: 'ue', name: this.$t('t_username_or_email') },
		          	{ id: 'uep', name: this.$t('t_username_or_email_or_phone') }
		        ],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_auth_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'auth', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/auth', {
					enable_registration: this.form.enable_registration,
					need_activation: this.form.need_activation,
					activation_type: this.form.activation_type,
					login_by: this.form.login_by,
					activation_code_life: this.form.activation_code_life,
					max_attempts: this.form.max_attempts,
					retry_after: this.form.retry_after,
					default_role: this.form.default_role,
					default_plan: this.form.default_plan
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_auth_settings_updated'), {
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
			}
		}
	}
</script>