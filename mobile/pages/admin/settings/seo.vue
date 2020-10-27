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
		            <div class="title">{{ $t('t_seo_settings') }}</div>
		            <span class="card-description">{{ $t('t_seo_settings_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Description -->
						<v-flex xs12>
							<v-textarea
								v-model="form.description"
								color="grey lighten-1"
								:label="$t('t_description')"
								:placeholder="$t('t_enter_seo_description')"
								no-resize
								:rules="errors.description"
								:error="errors.description ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Keywords -->
						<v-flex xs12>
							<v-text-field
								v-model="form.keywords"
								color="grey lighten-1"
								:label="$t('t_keywords')"
								:placeholder="$t('t_enter_keywords')"
								:rules="errors.keywords"
								:error="errors.keywords ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Title separator -->
						<v-flex xs12>
							<v-text-field
								v-model="form.separator"
								color="grey lighten-1"
								:label="$t('t_title_separator')"
								:placeholder="$t('t_enter_title_separator')"
								:rules="errors.separator"
								:error="errors.separator ? true : false"
								></v-text-field>
						</v-flex>

						<!-- dns prefetch -->
						<v-flex xs12>
							<v-textarea
								v-model="form.dnsPrefetch"
								color="grey lighten-1"
								:label="$t('t_dns_prefetch')"
								:placeholder="$t('t_enter_dns_prefetch')"
								no-resize
								:rules="errors.dnsPrefetch"
								:error="errors.dnsPrefetch ? true : false"
								></v-textarea>
						</v-flex>

						<!-- Google Analytics tracking ID -->
						<v-flex xs12>
							<v-text-field
								v-model="form.google_analytics"
								color="grey lighten-1"
								:label="$t('t_google_analytics')"
								:placeholder="$t('t_enter_google_analytics')"
								:rules="errors.google_analytics"
								:error="errors.google_analytics ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Enable Sitemap -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.sitemap ? 'has-danger' : '']">
								<v-switch v-model="form.sitemap" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_sitemap') }}</span>
							</label>
							<small class="red--text" v-if="errors.sitemap">{{ errors.sitemap[0] }}</small>
							<small v-if="form.sitemap" class="grey--text"><a :href="$home('sitemap.xml')" target="_blank" class="green--text">sitemap.xml</a></small>
							<small v-if="form.sitemap" class="grey--text"><a :href="$home('sitemap.xml.gz')" target="_blank" class="orange--text">sitemap.xml.gz</a></small>
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
			if (!app.$owgate.allow(app.$auth.user, 'seo', 'settings')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/settings/seo')
			return {
				form: {
					description     : response.data.description,
					keywords        : response.data.keywords,
					separator       : response.data.separator,
					dnsPrefetch     : response.data.dnsPrefetch,
					google_analytics: response.data.googleAnalyticsId,
					sitemap         : response.data.isSitemap,
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
				title: this.$t('t_seo_settings'),
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
				if (!this.$owgate.allow(this.$auth.user, 'seo', 'settings')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/settings/seo', {
					description: this.form.description,
					keywords: this.form.keywords,
					separator: this.form.separator,
					dnsPrefetch: this.form.dnsPrefetch,
					header_code: this.form.header_code,
					footer_code: this.form.footer_code,
					enable_cdn: this.form.enable_cdn,
					cdn_domain: this.form.cdn_domain,
					google_analytics: this.form.google_analytics,
					sitemap: this.form.sitemap
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_seo_settings_updated'), {
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