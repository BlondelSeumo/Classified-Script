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
		            <div class="title">{{ $t('t_edit_custom_field') }}</div>
		            <span class="card-description">{{ $t('t_create_field_para') }}</span>
		          </div>
		        </v-card-title>

				<v-container grid-list-xl fluid class="pa-4">
					<v-layout wrap>

						<!-- Name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.name"
								color="grey lighten-1"
								:label="$t('t_name')"
								:placeholder="$t('t_enter_name')"
								counter="100"
								maxlength="100"
								:rules="errors.name"
								:error="errors.name ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Identifier name -->
						<v-flex xs12>
							<v-text-field
								v-model="form.slug"
								color="grey lighten-1"
								:label="$t('t_identifier_name')"
								:placeholder="$t('t_enter_identifier_name')"
								counter="100"
								maxlength="100"
								:hint="$t('t_hint_identifier_name')"
								:rules="errors.slug"
								:error="errors.slug ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Type -->
						<v-flex xs12>
							<v-select
								autocomplete="off"
								color="grey lighten-1"
								v-model="form.type"
					          	:items="types"
					          	item-text="title"
					          	item-value="value"
					          	:placeholder="$t('t_choose_field_type')"
					          	:label="$t('t_type')"
								:rules="errors.type"
								:error="errors.type ? true : false"
					          	dense
					        ></v-select>
						</v-flex>

						<!-- Options -->
						<v-flex xs12 v-if="form.type !== 'checkbox' && form.type !== ''">
							<v-text-field
								v-model="form.options"
								color="grey lighten-1"
								:label="$t('t_options')"
								:placeholder="$t('t_enter_options')"
								:hint="$t('t_field_options_hint')"
								:rules="errors.options"
								:error="errors.options ? true : false"
								></v-text-field>
						</v-flex>

						<!-- Categories -->
						<v-flex xs12 class="multiple-categories-select">
							<v-autocomplete
								v-model="form.categories"
								:items="categories"
					          	item-text="title"
					          	item-value="id"
								:label="$t('t_categories')"
								:placeholder="$t('t_choose_categories')"
								autocomplete="off"
								color="grey lighten-1"
								:rules="errors.categories"
								:error="errors.categories ? true : false"
								chips
								dense
								multiple
								>
								<template slot="selection" slot-scope="data">
									<v-chip
										color="#3da3fa" 
										text-color="white"
										:input-value="data.selected"
										@click:close="remove(data)"
										close
										small
										class="elevation-0 small-chips-close-icon"
										>
										<v-avatar>
							              	<v-icon left>check_circle</v-icon>
							            </v-avatar>
										<span class="text-uppercase font-weight-light caption">{{ data.item.title }}</span>
									</v-chip>
								</template>
							</v-autocomplete>
						</v-flex>

						<!-- Required field -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.required ? 'has-danger' : '']">
								<v-switch v-model="form.required" :false-value="0" :true-value="1" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_required_field') }}</span>
							</label>
							<small class="red--text" v-if="errors.required">{{ errors.required[0] }}</small>
						</v-flex>

						<!-- Allow searches by this field -->
						<v-flex xs12>
							<label class="form-group has-float-label" :class="[errors.searchable ? 'has-danger' : '']">
								<v-switch v-model="form.searchable" :false-value="0" :true-value="1" class="ma-0 form-group form-control" color="blue"></v-switch>
								<span>{{ $t('t_searchable') }}</span>
							</label>
							<small class="red--text" v-if="errors.searchable">{{ errors.searchable[0] }}</small>
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
			if (!app.$adgate.allow(app.$auth.user, 'edit', 'fields')) {
				redirect('/administrator')
			}

			let response   = await $axios.post('administrator/fields/options/edit', { id: params.id })
			let categories = []
			for (var index in response.data.field.categories) { 
				categories.push(response.data.field.categories[index].id)
			} 
			return {
				form: {
					id: response.data.field.unique_id,
					name: response.data.field.name,
					slug: response.data.field.slug,
					type: response.data.field.type,
					options: response.data.field.options,
					required: response.data.field.required,
					searchable: response.data.field.searchable,
					categories: categories
				},
				categories: response.data.categories
			}
		},

		data: function() {
			return {
				types: [
					{title: this.$t('t_dropdown'), value: 'dropdown'},
					{title: this.$t('t_checkbox'), value: 'checkbox'},
					{title: this.$t('t_radio'), value: 'radio'}
				],
				errors: [],
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_edit_custom_field'),
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
				if (!this.$adgate.allow(this.$auth.user, 'edit', 'fields')) {
					this.$router.push('/administrator')	
					return
				}

				this.loading = true
				this.$axios
				.post('administrator/fields/options/update', {
					id: this.form.id,
					name: this.form.name,
					slug: this.form.slug,
					type: this.form.type,
					options: this.form.options,
					categories: this.form.categories,
					required: this.form.required,
					searchable: this.form.searchable
				})
				.then(response => {
					this.errors   = []
					this.$toasted.show(this.$t('t_toasted_field_updated'), {
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

			remove (item) {
		        this.form.categories.splice(this.form.categories.indexOf(item), 1)
		        this.form.categories = [...this.form.categories]
		    },

			icon: function(icon) {
				if (icon === null) {
					return this.$homeApi('storage/app/uploads/default/category/no-icon.png')
				}else{
					return this.$homeApi('storage/app/' + icon);
				}
			}
		}
	}
</script>