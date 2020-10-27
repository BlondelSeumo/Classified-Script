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
		            <div class="title">{{ $t('t_advertisements') }}</div>
		            <span class="card-description">{{ $t('t_adsense_paragraph') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Deal sidebar ad -->
						<v-flex xs12>
							<v-textarea
								v-model="form.ad_deal_sidebar"
								color="grey lighten-1"
								:label="$t('t_deal_sidebar_ad')"
								:placeholder="this.$t('t_put_ad_code')"
								no-resize
								:rules="errors.ad_deal_sidebar"
								:error="errors.ad_deal_sidebar ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Related deals top ad -->
						<v-flex xs12>
							<v-textarea
								v-model="form.ad_deal_top_related"
								color="grey lighten-1"
								:label="$t('t_related_deals_top_ad')"
								:placeholder="this.$t('t_put_ad_code')"
								no-resize
								:rules="errors.ad_deal_top_related"
								:error="errors.ad_deal_top_related ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Related deals bottom ad -->
						<v-flex xs12>
							<v-textarea
								v-model="form.ad_deal_bottom_related"
								color="grey lighten-1"
								:label="$t('t_related_deals_bottom_ad')"
								:placeholder="this.$t('t_put_ad_code')"
								no-resize
								:rules="errors.ad_deal_bottom_related"
								:error="errors.ad_deal_bottom_related ? true : false"
								></v-textarea>
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'advertisements')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/advertisements')
			return {
				form: {
					ad_deal_sidebar        : response.data.ad_deal_sidebar,
					ad_deal_top_related    : response.data.ad_deal_top_related,
					ad_deal_bottom_related : response.data.ad_deal_bottom_related
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
				title: this.$t('t_advertisements'),
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
				if (!this.$owgate.allow(this.$auth.user, 'access', 'advertisements')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/advertisements', {
					ad_deal_sidebar: this.form.ad_deal_sidebar,
					ad_deal_top_related: this.form.ad_deal_top_related,
					ad_deal_bottom_related: this.form.ad_deal_bottom_related
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_advertisements_updated'), {
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