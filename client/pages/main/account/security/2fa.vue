<template>
	<v-app id="inspire">
		
		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>

      				<!-- Account sidebar -->
          			<v-sidebar></v-sidebar>

          			<!-- Account page -->
					<v-flex xs9>
						<v-card class="m-card pb-5" flat>
							<v-card-title primary-title class="pa-4">
					          <div>
					            <div class="title pb-3">{{ $t('t_2fa_verification') }}</div>
					            <span class="card-description">{{ $t('t_2fa_verification_para') }}</span>
					          </div>
					        </v-card-title>

					        <v-container grid-list-xl fluid class="pa-4">
								<v-layout wrap>
									<v-row justify="center">
										<v-flex xs10 class="text-center">

											<!-- Generate Secret -->
											<div v-if="!account.password_security">
												<v-btn block depressed @click.prevent="generate()" color="grey darken-3" class="white--text">
													{{ $t('t_generate_2fa_secret') }}
												</v-btn>
											</div>

											<!-- enable 2FA -->
											<div v-else-if="!account.password_security.google2fa_enable">

												<!-- Download App -->
												<h1 class="headline font-weight-black text-uppercase my-5">{{ $t('t_download_2fa_app') }}</h1>

												<v-row>
													<v-col cols="6" md="4">
														<v-btn color="black" dark depressed block href="https://authy.com/download/" target="_blank">
															Authy
															<v-icon right>mdi-download</v-icon>
														</v-btn>
													</v-col>

													<v-col cols="6" md="4">
														<v-btn color="black" dark depressed block href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">
															Authenticator
															<v-icon right>mdi-google-play</v-icon>
														</v-btn>
													</v-col>

													<v-col cols="6" md="4">
														<v-btn color="black" dark depressed block href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">
															Authenticator
															<v-icon right>mdi-apple</v-icon>
														</v-btn>
													</v-col>
												</v-row>

												<!-- Scan QRCode -->
												<h1 class="headline font-weight-black text-uppercase my-5">{{ $t('t_scan_this_qrcode') }}</h1>

												<!-- QR Code IMG -->
												<img :src="google2fa_url" alt="">

												<!-- Step 3 Enter code -->
												<h1 class="headline font-weight-black text-uppercase my-5">{{ $t('t_enter_6_digit_generated') }}</h1>

												<!-- verification code -->
												<v-text-field
													v-model="form.verifyCode"
													color="grey lighten-1"
													:label="$t('t_verification_code')"
													:placeholder="$t('t_enter_verification_code')"
													:rules="errors.verifyCode"
													:error="errors.verifyCode ? true : false"
													></v-text-field>

												<!-- Enable -->
												<v-btn block depressed @click.prevent="enable()" color="grey darken-3" dark class="mt-4">
													{{ $t('t_enable_2fa') }}
												</v-btn>

											</div>

											<!-- Disable 2FA -->
											<div v-else>

												<!-- status -->
												<h1 class="headline font-weight-black text-uppercase my-5" v-html="$t('t_2fa_currently_enabled')"></h1>
												<p class="title pb-4">{{ $t('t_2fa_disable_para') }}</p>

												<!-- Password -->
												<v-text-field
													v-model="form.password"
													color="grey lighten-1"
													:label="$t('t_password')"
													:placeholder="$t('t_enter_password')"
													:rules="errors.password"
													:error="errors.password ? true : false"
													type="password"
													></v-text-field>

												<!-- Disable -->
												<v-btn block depressed @click.prevent="disable()" color="grey darken-3" class="white--text">
													{{ $t('t_disable_2fa') }}
												</v-btn>

											</div>
										</v-flex>
									</v-row>
								</v-layout>
							</v-container>

						</v-card>
					</v-flex>

				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	import sidebar from '~/pages/main/account/layout/account'

	export default {
		middleware: 'auth',

		layout: 'default/main',

		components: {
			'v-sidebar': sidebar
		},

		async asyncData ({ $axios }) {
			let response = await $axios.post('account/2fa')
			return {
				account: response.data.user,
				google2fa_url: response.data.google2fa_url,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon
				}
			}
		},

		data() {
			return {
				form: {
					verifyCode: '',
					password: ''
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title : this.$t('t_2fa_verification'),
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		methods: {
			generate: function() {
				this.loading = true
				this.$axios
				.post('account/2fa/generate')
				.then(response => {
					this.account.password_security = response.data.password_security
					this.google2fa_url             = response.data.google2fa_url
					this.$toasted.show(this.$t('t_toasted_2fa_secret_generated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading                = false
				})
				.catch(error => {
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
			},

			enable: function() {
				if (this.form.verifyCode !== '') {
					this.loading = true
					this.$axios
					.post('account/2fa/enable', {
						verifyCode: this.form.verifyCode
					})
					.then(response => {
						if (response.data.isFailed) {
							this.$toasted.error(this.$t('t_toasted_2fa_code_wrong'), {
								icon: 'error',
								className: this.$vuetify.rtl ? 'toasted-rtl' : ''
							})
							this.loading = false
						}else{
							this.account.password_security = response.data
							this.$toasted.show(this.$t('t_toasted_2fa_enabled'), {
								icon: 'done_all',
								className: this.$vuetify.rtl ? 'toasted-rtl' : ''
							})
							this.loading                = false
						}
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
			},

			disable: function() {
				if (this.form.password !== '') {
					this.loading = true
					this.$axios
					.post('account/2fa/disable', {
						password: this.form.password
					})
					.then(response => {
						if (response.data.isFailed) {
							this.$toasted.error(this.$t('t_toasted_wrong_password'), {
								icon: 'error',
								className: this.$vuetify.rtl ? 'toasted-rtl' : ''
							})
							this.loading = false
						}else{
							this.form.verifyCode           = null
							this.account.password_security = response.data
							this.$toasted.show(this.$t('t_toasted_2fa_disabled'), {
								icon: 'done_all',
								className: this.$vuetify.rtl ? 'toasted-rtl' : ''
							})
							this.loading = false
						}
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
	}
</script>