<template>
	<v-app id="inspire">

		<v-dialog v-model="loading" persistent elevation-0 width="100" content-class="elevation-0">
			<v-card flat>
				<v-card-text class="pa-4 text-center">
                    <v-progress-circular
                        :size="45"
						width="2"
                        color="light-blue"
                        indeterminate
                    ></v-progress-circular>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-container>
			<v-card class="m-card" flat>
				<v-card-title primary-title class="pa-4">
		          <div>
		            <div class="title">{{ $t('t_edit_shop') }}</div>
		            <span class="card-description">{{ $t('t_edit_shop_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Shop unique name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.store"
								color="grey lighten-1"
								:label="$t('t_shop_username')"
								:placeholder="$t('t_enter_shop_username')"
								:hint="`${$t('t_shop_username_look')} <i>${$home('shop/')}</i> <strong>${form.store}</strong>`"
								persistent-hint
								:rules="errors.store"
								:error="errors.store ? true : false"
								counter="60"
								maxlength="60"
								></v-text-field>
						</v-flex>

						<!-- Title -->
						<v-flex xs6>
							<v-text-field
								v-model="form.title"
								color="grey lighten-1"
								:label="$t('t_shop_title')"
								:placeholder="$t('t_enter_shop_title')"
								:rules="errors.title"
								:error="errors.title ? true : false"
								counter="100"
								maxlength="100"
								></v-text-field>
						</v-flex>

						<!-- Subtitle -->
						<v-flex xs6>
							<v-text-field
								v-model="form.subtitle"
								color="grey lighten-1"
								:label="$t('t_shop_subtitle')"
								:placeholder="$t('t_enter_shop_subtitle')"
								:rules="errors.subtitle"
								:error="errors.subtitle ? true : false"
								counter="100"
								maxlength="100"
								></v-text-field>
						</v-flex>

						<!-- Description -->
						<v-flex xs12>
							<div class="form-group form-group-ckeditor" :class="[errors.description ? 'has-danger' : '']">
								<label for="input-normal">{{ $t('t_description') }}</label> 
								<ckeditor :config="{language: this.$i18n.locale}" class="form-control" type="classic" v-model="form.description"></ckeditor>
							</div>
							<small class="red--text block" v-if="errors.description">{{ errors.description[0] }}</small>
						</v-flex>

						<!-- Address 1 -->
						<v-flex xs4>
							<v-text-field
								v-model="form.address1"
								color="grey lighten-1"
								:label="$t('t_address1')"
								:placeholder="$t('t_enter_address1')"
								:rules="errors.address1"
								:error="errors.address1 ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Address 2 -->
						<v-flex xs4>
							<v-text-field
								v-model="form.address2"
								color="grey lighten-1"
								:label="$t('t_address2')"
								:placeholder="$t('t_enter_address2')"
								:rules="errors.address2"
								:error="errors.address2 ? true : false"
								></v-text-field>
						</v-flex>

						<!-- ZIP code -->
						<v-flex xs4>
							<v-text-field
								v-model="form.zip"
								color="grey lighten-1"
								:label="$t('t_zip')"
								:placeholder="$t('t_enter_zip')"
								:rules="errors.zip"
								:error="errors.zip ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Shop Country -->
						<v-flex xs4>
							<v-autocomplete
								@change="fetchStates()"
								browser-autocomplete="off"
								v-model="form.country"
					          	:items="countries"
					          	item-text="name"
					          	item-value="id"
					          	:placeholder="$t('t_choose_country')"
								color="grey lighten-1"
								:label="$t('t_country')"
								:rules="errors.country"
								:error="errors.country ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Shop State -->
						<v-flex xs4>
							<v-autocomplete
								@change="fetchCities()"
								browser-autocomplete="off"
								v-model="form.state"
					          	:items="states"
					          	item-text="name"
					          	item-value="id"
					          	:placeholder="$t('t_choose_state')"
								color="grey lighten-1"
								:label="$t('t_state')"
								:rules="errors.state"
								:error="errors.state ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Shop City -->
						<v-flex xs4>
							<v-autocomplete
								browser-autocomplete="off"
								v-model="form.city"
					          	:items="cities"
					          	item-text="name"
					          	item-value="id"
					          	:placeholder="$t('t_choose_city')"
								color="grey lighten-1"
								:label="$t('t_city')"
								:rules="errors.city"
								:error="errors.city ? true : false"
								dense
								></v-autocomplete>
						</v-flex>

						<!-- Customer e-mail -->
						<v-flex xs4>
							<v-text-field
								v-model="form.customer_email"
								color="grey lighten-1"
								:label="$t('t_customer_email')"
								:placeholder="$t('t_enter_customer_email')"
								:rules="errors.customer_email"
								:error="errors.customer_email ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Phone number -->
						<v-flex xs4>
							<v-text-field
								v-model="form.phone"
								color="grey lighten-1"
								:label="$t('t_phone')"
								:placeholder="$t('t_enter_phone')"
								:rules="errors.phone"
								:error="errors.phone ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Primary locale -->
						<v-flex xs4>
							<v-select
								v-model="form.primary_locale"
								color="grey lighten-1"
					          	:items="locales"
					          	item-text="name"
					          	item-value="value"
					          	:label="$t('t_shop_locale')"
					          	:placeholder="$t('t_choose_shop_locale')"
								:rules="errors.primary_locale"
								:error="errors.primary_locale ? true : false"
					          	dense
					        ></v-select>
						</v-flex>

						<!-- Shop logo -->
						<v-flex xs6 class="mb-3">
							<upload-btn uniqueId @file-update="logo" block accept="image/*" :title="$t('t_change_logo')" color="grey lighten-3" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Shop cover -->
						<v-flex xs6 class="mb-3">
							<upload-btn uniqueId @file-update="cover" block accept="image/*" :title="$t('t_change_cover')" color="grey lighten-3" class="pa-0"></upload-btn>
						</v-flex>

						<v-flex xs12>
							<v-btn @click="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
						</v-flex>

					</v-layout>
				</v-container>

			</v-card>
		</v-container>

	</v-app>
</template>

<script>
	export default {
		layout: 'default/manager',

		middleware: 'auth',

		async asyncData({ $axios, params }) {
			let response = await $axios.post('manager/shops/options/edit', { shop: params.shop })
			return {
				form: {
					id             : response.data.shop.id,
					old            : response.data.shop.store,
					store          : response.data.shop.store,
					title          : response.data.shop.title,
					subtitle       : response.data.shop.subtitle,
					description    : response.data.shop.description,
					address1       : response.data.shop.address1,
					address2       : response.data.shop.address2,
					country        : response.data.shop.country,
					state          : response.data.shop.state,
					city           : response.data.shop.city,
					zip            : response.data.shop.zip,
					customer_email : response.data.shop.customer_email,
					phone          : response.data.shop.phone,
					primary_locale : response.data.shop.primary_locale
				},
				countries: response.data.countries
			}
		},

		data: function() {
			return {
				locales: [
					{value: "en", name: "English"},
					{value: "fr", name: "French"},
					{value: "ar", name: "Arabic"}
				],
				states: [],
				cities: [],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_edit_shop'),
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
				this.loading = true
				let data     = new FormData()
				data.append('id', this.form.id)
				data.append('store', this.form.store)
				data.append('title', this.form.title)
				data.append('subtitle', this.form.subtitle)
				data.append('description', this.form.description)
				data.append('address1', this.form.address1)
				data.append('address2', this.form.address2)
				if (this.form.country) {
					data.append('country', this.form.country)
				}
				if (this.form.state) {
					data.append('state', this.form.state)
				}
				if (this.form.city) {
					data.append('city', this.form.city)
				}
				if (this.form.customer_email) {
					data.append('customer_email', this.form.customer_email)
				}
				data.append('zip', this.form.zip)
				data.append('phone', this.form.phone)
				data.append('primary_locale', this.form.primary_locale)
				data.append('logo', this.form.logo)
				data.append('cover', this.form.cover)

				this.$axios
				.post('manager/shops/options/update',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_shop_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
					if (this.form.old !== response.data) {
						this.$router.push({ name: 'managerEditShop', params: { shop: response.data }})
					}
				})
				.catch(error => {
					if (error.response.data.errors) {
						this.errors = error.response.data.errors
					}
					this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading    = false
				})
			},

			avatar: function(avatar) {
		   		if (avatar === null) {
		   			return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
		   		}else{
		   			return this.$homeApi('storage/app/' + avatar)
		   		}
		   	},

		   	cover: function(file) {
		   		this.form.cover = file
		   	},

		   	logo: function(file) {
		   		this.form.logo = file
		   	},

			fetchStates: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/states', {
					country: this.form.country
				})
				.then(response => {
					this.states = response.data.states
					this.cities = response.data.cities
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					console.log(error.response.data.errors)
				})
			},

			fetchCities: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/cities', {
					state: this.form.state
				})
				.then(response => {
					this.cities  = response.data
					this.loading = false
				})
				.catch(error => {
					this.loading = false
					console.log(error.response.data.errors)
				})
			}
		},

		created() {
			if (this.form.country !== null) {
				this.fetchStates()
			}
			if (this.form.state !== null) {
				this.fetchCities()
			}
		}
	}
</script>