<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

        <v-container grid-list-xl fluid class="pa-4">
            <v-layout wrap>

                <!-- Edit deal -->
                <v-flex xs9>
                    <v-card class="m-card px-3" flat>
                        <v-card-title primary-title class="pa-4">
                        <div>
                            <div class="title">{{ $t('t_edit_deal') }}</div>
                            <span class="card-description">{{ $t('t_edit_deal_para', {title: deal.title}) }}</span>
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
                                            :value="fieldValue(field.field.slug)"
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
                                            v-for="(value, index) in field.field.options.split(',')"
                                            :key="index"
                                            @change="addCheckboxField($event, field.field.slug, value)"
                                            :label="value"
                                            color="red"
                                            :value="value"
                                            class="mb-3"
                                            hide-details
                                            :input-value="fieldValue(field.field.slug, field.field.type, index, value)"
                                            ></v-checkbox>
                                            <small class="error--text" v-if="errors[field.field.slug]">{{ errors[field.field.slug][0] }}</small>
                                    </v-flex>

                                    <!-- Radio -->
                                    <v-flex xs12 v-if="field.field.type === 'radio'">
                                        <div :class="errors[field.field.slug] ? 'error--text' : ''">{{ field.field.name }}</div>
                                        <v-radio-group column :value="fieldValue(field.field.slug)">
                                            <v-radio 
                                                v-for="value in field.field.options.split(',')"
                                                :key="value"
                                                @change="addRadioField(value, field.field.slug)"
                                                :label="value"
                                                color="red"
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

                                <!-- Update -->
                                <v-flex xs12>
                                    <v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1" dark>{{ $t('t_update') }}</v-btn>
                                </v-flex>

                            </v-layout>
                        </v-container>
                    </v-card>
                </v-flex>

                <!-- Photos -->
                <v-flex xs3>
                    <client-only>
                        <div v-sticky="{zIndex: 3, stickyTop: 80}">
                            <div>
                                <file-pond
                                    name="test"
                                    ref="pond"
                                    :label-idle="$t('t_drag_deal_images')"
                                    allow-multiple="true"
                                    accepted-file-types="image/jpeg, image/png"
                                    :server="server"
                                    :files="form.images"/>
                            </div>
                        </div>
                    </client-only>
                </v-flex>

            </v-layout>
        </v-container>

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
		layout: 'default/manager',

		middleware: 'manager',

		component: FilePond,

		async asyncData({ app, $axios, params, redirect }) {
			let response = await $axios.get(`manager/deals/options/edit/${params.id}`)
			return {
				form: {
            		currency: response.data.deal.currency,
            		title: response.data.deal.title,
					description: response.data.deal.description,
					price: response.data.deal.price,
					category: response.data.deal.category_id,
					sub_cat: response.data.deal.category_id,
					video: response.data.deal.video,
					images: [],
					fields: response.data.formFields
            	},
            	fields: response.data.fields,
            	currencies: response.data.currencies,
            	categories: response.data.parents,
            	childs: response.data.subs,
                deal: response.data.deal
			}
		},

		data: function() {
			return {
				errors: [],
                dialogs: {
			    	currency: false
				},
				server: {
					url: process.env.SERVER_URL,
					process: false,
					load: (source, load, headers) => {
						fetch(source)
						.then(res => res.blob())
						.then(load)
					}
				},
                loading: true
			}
		},

		head() {
			return {
				title: this.$t('t_edit_deal'),
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
			update() {
                try {

					this.loading  = true
					let fields    = JSON.stringify(this.form.fields);
					let data      = new FormData()
					data.append('id', this.deal.unique_id)
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
                    if (this.form.category) {
                        data.append('category', this.form.category)
                    }
					if (this.form.sub_cat) {
						data.append('sub_cat', this.form.sub_cat)
                    }
                    if (this.form.currency) {
                        data.append('currency', this.form.currency)
                    }
					data.append('fields', fields)

					for (let i = 0; i < this.$refs.pond.getFiles().length; i++) {
						if (this.$refs.pond.getFiles()[i].file.type === 'image/jpeg' || this.$refs.pond.getFiles()[i].file.type === 'image/png') {
					    	data.append('images[]', this.$refs.pond.getFiles()[i].file)							
						}
					}

					this.$axios
					.post('manager/deals/options/update',
					  	data,
					  	{
						    headers: {
						        'Content-Type': 'multipart/form-data'
						    }
					  	}
					)
					.then(response => {
						this.errors   = []
						this.$toasted.show(this.$t('t_toasted_deal_updated'), {
							icon: 'done_all',
							className: this.$vuetify.rtl ? 'toasted-rtl' : ''
						})
						this.$router.push('/manager/deals')
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

            },
            
            fieldValue(slug, type = null, index, value = null) {
				if (this.form.fields.length !== 0) {
					if (type === 'checkbox') {
						let search = this.form.fields.find(function(element) { 
							return element.slug === slug
                        })
                        if (search) {
                            let exists = search.value.find(function(element) { 
                                return element === value
                            })

                            return exists
                        }
						
                    }else{
                        // Not checkbox
                        let search = this.form.fields.find(function(element) { 
                            return element.slug === slug
                        })

                        if (search) {
                            return search.value	
                        }
                    }
				}
			},
            
            fetchImages() {
				// Check if deal has photos
				if (this.deal.photosNumber) {
					
					// Loop through images
					for (let index = 0; index < this.deal.photosNumber; index++) {
						
						// Generate file object
						let image = {
							source: this.$homeApi(`api/fetch/image/${this.deal.unique_id}-preview_${index}.jpg?v=${Math.random()}`),
							options: {
								type: 'local'
							}
						}

						// Add image
						this.form.images.push(image)
						
					}

				}
			}
        },
        
        mounted() {
			if (process.client) {
				this.fetchImages()
				this.loading = false
			}
		}
	}
</script>