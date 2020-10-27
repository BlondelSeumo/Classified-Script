<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
				<v-layout wrap>

	      			<v-flex xs12>
	      				<!-- Deal details -->
						<v-card class="m-card mb-4" flat>
							<v-card-title primary-title class="text-center pa-5">
					          	<div class="text-center" style="width:100%">
						          	<v-icon color="grey darken-1" size="80">mdi-image-multiple</v-icon>
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

									<!-- Description -->
									<v-flex xs12>
										<div class="form-group mb-4" :class="[errors.description ? 'has-danger' : '']">
											<label for="input-normal">{{ $t('t_description') }}</label> 
											<ckeditor :config="{language: this.$i18n.locale}" class="form-control" type="classic" v-model="form.description"></ckeditor>
										</div>
										<small class="red--text block" v-if="errors.description">{{ errors.description[0] }}</small>
									</v-flex>

									<!-- Categories -->
									<v-flex xs12>
										<v-autocomplete
											@change="fetchFields(); fetchChilds()"
											v-model="form.category"
											:items="categories"
								          	item-text="title"
								          	item-value="id"
											:label="$t('t_category')"
											:placeholder="$t('t_choose_category')"
											autocomplete="off"
											color="grey lighten-1"
											:rules="errors.category"
											:error="errors.category ? true : false"
											dense
											>
										</v-autocomplete>
									</v-flex>

									<!-- Childs -->
									<v-flex xs12 v-if="childs.length !== 0">
										<v-autocomplete
											@change="fetchFields(true); fetchSubs()"
											v-model="form.sub_cat"
											:items="childs"
								          	item-text="title"
								          	item-value="id"
											:label="$t('t_sub_category')"
											:placeholder="$t('t_choose_sub_category')"
											autocomplete="off"
											color="grey lighten-1"
											:rules="errors.sub_cat"
											:error="errors.sub_cat ? true : false"
											dense
											>
										</v-autocomplete>
									</v-flex>

									<!-- Custom fields -->
									<div style="width: 100%" v-for="field in fields" :key="field.id">
										
										<!-- Dropdown -->
										<v-flex xs12 v-if="field.field.type === 'dropdown'">
											<v-select
												@change="addDropdownField($event, field.field.slug)"
												:items="field.field.options.split(',')"
												:placeholder="field.field.name"
						          				:label="field.field.name"
												autocomplete="off"
												color="grey lighten-1"
												:rules="errors[field.field.slug]"
												:error="errors[field.field.slug] ? true : false"
												dense
												>
											</v-select>
										</v-flex>

										<!-- Checkbox -->
										<v-flex xs12 v-if="field.field.type === 'checkbox'">
									        <div :class="errors[field.field.slug] ? 'error--text' : ''">{{ field.field.name }}</div>
											<v-checkbox
												v-for="value in field.field.options.split(',')"
												:key="value"
												@change="addCheckboxField($event, field.field.slug, value)"
												:label="value"
												:color="$vuetify.theme.dark ? 'white' : 'red'"
												:value="value"
												class="mb-3"
												hide-details
												></v-checkbox>
												<small class="error--text" v-if="errors[field.field.slug]">{{ errors[field.field.slug][0] }}</small>
										</v-flex>

										<!-- Radio -->
										<v-flex xs12 v-if="field.field.type === 'radio'">
									        <div :class="errors[field.field.slug] ? 'error--text' : ''">{{ field.field.name }}</div>
									        <v-radio-group column>
										      	<v-radio 
										      		v-for="value in field.field.options.split(',')"
													:key="value"
													@change="addRadioField(value, field.field.slug)"
													:label="value"
													:color="$vuetify.theme.dark ? 'white' : 'red'"
													:value="value"
													class="mb-3"
													hide-details
										      		></v-radio>
										      	<small class="error--text" v-if="errors[field.field.slug]">{{ errors[field.field.slug][0] }}</small>
										    </v-radio-group>
										</v-flex>

									</div>

									<!-- Price -->
									<v-flex xs12>
										<v-text-field
											v-model="form.price"
											color="grey lighten-1"
											:label="$t('t_price')"
											:placeholder="$t('t_enter_price')"
											:prefix="$currencySymbol(form.currency)"
											append-icon="mdi-dots-vertical"
											@click:append="changeCurrency"
											:rules="errors.price"
											:error="errors.price ? true : false"
											></v-text-field>
									</v-flex>

									<!-- Currency -->
									<v-flex xs12 v-if="dialogs.currency">
										<v-autocomplete
											@change="dialogs.currency = !dialogs.currency"
											v-model="form.currency"
											autocomplete="off"
											color="grey lighten-1"
								          	:items="currencies"
								          	item-text="name"
								          	item-value="code"
								          	:placeholder="$t('t_enter_currency')"
								          	:label="$t('t_currency')"
								          	dense
								        ></v-autocomplete>
									</v-flex>

									<!-- Youtube video -->
									<v-flex xs12>
										<v-text-field
											v-model="form.video"
											color="grey lighten-1"
											:label="$t('t_youtube_video')"
											:placeholder="$t('t_enter_youtube_video')"
											:rules="errors.video"
											:error="errors.video ? true : false"
											></v-text-field>
									</v-flex>

								</v-layout>
							</v-container>
						</v-card>

						<!-- Promote && create -->
						<v-container fluid class="pa-0">
							<v-layout column wrap>
								
								<!-- Upload images -->
								<v-flex xs12>
									<client-only>
										<div class="mt-4">
											<div>
												<file-pond
													name="test"
													ref="pond"
													:label-idle="$t('t_drag_deal_images_mobile')"
													allow-multiple="true"
													accepted-file-types="image/jpeg, image/png"
													:files="form.images"/>
											</div>
										</div>
									</client-only>
								</v-flex>

								<v-flex xs12>
									<v-container grid-list-xl class="pa-0">
										<v-layout row wrap align-start justify-start>
											<!-- Packages --> 
											<v-flex xs6 v-for="(p, index) in packages" :key="index">
												<v-card flat style="box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.08);" class="py-3 cursor-pointer" :color="selected === p.id ? '#878d8b' : ($vuetify.theme.dark ? '#3a3939' : 'white')" :class="selected === p.id ? 'selected-package' : ''">
													<v-icon @click="selected = null" v-show="p.id === selected" style="position:absolute;top:10px;right:10px;" size="16px" color="white">mdi-close</v-icon>
													<v-tooltip top>
														<template v-slot:activator="{ on }">
															<v-icon v-on="on" @click="selected = null" v-show="selected !== p.id" style="position:absolute;top:10px;left:10px;" size="16px" color="#7a7a7a">mdi-information-variant</v-icon>
														</template>
														<span>{{ p.title }}</span>
													</v-tooltip>
													<v-card-title @click="selected = p.id" primary-title class="layout justify-center pb-5">
														<v-chip v-show="p.type === 'highlight'" small label color="amber lighten-4" text-color="amber darken-2">{{ $t('t_highlight') }}</v-chip>
														<v-chip v-show="p.type === 'urgent'" small label color="red lighten-4" text-color="red darken-2">{{ $t('t_urgent') }}</v-chip>
														<v-chip v-show="p.type === 'featured'" small label color="light-green lighten-4" text-color="light-green darken-2">{{ $t('t_featured') }}</v-chip>
													</v-card-title>
													<v-card-text @click="selected = p.id" class="text-center">
														<span class="package-price pb-5" style="font-family:'Open sans',sans-serif;font-size:16px!important;letter-spacing:1px;font-weight:700;">{{ $getCurrency(p.price, p.currency, p.locale) }}</span>
														<div class="grey--text pt-5">
															<span class="package-days" style="font-family:Hind, 'Noto Kufi Arabic',sans-serif;font-weight:500;text-transform:uppercase;font-size:10px;letter-spacing:1px;color:#595959;padding-top: 15px">{{ p.days | numeralFormat }} {{ $t('t_days') }}</span><br/>
															<br/>
														</div>
													</v-card-text>
												</v-card>
											</v-flex>
											
											<!-- Create -->
											<v-flex xs12>
												<v-btn @click.prevent="create" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_create') }}</v-btn>
											</v-flex>
										</v-layout>
									</v-container>
								</v-flex>
							</v-layout>
						</v-container>
					</v-flex>

					<v-flex xs12>

						

						<!-- Advertisement -->
      					<v-card class="advertisement mt-4 m-card pa-0 text-center" v-if="advertisement" v-html="advertisement.ad_deal_sidebar"></v-card>

					</v-flex>
				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	import vueFilePond from 'vue-filepond';
	import 'filepond/dist/filepond.min.css';
	import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
	import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
	import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
	const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

	export default {

		middleware: 'auth',

		layout: 'default/main',

		component: FilePond,

		async asyncData ({ $axios }) {
            let response = await $axios.post('create/fetch')

            return {
            	form: {
            		currency: response.data.currency.currency.code,
            		title: '',
					description: '',
					price: '',
					category: '',
					sub_cat: '',
					video: '',
					images: [],
					fields: []
            	},
            	countries: response.data.countries,
            	currencies: response.data.currencies,
            	categories: response.data.categories,
            	advertisement: response.data.advertisement,
            	packages: response.data.packages,
            	seo: {
	  				title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon,
	  			}
            }
        },

		data: function() {
			return {
				fields: [],
				childs: [],
				errors: [],
				selected: null,
				loading: false,
			    dialogs: {
			    	currency: false
			    }
			}
		},

		head() {
			return {
	  			title: this.$t('t_post_new'),
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'noindex, nofollow' },
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
      			]
			}
		},

		methods: {
			create: function() {
				try {

					this.loading  = true
					let fields    = JSON.stringify(this.form.fields);
					let data      = new FormData()
					data.append('title', this.form.title)
					if (this.form.description) {
					    data.append('description', this.form.description)                        
                    }
                    if (this.form.video) {
					    data.append('video', this.form.video)                        
                    }
                    if (this.form.price) {
                        data.append('price', this.form.price)
                    }
					data.append('category', this.form.category)
					if (this.form.sub_cat) {
						data.append('sub_cat', this.form.sub_cat)
					}
					if (this.selected) {
						data.append('package', this.selected)
					}
					if (this.form.currency) {
                        data.append('currency', this.form.currency)
                    }
					data.append('fields', fields)

					for (let i = 0; i < this.$refs.pond.getFiles().length; i++) {
					    data.append('images[]', this.$refs.pond.getFiles()[i].file)
					}

					this.$axios
					.post('create',
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
							description: '',
							price: '',
							video: '',
							fields: []

						}

						// Deal requires approval?
						if (response.data === 'approval_required') {
							this.$router.push('/')
							this.$toasted.success(this.$t('t_toasted_deal_requires_approval'), {
								icon: 'sync',
								className: this.$vuetify.rtl ? 'toasted-rtl' : ''
							})
							this.loading = false
							return
						}

						this.$router.push(response.data)
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
				catch(error) {
				  	this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.loading = false
				}
			},

			changeCurrency: function() {
				this.dialogs.currency = !this.dialogs.currency
			},

			remove (item) {
		        this.form.categories.splice(this.form.categories.indexOf(item), 1)
		        this.form.categories = [...this.form.categories]
		    },

			icon: function(icon) {
				if (icon === null) {
					return this.home('/application/storage/app/uploads/default/category/no-icon.png')
				}else{
					return this.home('/application/storage/app/' + icon);
				}
			},

			fetchFields: function(isChilds = false) {
				this.form.fields = []
				this.loading     = true
				let catId        = isChilds ? this.form.sub_cat : this.form.category
				this.$axios
				.post('ajax/fetch/fields', {
					category: catId
				})
				.then(response => {
					this.fields  = response.data
					this.loading = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
				})
			},

			fetchChilds: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/categories/childs', {
					id: this.form.category
				})
				.then(response => {
					if (response.data.length === 0) {
						this.form.sub_cat = null
						this.childs       = []
					}else{
						this.childs  = response.data
					}
					this.loading = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
				})
			},

			fetchSubs: function() {
				this.loading = true
				this.$axios
				.post('ajax/fetch/categories/childs', {
					id: this.form.sub_cat
				})
				.then(response => {
					if (response.data.length !== 0) {
						let older             = this.form.sub_cat
						this.form.sub_cat     = null
						this.$nextTick(() => {
							this.childs       = response.data
							this.form.sub_cat = older
						})
					}
					this.loading = false
				})
				.catch(error => {
					console.log(error)
					this.loading = false
				})
			},

			addDropdownField: function(value, slug) {
				
				let vm     = this
				let data   = {slug: slug, value: value}
				let length = vm.form.fields.length
				    	
				// Check if fields are empty
		  		if (length === 0) {
					vm.form.fields.push(data)
					return
				}else{

					// Check if field already exists
					let exists = vm.form.fields.some(a => a.slug === slug);


					if (exists) {

						// Change value
						for (var index in vm.form.fields) {

							let field = vm.form.fields[index]

							// Change
							if (field.slug === slug) {
								field.value = value
								break
							}

						}

						return

					}else{

						// Add new
						vm.form.fields.push(data)
						return

					}
				}

			},

			addCheckboxField: function(value, slug, checkbox) {

				let vm     = this 
				let data   = {slug: slug, value: [value]}
				let length = vm.form.fields.length

				// Check if fields empty
		    	if (length === 0) {
					vm.form.fields.push(data)
					return
				}else{

					// Check if slug already exists
					let exists = vm.form.fields.some(a => a.slug === slug);


					if (exists) {

						// Change value
						for (var index in vm.form.fields) {

							let field = vm.form.fields[index]

							// Change
							if (field.slug === slug) {

								// Get checkbox values
								let values = field.value

								// Add new
								if (values.length === 0) {
									values.push(value)
								}

								// Loop trough values
								for (var val in values) {

									let old = vm.form.fields[index].value[val]

									// Remove old value
									if (value === null && old === checkbox) {
										vm.$delete(vm.form.fields[index].value, val)
										break
										return
									}

									// Add new value
									if (value !== null && checkbox !== old) {
										vm.form.fields[index].value.push(value)
										break
										return
									}
								}

								break
							}

						}

						return

					}else{

						// Add new
						vm.form.fields.push(data)
						return

					}
				}

			},

			addRadioField: function(value, slug) {	
				let vm     = this
				let data   = {slug: slug, value: value}
				let length = vm.form.fields.length

				// Check if fields empty
		  		if (length === 0) {
					vm.form.fields.push(data)
					return
				}else{

					let exists = vm.form.fields.some(a => a.slug === slug);


					if (exists) {

						// Change value
						for (var index in vm.form.fields) {

							let field = vm.form.fields[index]

							// Change
							if (field.slug === slug) {
								field.value = value
								break
							}

						}

						return

					}else{

						// Add new
						vm.form.fields.push(data)
						return

					}
				}

			}
		}
	}
</script>