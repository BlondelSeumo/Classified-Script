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
		            <div class="title">{{ $t('t_smtp_settings') }}</div>
		            <span class="card-description">{{ $t('t_smtp_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Driver -->
						<v-flex xs12>
							<v-select
								v-model="form.driver"
								:items="drivers"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_driver')"
								:placeholder="$t('t_driver_para')"
								:rules="errors.driver"
								:error="errors.driver ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- SMTP host server -->
						<v-flex xs12>
							<v-text-field
								v-model="form.host"
								color="grey lighten-1"
								:label="$t('t_smtp_host')"
								:placeholder="$t('t_enter_smtp_host')"
								:rules="errors.host"
								:error="errors.host ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Port -->
						<v-flex xs12>
							<v-text-field
								v-model="form.port"
								color="grey lighten-1"
								:label="$t('t_port')"
								:placeholder="$t('t_enter_port')"
								:rules="errors.port"
								:error="errors.port ? true : false"
								></v-text-field>
						</v-flex>

						<!-- E-Mail Encryption Protocol -->
						<v-flex xs12>
							<v-select
								v-model="form.encryption"
								:items="encryptions"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_encryption_protocol')"
								:placeholder="$t('t_choose_encryption_protocol')"
								:rules="errors.encryption"
								:error="errors.encryption ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- SMTP Server Username -->
						<v-flex xs12>
							<v-text-field
								v-model="form.username"
								color="grey lighten-1"
								:label="$t('t_smtp_username')"
								:placeholder="$t('t_enter_smtp_username')"
								:rules="errors.username"
								:error="errors.username ? true : false"
								></v-text-field>
						</v-flex>

						<!-- SMTP Server Password -->
						<v-flex xs12>
							<v-text-field
								v-model="form.password"
								color="grey lighten-1"
								:label="$t('t_smtp_password')"
								:placeholder="$t('t_enter_smtp_password')"
								:rules="errors.password"
								:error="errors.password ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Sender E-mail Address -->
						<v-flex xs6>
							<v-text-field
								v-model="form.email"
								color="grey lighten-1"
								:label="$t('t_smtp_email')"
								:placeholder="$t('t_enter_smtp_email')"
								:rules="errors.email"
								:error="errors.email ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Sender Name -->
						<v-flex xs6>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_smtp_name')"
								:placeholder="$t('t_enter_smtp_name')"
								:rules="errors.name"
								:error="errors.name ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'smtp', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/smtp')
			return {
				form: {
					driver    : response.data.driver,
					host      : response.data.host,
					port      : response.data.port,
					encryption: response.data.encryption,
					username  : response.data.username,
					email     : response.data.email,
					name      : response.data.name
				}
			}
		},

		data: function() {
			return {
				drivers: [
		          	{ id: 'smtp', name: this.$t('t_smtp') },
		          	{ id: 'sendmail', name: this.$t('t_sendmail') },
		          	{ id: 'log', name: this.$t('t_log') }
		        ],
				encryptions: [
		          	{ id: 'tls', name: this.$t('t_tls') },
		          	{ id: 'ssl', name: this.$t('t_ssl') }
		        ],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_smtp_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'smtp', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/smtp', {
					driver: this.form.driver,
					host: this.form.host,
					port: this.form.port,
					encryption: this.form.encryption,
					username: this.form.username,
					password: this.form.password,
					email: this.form.email,
					name: this.form.name
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_smtp_settings_updated'), {
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