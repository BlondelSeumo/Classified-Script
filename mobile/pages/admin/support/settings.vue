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
		            <div class="title">{{ $t('t_support_settings') }}</div>
		            <span class="card-description">{{ $t('t_support_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Your purchase code -->
						<v-flex xs12>
							<v-text-field
								v-model="form.code"
								color="grey lighten-1"
								:label="$t('t_purchase_code')"
								:placeholder="$t('t_enter_purchase_code')"
								:rules="errors.code"
								:error="errors.code ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Envato username -->
						<v-flex xs12>
							<v-text-field
								v-model="form.username"
								color="grey lighten-1"
								:label="$t('t_envato_username')"
								:placeholder="$t('t_enter_envato_username')"
								:rules="errors.username"
								:error="errors.username ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'support')) {
				redirect('/administrator')
			}
			
			let response = await $axios.get('administrator/support/settings')
			return {
				form: {
					code    : response.data.purchaseCode,
					username: response.data.envatoUsername
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
				title: this.$t('t_support_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'access', 'support')) {
					this.$router.push('/administrator')	
					return
				}
				
				this.loading = true
				this.$axios
				.post('administrator/support/settings', {
					code: this.form.code,
					username: this.form.username
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_support_settings_updated'), {
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