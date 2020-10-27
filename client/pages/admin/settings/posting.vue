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
		            <div class="title">{{ $t('t_posting_settings') }}</div>
		            <span class="card-description">{{ $t('t_posting_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Free account max deals per day -->
						<v-flex xs12>
							<v-text-field
								v-model="form.deals_per_day"
								color="grey lighten-1"
								:label="$t('t_plan_deals_limits')"
								:placeholder="$t('t_enter_plan_deals_limits')"
								:rules="errors.deals_per_day"
								:error="errors.deals_per_day ? true : false"
								type="number"
								></v-text-field>
						</v-flex>

						<!-- Free account deals expiry period -->
						<v-flex xs12>
							<v-text-field
								v-model="form.deals_expires_after"
								color="grey lighten-1"
								:label="$t('t_plan_deal_expiration_period')"
								:placeholder="$t('t_enter_plan_deal_expiration_period')"
								:rules="errors.deals_expires_after"
								:error="errors.deals_expires_after ? true : false"
								type="number"
								></v-text-field>
						</v-flex>

						<!-- Free account max deal images -->
						<v-flex xs12>
							<v-text-field
								v-model="form.deals_max_images"
								color="grey lighten-1"
								:label="$t('t_plan_deals_photos_limits')"
								:placeholder="$t('t_enter_plan_deals_photos_limits')"
								:rules="errors.deals_max_images"
								:error="errors.deals_max_images ? true : false"
								type="number"
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/posting')
			return {
				form: {
					deals_per_day       : response.data.deals_day,
					deals_expires_after : response.data.deals_life,
					deals_max_images    : response.data.deals_images
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
				title: this.$t('t_posting_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'access', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/posting', {
					deals_per_day: this.form.deals_per_day,
					deals_expires_after: this.form.deals_expires_after,
					deals_max_images: this.form.deals_max_images
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_posting_settings_updated'), {
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