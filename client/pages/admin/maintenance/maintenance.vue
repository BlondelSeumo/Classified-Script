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
		            <div class="title">{{ $t('t_maintenance_mode') }}</div>
		            <span class="card-description">{{ $t('t_maintenance_mode_para') }}</span>
		            <div class="pt-3" style="width:100%" v-if="form.token !== null">
		            	<span class="card-description error--text">{{ $t('t_maintenance_mode_warning') }}</span><br>
		            	<a style="display:block" class="pt-2 card-description" :href="$home('maintenance/disable?token=' + form.token)" target="_blank">{{ $home('maintenance/disable?token=') + form.token }}</a>
		            </div>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Enable Maintenance Mode -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.enable ? 'has-danger' : '']">
								<v-switch v-model="form.enable" @change="generate" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_maintenance') }}</span>
								<small class="form-text" v-if="errors.enable">{{ errors.enable[0] }}</small>
							</label>
						</v-flex>

						<!-- Disable Token -->
						<v-flex xs12>
							<v-text-field
								v-model="form.token"
								disabled
								color="grey lighten-1"
								:label="$t('t_maintenance_disable_token')"
								:placeholder="$t('t_enter_maintenance_disable_token')"
								:rules="errors.token"
								:error="errors.token ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'maintenance')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/maintenance')
			return {
				form: {
					enable: response.data.enabled,
					token: response.data.enabled ? response.data.token : null
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
				title: this.$t('t_maintenance_mode'),
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
				if (!this.$owgate.allow(this.$auth.user, 'access', 'maintenance')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				if (!this.form.enable) {
					this.form.token = null
				}

				this.$axios
				.post('administrator/maintenance', {
					enable: this.form.enable,
					token: this.form.token
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_maintenance_updated'), {
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
			},

			generate: function() {
				if (this.form.enable) {
					this.loading = true
					this.$axios
					.post('administrator/maintenance/generate', {
						isEnabled: this.form.enable
					})
					.then(response => {
						this.form.token = response.data
						this.loading      = false
					})
					.catch(error => {
						this.loading = false
					})
				}else{
					this.loading = false
				}
			}
		}
	}
</script>