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
		            <div class="title">{{ $t('t_footer_settings') }}</div>
		            <span class="card-description">{{ $t('t_footer_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Footer copyrights -->
						<v-flex xs12>
							<v-text-field
								v-model="form.copyrights"
								color="grey lighten-1"
								:label="$t('t_footer_copyrights')"
								:placeholder="$t('t_enter_footer_copyrights')"
								:rules="errors.copyrights"
								:error="errors.copyrights ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Terms of use page -->
						<v-flex xs12>
							<v-select
								v-model="form.terms"
								:items="pages"
						        item-text="title"
						        item-value="slug"
								color="grey lighten-1"
								:label="$t('t_terms_of_use_page')"
								:placeholder="$t('t_choose_terms_of_use_page')"
								:rules="errors.terms"
								:error="errors.terms ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Privacy policy page -->
						<v-flex xs12>
							<v-select
								v-model="form.privacy"
								:items="pages"
						        item-text="title"
						        item-value="slug"
								color="grey lighten-1"
								:label="$t('t_privacy_policy_page')"
								:placeholder="$t('t_choose_privacy_policy_page')"
								:rules="errors.privacy"
								:error="errors.privacy ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- First footer column -->
						<v-flex xs12>
							<v-text-field
								v-model="form.firstColumn"
								color="grey lighten-1"
								:label="$t('t_first_footer_column')"
								:placeholder="$t('t_enter_first_footer_column')"
								:rules="errors.firstColumn"
								:error="errors.firstColumn ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Second footer column -->
						<v-flex xs12>
							<v-text-field
								v-model="form.secondColumn"
								color="grey lighten-1"
								:label="$t('t_second_footer_column')"
								:placeholder="$t('t_enter_second_footer_column')"
								:rules="errors.secondColumn"
								:error="errors.secondColumn ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Third footer column -->
						<v-flex xs12>
							<v-text-field
								v-model="form.thirdColumn"
								color="grey lighten-1"
								:label="$t('t_third_footer_column')"
								:placeholder="$t('t_enter_third_footer_column')"
								:rules="errors.thirdColumn"
								:error="errors.thirdColumn ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Fourth footer column -->
						<v-flex xs12>
							<v-text-field
								v-model="form.fourthColumn"
								color="grey lighten-1"
								:label="$t('t_fourth_footer_column')"
								:placeholder="$t('t_enter_fourth_footer_column')"
								:rules="errors.fourthColumn"
								:error="errors.fourthColumn ? true : false"
								></v-text-field>
						</v-flex>

						<!-- MailChimp API Key -->
						<v-flex xs12>
							<v-text-field
								v-model="form.mailchimp_key"
								color="grey lighten-1"
								:label="$t('t_mailchimp_api')"
								:placeholder="$t('t_enter_mailchimp_api')"
								:rules="errors.mailchimp_key"
								:error="errors.mailchimp_key ? true : false"
								></v-text-field>
						</v-flex>

						<!-- MailChimp List Id -->
						<v-flex xs12>
							<v-text-field
								v-model="form.mailchimp_id"
								color="grey lighten-1"
								:label="$t('t_mailchimp_list_id')"
								:placeholder="$t('t_enter_mailchimp_list_id')"
								:rules="errors.mailchimp_id"
								:error="errors.mailchimp_id ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Facebook -->
						<v-flex xs12>
							<v-text-field
								v-model="form.facebook"
								color="grey lighten-1"
								:label="$t('t_facebook')"
								:placeholder="$t('t_your_facebook_page')"
								:rules="errors.facebook"
								:error="errors.facebook ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Twitter -->
						<v-flex xs12>
							<v-text-field
								v-model="form.twitter"
								color="grey lighten-1"
								:label="$t('t_twitter')"
								:placeholder="$t('t_your_twitter_page')"
								:rules="errors.twitter"
								:error="errors.twitter ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Google -->
						<v-flex xs12>
							<v-text-field
								v-model="form.google"
								color="grey lighten-1"
								:label="$t('t_google')"
								:placeholder="$t('t_your_google_page')"
								:rules="errors.google"
								:error="errors.google ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Instagram -->
						<v-flex xs12>
							<v-text-field
								v-model="form.instagram"
								color="grey lighten-1"
								:label="$t('t_instagram')"
								:placeholder="$t('t_your_instagram_page')"
								:rules="errors.instagram"
								:error="errors.instagram ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Linkedin -->
						<v-flex xs12>
							<v-text-field
								v-model="form.linkedin"
								color="grey lighten-1"
								:label="$t('t_linkedin')"
								:placeholder="$t('t_your_linkedin_page')"
								:rules="errors.linkedin"
								:error="errors.linkedin ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Tumblr -->
						<v-flex xs12>
							<v-text-field
								v-model="form.tumblr"
								color="grey lighten-1"
								:label="$t('t_tumblr')"
								:placeholder="$t('t_your_tumblr_page')"
								:rules="errors.tumblr"
								:error="errors.tumblr ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Vk -->
						<v-flex xs12>
							<v-text-field
								v-model="form.vk"
								color="grey lighten-1"
								:label="$t('t_vk')"
								:placeholder="$t('t_your_vk_page')"
								:rules="errors.vk"
								:error="errors.vk ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Youtube -->
						<v-flex xs12>
							<v-text-field
								v-model="form.youtube"
								color="grey lighten-1"
								:label="$t('t_youtube')"
								:placeholder="$t('t_your_youtube_channel')"
								:rules="errors.youtube"
								:error="errors.youtube ? true : false"
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
			if (!app.$owgate.allow(app.$auth.user, 'footer', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/footer')
			return {
				form: {
					copyrights    : response.data.config.copyrights,
					terms         : response.data.config.terms,
					privacy       : response.data.config.privacy,
					firstColumn   : response.data.config.rows.first,
					secondColumn  : response.data.config.rows.second,
					thirdColumn   : response.data.config.rows.third,
					fourthColumn  : response.data.config.rows.fourth,
					mailchimp_key : response.data.newsletter.apiKey,
					mailchimp_id  : response.data.newsletter.lists.subscribers.id,
					facebook      : response.data.config.links.facebook,
					twitter       : response.data.config.links.twitter,
					google        : response.data.config.links.google,
					instagram     : response.data.config.links.instagram,
					linkedin      : response.data.config.links.linkedin,
					vk            : response.data.config.links.vk,
					tumblr        : response.data.config.links.tumblr,
					youtube       : response.data.config.links.youtube
				},
				pages: response.data.pages
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
				title: this.$t('t_footer_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'footer', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/footer', {
					copyrights: this.form.copyrights,
					terms: this.form.terms,
					privacy: this.form.privacy,
					firstColumn: this.form.firstColumn,
					secondColumn: this.form.secondColumn,
					thirdColumn: this.form.thirdColumn,
					fourthColumn: this.form.fourthColumn,
					mailchimp_key: this.form.mailchimp_key,
					mailchimp_id: this.form.mailchimp_id,
					facebook: this.form.facebook,
					twitter: this.form.twitter,
					google: this.form.google,
					instagram: this.form.instagram,
					linkedin: this.form.linkedin,
					vk: this.form.vk,
					tumblr: this.form.tumblr,
					youtube: this.form.youtube
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_footer_settings_updated'), {
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