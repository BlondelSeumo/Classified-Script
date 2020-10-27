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
		            <div class="title">{{ $t('t_create_page') }}</div>
		            <span class="card-description">{{ $t('t_create_page_para') }}</span>
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

						<!-- Slug -->
						<v-flex xs12>
							<v-text-field
								v-model="form.slug"
								color="grey lighten-1"
								:label="$t('t_slug')"
								:placeholder="$t('t_enter_slug')"
								counter="100"
								maxlength="100"
								:rules="errors.slug"
								:error="errors.slug ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Content -->
						<v-flex xs12>
							<div class="form-group" :class="[errors.content ? 'has-danger' : '']">
								<label for="input-normal">{{ $t('t_content') }}</label> 
								<ckeditor :config="{language: this.$i18n.locale}" class="form-control" type="classic" v-model="form.content"></ckeditor>
							</div>
							<small class="red--text block" v-if="errors.content">{{ errors.content[0] }}</small>
						</v-flex>

						<!-- Create link -->
						<v-flex xs4>
							<label class="form-group has-float-label" :class="[errors.is_link ? 'has-danger' : '']">
								<v-switch v-model="form.is_link" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_create_link') }}</span>
							</label>
							<small class="error-text" v-if="errors.is_link">{{ errors.is_link[0] }}</small>
						</v-flex>

						<!-- Link -->
						<v-flex xs4>
							<v-text-field
								v-model="form.link"
								color="grey lighten-1"
								:label="$t('t_link')"
								:placeholder="$t('t_enter_link')"
								:rules="errors.link"
								:error="errors.link ? true : false"
								:disabled="!form.is_link"
								></v-text-field>
						</v-flex>

						<!-- Footer column -->
						<v-flex xs4>
							<v-select
								v-model="form.column"
								color="grey lighten-1"
					          	:items="[{name: columns.first, id: 'first'},{name: columns.second, id: 'second'},{name: columns.third, id: 'third'},{name: columns.fourth, id: 'fourth'}]"
					          	item-text="name"
					          	item-value="id"
					          	:placeholder="$t('t_choose_footer_column')"
								:label="$t('t_footer_column')"
								:rules="errors.column"
								:error="errors.column ? true : false"
					          	dense
						        ></v-select>
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

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'create', 'pages')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/pages/options/create')
			return {
				columns: {
					first: response.data.first,
					second: response.data.second,
					third: response.data.third,
					fourth: response.data.fourth
				}
			}
		},

		data: function() {
			return {
				form: {
					title: '',
					slug: '',
					content: '',
					is_link: false,
					link: '',
					column: ''
				},
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_page'),
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
				if (!this.$adgate.allow(this.$auth.user, 'create', 'pages')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/pages/options/create',{
					title: this.form.title,
					slug: this.form.slug,
					content: this.form.content,
					is_link: this.form.is_link,
					link: this.form.link,
					column: this.form.column
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						title: '',
						slug: '',
						content: '',
						is_link: false,
						link: '',
						column: ''
					}
					this.$toasted.show(this.$t('t_toasted_page_created'), {
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