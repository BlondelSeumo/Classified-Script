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
		            <div class="title">{{ $t('t_create_article') }}</div>
		            <span class="card-description">{{ $t('t_create_article_paragraph') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Title -->
						<v-flex xs12>
							<v-text-field
								v-model="form.title"
								color="grey lighten-1"
								:label="$t('t_title')"
								:placeholder="$t('t_enter_title')"
								counter="100"
								maxlength="100"
								:rules="errors.title"
								:error="errors.title ? true : false"
								></v-text-field>
						</v-flex>

						<!-- cover -->
						<v-flex xs12 class="mb-3">
							<upload-btn @file-update="cover" small block accept="image/*" :title="$t('t_choose_cover')" :color="$vuetify.theme.dark ? 'grey darken-4' : 'grey lighten-3'" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Content -->
						<v-flex xs12>
							<div class="form-group" :class="[errors.content ? 'has-danger' : '']">
								<label for="input-normal">{{ $t('t_content') }}</label> 
								<ckeditor :config="{language: this.$i18n.locale}" class="form-control" type="classic" v-model="form.content"></ckeditor>
							</div>
							<small class="red--text block" v-if="errors.content">{{ errors.content[0] }}</small>
						</v-flex>
						
						<v-flex xs12>
							<v-btn @click.prevent="create" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_create') }}</v-btn>
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
			if (!app.$adgate.allow(app.$auth.user, 'create', 'blog')) {
				redirect('/administrator')
			}
		},

		data: function() {
			return {
				form: {
					title: '',
					cover: null,
					content: ''
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_article'),
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
			create: function() {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'create', 'blog')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				let data = new FormData()
				data.append('title', this.form.title)
				if (this.form.cover) {
					data.append('cover', this.form.cover)
				}
				data.append('content', this.form.content)
				this.$axios
				.post('administrator/blog/options/create',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					this.errors   = []
					this.form     = {
						title: '',
						cover: null,
						content: ''
					}
					this.$toasted.show(this.$t('t_toasted_article_created'), {
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

			cover: function(file) {
				this.form.cover = file
			}
		}
	}
</script>