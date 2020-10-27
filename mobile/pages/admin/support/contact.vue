<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Extra high? -->
		<v-dialog v-model="confirm" max-width="290" persistent>
			<v-card>
				<v-card-title class="font-weight-bold text-uppercase">{{ $t('t_support_confirm_extra') }}</v-card-title>
				<v-card-text>
					{{ $t('t_support_confirm_extra_para') }}
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="red" text @click="confirm = false">
						{{ $t('t_confirm') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<v-card class="m-card" flat>
				<v-card-title primary-title class="pa-4">
		          	<div>
			            <div class="title">{{ $t('t_contact_mendelmangroup') }}</div>
			            <span class="card-description" v-html="$t('t_contact_mendelmangroup_para')"></span>
			        </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Your name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_name')"
								:placeholder="$t('t_enter_name')"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Your e-mail address -->
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

						<!-- Priority -->
						<v-flex xs12>
							<v-select
								@change="extraHigh()"
								v-model="form.priority"
								:items="priorities"
						        item-text="name"
						        item-value="id"
								color="grey lighten-1"
								:label="$t('t_priority')"
								:placeholder="$t('t_choose_priority')"
								:rules="errors.priority"
								:error="errors.priority ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Message -->
						<v-flex xs12>
							<v-textarea
								v-model="form.message"
								color="grey lighten-1"
								:label="$t('t_message')"
								:placeholder="$t('t_support_enter_message')"
								no-resize
								:rules="errors.message"
								:error="errors.message ? true : false"
								></v-textarea>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="contact" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_send') }}</v-btn>
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'support')) {
				redirect('/administrator')
			}
		},

		data: function() {
			return {
				form: {
					name: '',
					email: '',
					message: '',
					priority: 'low'
				},
				priorities: [
		          	{ id: 'low', name: this.$t('t_low_priority') },
		          	{ id: 'medium', name: this.$t('t_medium_priority') },
		          	{ id: 'high', name: this.$t('t_high_priority') },
		          	{ id: 'extra', name: this.$t('t_extra_priority') }
		        ],
				errors: [],
				loading: false,
				confirm: false
			}
		},

		head() {
			return {
				title: this.$t('t_contact_support'),
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
			contact: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'access', 'support')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/support/contact', {
					name: this.form.name,
					email: this.form.email,
					message: this.form.message,
					priority: this.form.priority
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						name: '',
						email: '',
						message: '',
						priority: 'low'
					}
					this.$toasted.show(this.$t('t_toasted_support_message_sent'), {
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
			},

			extraHigh: function() {
				if (this.form.priority === 'extra') {
					this.confirm = true
				}
			}
		}
	}
</script>