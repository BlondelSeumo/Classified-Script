<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-row justify="center">
      				<v-flex xs6>
      					<v-card class="m-card" flat>
      						<v-card-title primary-title class="pa-0 justify-center">
					          	<h1 class="display-1 font-weight-black text-uppercase pt-12">{{ $t('t_contact_us') }}</h1>
					        </v-card-title>
							<v-container grid-list-xl fluid class="pa-7 mt-4">
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
											type="email"
											></v-text-field>
									</v-flex>

									<!-- Subject -->
									<v-flex xs12>
										<v-text-field
											v-model="form.subject"
											color="grey lighten-1"
											:label="$t('t_subject')"
											:placeholder="$t('t_enter_subject')"
											counter="100"
											maxlength="100"
											:rules="errors.subject"
											:error="errors.subject ? true : false"
											></v-text-field>
									</v-flex>

									<!-- Message -->
									<v-flex xs12>
										<v-textarea
											v-model="form.message"
											color="grey lighten-1"
											:label="$t('t_message')"
											:placeholder="$t('t_enter_message')"
											counter="700"
											maxlength="700"
											:rules="errors.message"
											:error="errors.message ? true : false"
											>
										</v-textarea>
									</v-flex>

									<v-flex xs12>
										<v-btn @click.prevent="send()" depressed :loading="loading" :disabled="loading" block color="light-blue lighten-1" dark class="mt-0">{{ $t('t_send') }}</v-btn>
									</v-flex>
								</v-layout>
							</v-container>
      					</v-card>
      				</v-flex>
      			</v-row>
      		</v-container>
      	</v-content>

    </v-app>
</template>

<script>
	export default {
		layout: 'default/main',

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.get('contact')
			return {
				seo: {
					title: response.data.title,
	  				separator: response.data.separator,
	  				description: response.data.description,
	  				favicon: response.data.favicon,
	  				image: response.data.image
				}
			}
		},

		head () {
			return {
				title: this.$t('t_contact_us'),
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.$t('t_contact_us')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home('contact') },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.$t('t_contact_us')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'twitter:description', content: this.seo.description },
			      	{ property: 'twitter:image', content: this.seo.image }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},
		
		data: function() {
			return {
				form: {
					name: '',
					email: '',
					subject: '',
					message: '',
				},
				errors: [],
				loading: false
			}
		},

		methods: {
			send: function() {
				this.loading = true
				this.$axios
				.post('contact', {
					name: this.form.name,
					email: this.form.email,
					subject: this.form.subject,
					message: this.form.message
				})
				.then(response => {
					this.errors  = []
					this.form    = {
						name: '',
						email: '',
						subject: '',
						message: '',
					}
					this.$toasted.show(this.$t('t_toasted_message_sent'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors  = error.response.data.errors
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