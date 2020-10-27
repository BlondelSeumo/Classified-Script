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
      					<v-card class="m-card mt-12" flat>
      						<v-card-title primary-title class="pa-0 justify-center">
					          	<h1 class="display-1 font-weight-black text-uppercase pt-12">{{ $t('t_feedback') }}</h1>
					        </v-card-title>
							<v-container grid-list-xl fluid class="pa-7 mt-4">
								<v-layout wrap>

									<!-- satisfied unsatisfied -->
									<v-flex xs12>
										<v-chip-group
											mandatory
											class="satisfied-btn"
											>
											<v-chip @click="form.isSatisfied = 1" :text-color="form.isSatisfied === 1 ? 'success' : ''">{{ $t('t_i_am_satisfied') }}</v-chip>
											<v-chip @click="form.isSatisfied = 0" :text-color="form.isSatisfied === 0 ? 'error' : ''">{{ $t('t_i_am_unsatisfied') }}</v-chip>
										</v-chip-group>
						          	</v-flex>

									<!-- Feedback -->
									<v-flex xs12>
										<v-textarea
											v-model="form.feedback"
											color="grey lighten-1"
											:label="$t('t_feedback')"
											:placeholder="$t('t_enter_feedback')"
											counter="700"
											maxlength="700"
											:rules="errors.feedback"
											:error="errors.feedback ? true : false"
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
			let response = await $axios.get('contact/feedback')
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
				title: this.$t('t_feedback'),
				titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'index, follow' },
			      	{ property: 'og:type', content: 'article' },
			      	{ property: 'og:title', content: `${this.$t('t_feedback')} ${this.seo.separator} ${this.seo.title}` },
			      	{ property: 'og:description', content: this.seo.description },
			      	{ property: 'og:image', content: this.seo.image },
			      	{ property: 'og:url', content: this.$home('feedback') },
			      	{ property: 'og:site_name', content: this.seo.title },
			      	{ property: 'twitter:title', content: `${this.$t('t_feedback')} ${this.seo.separator} ${this.seo.title}` },
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
					isSatisfied: '',
					feedback: ''
				},
				errors: [],
				loading: false
			}
		},

		methods: {
			send: function() {
				this.loading = true
				this.$axios
				.post('contact/feedback', {
					isSatisfied: this.form.isSatisfied,
					feedback: this.form.feedback
				})
				.then(response => {
					this.errors  = []
					this.form    = {
						isSatisfied: '',
						feedback: ''
					}
					this.$toasted.show(this.$t('t_toasted_feedback_sent'), {
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

<style>
	.satisfied-btn .v-chip {
		width: 50%;
		text-align: center;
		display: block;
		margin: 10px 0;
	}
</style>