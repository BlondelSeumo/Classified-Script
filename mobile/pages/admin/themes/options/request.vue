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
		            <div class="title">{{ $t('t_request_theme') }}</div>
		            <span class="card-description">{{ $t('t_request_theme_para1') }}<br> <small class="red--text">{{ $t('t_request_theme_para2') }}</small></span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_name')"
								:placeholder="$t('t_enter_name')"
								counter="100"
								maxlength="100"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- E-mail address -->
						<v-flex xs12>
							<v-text-field
								v-model="form.email"
								color="grey lighten-1"
								:label="$t('t_email')"
								:placeholder="$t('t_enter_email')"
								:rules="errors.email"
								:error="errors.email ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Example link -->
						<v-flex xs12>
							<v-text-field
								v-model="form.example"
								color="grey lighten-1"
								:label="$t('t_example_url')"
								:placeholder="$t('t_enter_example_url')"
								:rules="errors.example"
								:error="errors.example ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Budget -->
						<v-flex xs12>
							<v-text-field
								append-icon="mdi-currency-usd"
								v-model="form.budget"
								color="grey lighten-1"
								:label="$t('t_budget')"
								:placeholder="$t('t_enter_budget')"
								:rules="errors.budget"
								:error="errors.budget ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Additional details -->
						<v-flex xs12>
							<v-textarea
								v-model="form.details"
								color="grey lighten-1"
								:label="$t('t_additional_details')"
								:placeholder="$t('t_enter_additional_details')"
								no-resize
								:rules="errors.details"
								:error="errors.details ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Make this theme unique (No one will have the same theme) -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.unique ? 'has-danger' : '']">
								<v-switch v-model="form.unique" :false-value="0" :true-value="1" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_private_theme') }}</span>
							</label>
							<small class="error--text" v-if="errors.unique">{{ errors.unique[0] }}</small>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="request" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_request') }}</v-btn>
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

		asyncData({ app, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'request', 'themes')) {
				redirect('/administrator')
			}
		},

		data: function() {
			return {
				form: {
					name: '',
					email: '',
					example: '',
					budget: '',
					details: '',
					unique: false,
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_request_theme'),
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
			request: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'request', 'themes')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/themes/options/request',{
					name: this.form.name,
					email: this.form.email,
					example: this.form.example,
					budget: this.form.budget,
					details: this.form.details,
					unique: this.form.unique
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						email: '',
						example: '',
						budget: '',
						details: '',
						unique: false,
					}
					this.$toasted.show(this.$t('t_toasted_theme_request_sent'), {
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