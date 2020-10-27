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
		            <span class="card-description">{{ $t('t_create_package_para') }}</span>
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

						<!-- Subtitle -->
						<v-flex xs12>
							<v-text-field
								v-model="form.subtitle"
								color="grey lighten-1"
								:label="$t('t_subtitle')"
								:placeholder="$t('t_enter_subtitle')"
								counter="60"
								maxlength="60"
								:rules="errors.subtitle"
								:error="errors.subtitle ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Description -->
						<v-flex xs12>
							<v-text-field
								v-model="form.description"
								color="grey lighten-1"
								:label="$t('t_description')"
								:placeholder="$t('t_enter_description')"
								counter="100"
								maxlength="100"
								:rules="errors.description"
								:error="errors.description ? true : false"
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
								:hint="`<i>${$home('pricing/checkout/')}</i> <strong>${form.slug}</strong>`"
								></v-text-field>
						</v-flex>

						<!-- Discount -->
						<v-flex xs3>
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

						<!-- Price -->
						<v-flex xs3>
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
						<v-flex xs3>
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

						<!-- Frequency -->
						<v-flex xs3>
							<v-select
					          	:items="frequency"
					          	item-text="name"
					          	item-value="id"
								v-model="form.frequency"
								color="grey lighten-1"
								:label="$t('t_frequency')"
								:placeholder="$t('t_choose_frequency')"
								:rules="errors.frequency"
								:error="errors.frequency ? true : false"
								dense
								></v-select>
						</v-flex>

						<!-- Upload icon -->
						<v-flex xs12>
							<upload-btn uniqueId @file-update="icon" block accept="image/*" :title="$t('t_choose_icon')" color="grey lighten-3" class="pa-0 mb-3"></upload-btn>
						</v-flex>

						<!-- Is popular? -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.recommended ? 'has-danger' : '']">
								<v-switch :true-value="1" :false-value="0" v-model="form.recommended" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_recommended_plan') }}</span>
							</label>
							<small class="error--text" v-if="errors.recommended">{{ errors.recommended[0] }}</small>
						</v-flex>

						<!-- Enable stores -->
						<v-flex xs6>
							<label class="form-group has-float-label" :class="[errors.enable_stores ? 'has-danger' : '']">
								<v-switch :true-value="1" :false-value="0" v-model="form.enable_stores" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_shops') }}</span>
							</label>
							<small class="error--text" v-if="errors.enable_stores">{{ errors.enable_stores[0] }}</small>
						</v-flex>

						<!-- Enable autoshare system -->
						<v-flex xs6>
							<label class="form-group has-float-label" :class="[errors.enable_autoshare ? 'has-danger' : '']">
								<v-switch :true-value="1" :false-value="0" v-model="form.enable_autoshare" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_enable_autoshare') }}</span>
							</label>
							<small class="error--text" v-if="errors.enable_autoshare">{{ errors.enable_autoshare[0] }}</small>
						</v-flex>
	
						<!-- Maximum stores -->
						<v-flex xs3>
							<v-text-field
								v-model="form.maximum_stores"
								color="grey lighten-1"
								:label="$t('t_plan_shops_limits')"
								:placeholder="$t('t_enter_plan_shops_limits')"
								:rules="errors.maximum_stores"
								:error="errors.maximum_stores ? true : false"
								type="number"
								:disabled="!form.enable_stores"
								></v-text-field>
						</v-flex>

						<!-- Maximum deals -->
						<v-flex xs3>
							<v-text-field
								v-model="form.maximum_classifieds"
								color="grey lighten-1"
								:label="$t('t_plan_deals_limits')"
								:placeholder="$t('t_enter_plan_deals_limits')"
								:rules="errors.maximum_classifieds"
								:error="errors.maximum_classifieds ? true : false"
								type="number"
								></v-text-field>
						</v-flex>

						<!-- Maximum images per deal -->
						<v-flex xs3>
							<v-text-field
								v-model="form.maximum_classifieds_images"
								color="grey lighten-1"
								:label="$t('t_plan_deals_photos_limits')"
								:placeholder="$t('t_enter_plan_deals_photos_limits')"
								:rules="errors.maximum_classifieds_images"
								:error="errors.maximum_classifieds_images ? true : false"
								type="number"
								></v-text-field>
						</v-flex>

						<!-- Deal Expiration period -->
						<v-flex xs3>
							<v-text-field
								v-model="form.classifieds_expiration_period"
								color="grey lighten-1"
								:label="$t('t_plan_deal_expiration_period')"
								:placeholder="$t('t_enter_plan_deal_expiration_period')"
								:rules="errors.classifieds_expiration_period"
								:error="errors.classifieds_expiration_period ? true : false"
								type="number"
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
			if (!app.$owgate.allow(app.$auth.user, 'create', 'plans')) {
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
					title: '',
					subtitle: '',
					description: '',
					slug: '',
					price: '',
					discount: '',
					currency: '',
					frequency: '',
					icon: '',
					recommended: 0,
					maximum_stores: '',
					maximum_classifieds: '',
					maximum_classifieds_images: '',
					classifieds_expiration_period: '',
					enable_stores: 0,
					enable_autoshare: 0
				},
				frequency: [
		          	{ id: 'daily', name: this.$t('t_daily')},
		          	{ id: 'weekly', name: this.$t('t_weekly')},
		          	{ id: 'monthly', name: this.$t('t_monthly')},
		          	{ id: 'yearly', name: this.$t('t_yearly')}
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
				if (!this.$owgate.allow(this.$auth.user, 'create', 'plans')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading  = true
				let data = new FormData()
				data.append('icon', this.form.icon)
				data.append('title', this.form.title)
				data.append('subtitle', this.form.subtitle)
				data.append('description', this.form.description)
				data.append('slug', this.form.slug)
				data.append('price', this.form.price)
				data.append('discount', this.form.discount)
				data.append('currency', this.form.currency)
				data.append('frequency', this.form.frequency)
				data.append('recommended', this.form.recommended)
				data.append('maximum_stores', this.form.maximum_stores)
				data.append('maximum_classifieds', this.form.maximum_classifieds)
				data.append('maximum_classifieds_images', this.form.maximum_classifieds_images)
				data.append('classifieds_expiration_period', this.form.classifieds_expiration_period)
				data.append('enable_stores', this.form.enable_stores)
				data.append('enable_autoshare', this.form.enable_autoshare)
				this.$axios
				.post('administrator/membership/plans/options/create',
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
						subtitle: '',
						description: '',
						slug: '',
						price: '',
						discount: '',
						currency: '',
						frequency: '',
						icon: '',
						recommended: 0,
						maximum_stores: '',
						maximum_classifieds: '',
						maximum_classifieds_images: '',
						classifieds_expiration_period: '',
						enable_stores: 0,
						enable_autoshare: 0
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
			},

			icon: function(file) {
				this.form.icon = file
			}
		}
	}
</script>