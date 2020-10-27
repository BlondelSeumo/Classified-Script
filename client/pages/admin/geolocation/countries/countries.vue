<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Edit dialog -->
		<v-dialog v-model="edit.dialog" max-width="500px" persistent v-if="$owgate.allow($auth.user, 'edit', 'geo')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_edit_country') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="edit.dialog = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>

							<!-- Name -->
							<v-flex xs12>
								<v-text-field
									v-model="edit.country.name"
									color="grey lighten-1"
									:label="$t('t_country_name')"
									:placeholder="$t('t_enter_country_name')"
									counter="60"
									maxlength="60"
									:rules="errors.name"
									:error="errors.name ? true : false"
									></v-text-field>
							</v-flex>

							<!-- Capital -->
							<v-flex xs12>
								<v-text-field
									v-model="edit.country.capital"
									color="grey lighten-1"
									:label="$t('t_capital')"
									:placeholder="$t('t_enter_capital')"
									counter="60"
									maxlength="60"
									:rules="errors.capital"
									:error="errors.capital ? true : false"
									></v-text-field>
							</v-flex>

							<!-- States -->
							<v-flex xs12>
								<label class="form-group has-float-label" :class="[errors.has_division ? 'has-danger' : '']">
									<v-switch v-model="edit.country.has_division" class="ma-0 form-group form-control" color="blue"></v-switch>
									<span>{{ $t('t_country_has_states') }}</span>
									<small class="form-text" v-if="errors.has_division">{{ errors.has_division[0] }}</small>
								</label>
							</v-flex>

						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn color="#2e3131" text @click="update()">{{ $t('t_update') }}</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_countries') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Create country -->
			        <v-tooltip top>
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/geolocation/countries/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					:headers="headers"
					:items="countries"
					item-key="id"
      				hide-default-footer
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Flag -->
					<template v-slot:item.flag="{ item }">
						<v-avatar size="36px">
							<img :src="$homeApi('public/images/flags/large/' + item.code.toUpperCase() + '.png')">
						</v-avatar>
					</template>

					<!-- Continent -->
					<template v-slot:item.continent="{ item }">{{ item.continent.name }}</template>

					<!-- Country -->
					<template v-slot:item.country="{ item }">{{ item.name }}</template>

					<!-- Capital -->
					<template v-slot:item.capital="{ item }">{{ item.capital }}</template>

					<!-- Phone code -->
					<template v-slot:item.phone="{ item }">{{ item.callingcode }}</template>

					<!-- States -->
					<template v-slot:item.states="{ item }">
						<v-tooltip top v-if="item.has_division">
							<template v-slot:activator="{ on }">
								<v-btn text icon color="green accent-3" v-on="on">
									<v-icon size="20px">mdi-check-all</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_has_states') }}</span>
						</v-tooltip>
						<v-tooltip top v-else>
							<template v-slot:activator="{ on }">
								<v-btn text icon color="error" v-on="on">
									<v-icon size="20px">mdi-close</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_no_states') }}</span>
						</v-tooltip>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn small icon @click="change(item, countries.indexOf(item))" v-if="$owgate.allow($auth.user, 'edit', 'geo')">
							<v-icon size="18px" color="grey darken-1">mdi-square-edit-outline</v-icon>
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="countriesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
		      	</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$owgate.allow(app.$auth.user, 'access', 'geo')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/geolocation/countries')
			return {
				countriesData: response.data,
				countries: response.data.data
			}
		},

		data: function() {
			return {
				headers: [
		          	{ text: this.$t('t_flag'), value: 'flag', sortable: false, align: 'center'},
		          	{ text: this.$t('t_continent'), value: 'continent', sortable: false, align: 'center'},
		          	{ text: this.$t('t_country'), value: 'country', sortable: false, align: 'center'},
		          	{ text: this.$t('t_capital'), value: 'capital', sortable: false, align: 'center'},
		          	{ text: this.$t('t_calling_code'), value: 'phone', sortable: false, align: 'center'},
		          	{ text: this.$t('t_states'), value: 'states', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        edit: {
		        	country: {
		        		id: '',
		        		name: '',
		        		capital: '',
		        		has_division: false
		        	},
		        	index: null,
		        	dialog: false
		        },
		        errors: [],
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_countries'),
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
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('administrator/geolocation/countries?page=' + page)
				.then(response => {
					this.selected      = []
					this.countriesData = response.data
					this.countries     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading       = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
				})
			},

		   	change: function(country, index) {
				this.edit.country.id           = country.id
				this.edit.country.name         = country.name
				this.edit.country.capital      = country.capital
				this.edit.country.has_division = country.has_division
				this.edit.index                = index
				this.edit.dialog               = true
		   	},

		   	update: function() {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'edit', 'geo')) {
					this.$router.push('/administrator')	
					return
				}

		   		this.loading = true
		   		this.$axios
		   		.post('administrator/geolocation/countries/options/edit', {
		   			id: this.edit.country.id,
		   			name: this.edit.country.name,
		   			capital: this.edit.country.capital,
		   			has_division: this.edit.country.has_division,
		   		})
		   		.then(response => {
					this.countries[this.edit.index].name         = response.data.name
					this.countries[this.edit.index].capital      = response.data.capital
					this.countries[this.edit.index].has_division = response.data.has_division
					this.edit.dialog                             = false
					this.edit.country.name                       = ''
					this.edit.country.capital                    = ''
					this.edit.country.has_division               = false
					this.edit.country.index                      = null
		   			this.$toasted.show(this.$t('t_toasted_country_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
		   		})
		   		.catch(error => {
		   			if (error.response.data.errors) {
		   				this.errors = error.response.data.errors
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