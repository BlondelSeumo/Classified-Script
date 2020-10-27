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
		            <div class="title">{{ $t('t_edit_category') }}</div>
		            <span class="card-description">{{ $t('t_edit_category_para') }}</span>
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

						<!-- Upload icon -->
						<v-flex xs12>
							<upload-btn uniqueId @file-update="icon" block accept="image/*" :title="$t('t_choose_icon')" color="grey lighten-3" class="pa-0"></upload-btn>
						</v-flex>

						<!-- Create sub category -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.isChild ? 'has-danger' : '']">
								<v-switch v-model="form.isChild" :false-value="0" :true-value="1" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_create_subcategory') }}</span>
							</label>
							<small class="red--text" v-if="errors.isChild">{{ errors.isChild[0] }}</small>
						</v-flex>

						<!-- Parent category -->
						<v-flex xs12 v-show="form.isChild">
							<v-autocomplete
								@change="fetchChilds"
								autocomplete="off"
								color="grey lighten-1"
								v-model="selected"
					          	:items="categories"
					          	item-text="title"
					          	item-value="id"
					          	:placeholder="$t('t_choose_parent_category')"
					          	:label="$t('t_parent_category')"
					          	dense
					        ></v-autocomplete>
						</v-flex>

						<v-flex xs12>
							<v-btn @click.prevent="update" :disabled="loading" :loading="loading" block depressed color="blue" class="mb-1 white--text">{{ $t('t_update') }}</v-btn>
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

		async asyncData({ app, $axios, params, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'edit', 'categories')) {
				redirect('/administrator')
			}

			let response = await $axios.post('administrator/categories/options/edit',  { slug: params.slug })
			return {
                category: response.data.category,
                categories: response.data.parents,
                form: {
					title: response.data.category.title,
					slug: response.data.category.slug,
					icon: '',
					parent: response.data.category.parent_id,
					isChild: response.data.category.parent_id ? 1 : 0
                },
				selected: ''
			}
		},

		data: function() {
			return {
				errors: [],
				noMoreChilds: false,
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_edit_category'),
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
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'edit', 'categories')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				let data = new FormData()
				data.append('title', this.form.title)
				data.append('slug', this.form.slug)
				data.append('isChild', this.form.isChild)
                data.append('parent', this.form.parent)
                if (this.form.icon) {
                    data.append('icon', this.form.icon)
                }
				this.$axios
				.post('administrator/categories/options/update',
				  	data,
				  	{
					    headers: {
					        'Content-Type': 'multipart/form-data'
					    }
				  	}
				)
				.then(response => {
					this.errors         = []
					this.categoriesData = response.data
					this.selected       = ''
					this.$toasted.show(this.$t('t_toasted_category_updated'), {
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

			fetchChilds: function() {
				this.$nextTick(() => {
					if (!this.noMoreChilds) {
						this.loading  = true
						this.$axios
						.post('administrator/ajax/fetch/categories/childs', {
							parent: this.selected
						})
						.then(response => {
							if (response.data.hasChilds) {
                                this.loading        = false
								this.categories     = response.data.childs
								this.form.parent    = this.selected
								this.selected       = ''
							}else{
								this.loading        = false
								this.form.parent    = this.selected
								this.noMoreChilds   = true
							}
						})
						.catch(error => {
							this.loading = false
							console.log(error)
						})
					}
				});
			},

			icon: function(file) {
				this.form.icon = file
            }
		}
	}
</script>