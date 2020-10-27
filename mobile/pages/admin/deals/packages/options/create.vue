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
		            <div class="title">{{ $t('t_create_package') }}</div>
		            <span class="card-description">{{ $t('t_create_package_deals_para') }}</span>
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
								counter="60"
								maxlength="60"
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
								counter="60"
								maxlength="60"
								:rules="errors.slug"
								:error="errors.slug ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Package type -->
						<v-flex xs12>
							<v-select
					          	:items="types"
					          	item-text="name"
					          	item-value="id"
								v-model="form.type"
								color="grey lighten-1"
								:label="$t('t_package_type')"
								:placeholder="$t('t_choose_package_type')"
								:rules="errors.type"
								:error="errors.type ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Days -->
						<v-flex xs12>
							<v-text-field
								v-model="form.days"
								color="grey lighten-1"
								:label="$t('t_head_days')"
								:placeholder="$t('t_enter_days')"
								type="number"
								:rules="errors.days"
								:error="errors.days ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Price -->
						<v-flex xs12>
							<v-text-field
								v-model="form.price"
								color="grey lighten-1"
								:label="$t('t_price')"
								:placeholder="$t('t_enter_price')"
								:rules="errors.price"
								:error="errors.price ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Currency -->
						<v-flex xs12>
							<v-autocomplete
								autocomplete="off"
								v-model="form.currency"
								:items="currencies"
						        item-text="name"
						        item-value="code"
								color="grey lighten-1"
								:label="$t('t_currency')"
								:placeholder="$t('t_enter_currency')"
								:rules="errors.currency"
								:error="errors.currency ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Discount -->
						<v-flex xs12>
							<v-text-field
								v-model="form.discount"
								color="grey lighten-1"
								:label="$t('t_discount')"
								:placeholder="$t('t_enter_discount')"
								type="number"
								:rules="errors.discount"
								:error="errors.discount ? true : false"
								></v-text-field>
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'plans')) {
				redirect('/administrator')
			}

			let response = await $axios.post('administrator/ajax/fetch/currencies')
			return {
				currencies: response.data
			}
		},

		data: function() {
			return {
				form: {
					title: null,
					slug: null,
					type: null,
					days: null,
					price: null,
					discount: null,
					currency: null
				},
				types: [
		          	{ id: 'featured', name: this.$t('t_featured')},
		          	{ id: 'urgent', name: this.$t('t_urgent')},
		          	{ id: 'highlight', name: this.$t('t_highlight')}
		        ],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_create_package'),
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
				if (!this.$owgate.allow(this.$auth.user, 'access', 'plans')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading  = true
				this.$axios
				.post('administrator/deals/packages/options/create', {
					title: this.form.title,
					slug: this.form.slug,
					type: this.form.type,
					days: this.form.days,
					price: this.form.price,
					currency: this.form.currency,
					discount: this.form.discount
				})
				.then(response => {
					this.errors   = []
					this.form     = {
						title: null,
						slug: null,
						type: null,
						days: null,
						price: null,
						discount: null,
						currency: null
					}
					this.$toasted.show(this.$t('t_toasted_package_created'), {
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