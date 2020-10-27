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
		            <div class="title">{{ $t('t_nexmo') }}</div>
		            <span class="card-description">{{ $t('t_nexmo_gateway_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.key"
								color="grey lighten-1"
								:label="$t('t_nexmo_key')"
								:placeholder="$t('t_enter_nexmo_key')"
								:rules="errors.key"
								:error="errors.key ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Secret -->
						<v-flex xs12>
							<v-text-field
								v-model="form.secret"
								color="grey lighten-1"
								:label="$t('t_nexmo_secret')"
								:placeholder="$t('t_enter_nexmo_secret')"
								:rules="errors.secret"
								:error="errors.secret ? true : false"
								></v-text-field>
						</v-flex>

						<!-- SMS from -->
						<v-flex xs12>
							<v-text-field
								v-model="form.from"
								color="grey lighten-1"
								:label="$t('t_nexmo_sms_from')"
								:placeholder="$t('t_enter_nexmo_sms_from')"
								:rules="errors.from"
								:error="errors.from ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'sms', 'services')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/services/sms/nexmo')
			return {
				form: {
					key    : response.data.key,
					secret : response.data.secret,
					from   : response.data.from
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
				title: this.$t('t_nexmo_gateway'),
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
				if (!this.$owgate.allow(this.$auth.user, 'sms', 'services')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/services/sms/nexmo',{
					key: this.form.key,
					secret: this.form.secret,
					from: this.form.from
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_nexmo_updated'), {
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