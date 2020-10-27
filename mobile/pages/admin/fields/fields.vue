<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'fields')">
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="error">mdi-alert-octagon-outline</v-icon>
					</div>
					<div class="mb-4 text-center headline font-weight-black text-uppercase">
						{{ $t('t_are_you_sure') }}
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="dialogs.delete = false">
						{{ $t('t_cancel') }}
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_fields') : $t('t_delete_field') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Field options dialog -->
		<v-dialog v-model="dialogs.options" max-width="400" persistent v-if="$adgate.allow($auth.user, 'access', 'fields')">
			<v-card class="py-2">
				<v-card-text>
					<!-- Dropdown -->
					<div v-if="field.type === 'dropdown'">
						<v-flex xs12 class="pt-4">
							<v-select
								autocomplete="off"
								color="grey lighten-1"
					          	:items="field.options.split(',')"
					          	:placeholder="field.name"
					          	:label="field.name"
					          	dense
					        ></v-select>
						</v-flex>
					</div>

					<!-- Checkbox -->
					<div v-if="field.type === 'checkbox'">
						<p>{{ field.name }}</p>
						<v-checkbox
							v-for="option in field.options.split(',')"
							:key="option"
							:label="option"
							color="grey darken-3"
							:value="option"
							hide-details
							></v-checkbox>
					</div>

					<!-- Radio -->
					<div v-if="field.type === 'radio'">
						<p>{{ field.name }}</p>
						<v-radio-group :mandatory="false">
							<v-radio v-for="option in field.options.split(',')" :key="option" :label="option" :value="option" color="grey darken-3"></v-radio>
						</v-radio-group>
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="dialogs.options = false">
						{{ $t('t_cancel') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_custom_fields') }}</v-toolbar-title>
					<v-spacer></v-spacer>

				    <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'fields')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Restore -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'fields')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create custom field -->
			        <v-tooltip top v-if="$adgate.allow($auth.user, 'create', 'fields')">
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/fields/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="fields"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>

					<!-- Name -->
					<template v-slot:item.name="{ item }">
						<div class="table-wrap-title">{{ item.name }}</div>
						<small class="pb-1 font-weight-regular text-uppercase d-block">{{ item.slug }}</small>
					</template>

					<!-- Categories -->
					<template v-slot:item.categories="{ item }">
						<v-btn text icon color="grey darken-3">
							<v-icon size="22px">mdi-format-list-bulleted-type</v-icon>
						</v-btn>
					</template>

					<!-- Field type -->
					<template v-slot:item.type="{ item }">{{ type(item.type) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-btn text icon color="grey darken-3" @click="options(item)">
							<v-icon size="20px">mdi-tune</v-icon>
						</v-btn>
					</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Active -->
							<v-btn text color="#2ecc71">
								{{ $t('t_active') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Options -->
					<template v-slot:item.actions="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" icon>
									<v-icon size="20px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Edit -->
								<v-list-item :to="'/administrator/fields/edit/' + item.unique_id" v-if="$adgate.allow($auth.user, 'edit', 'fields')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item v-if="item.deleted_at === null && $adgate.allow($auth.user, 'delete', 'fields')" @click="remove([item], fields.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item v-if="item.deleted_at !== null && $adgate.allow($auth.user, 'delete', 'fields')" @click="restore([item], fields.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete-restore</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_restore') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

							</v-list>
						</v-menu>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="fieldsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$adgate.allow(app.$auth.user, 'access', 'fields')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/fields')
			return {
				fieldsData: response.data,
				fields: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_name'), value: 'name', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_categories'), value: 'categories', sortable: false, align: 'center'},
		          	{ text: this.$t('t_type'), value: 'type', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_field_options'), value: 'options', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'actions', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        	options: false
		        },
		        field: {},
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_custom_fields'),
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
				.get('administrator/fields?page=' + page)
				.then(response => {
					this.selected   = []
					this.fieldsData = response.data
					this.fields     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading    = false
				})
			},

			remove: function(fields = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'fields')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/fields/options/delete', {
					fields: fields
				})
				.then(response => {
					if (index !== null) {
						this.fields[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedField, selectedIndex) {
							vm.fields.forEach(function(value, ind) {
								if (selectedField.unique_id === value.unique_id) {
									vm.fields[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_fields_deleted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			restore: function(fields = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'fields')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/fields/options/restore', {
					fields: fields
				})
				.then(response => {
					if (index !== null) {
						this.fields[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedField, selectedIndex) {
							vm.fields.forEach(function(value, ind) {
								if (selectedField.unique_id === value.unique_id) {
									vm.fields[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_fields_restored'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
				.catch(error => {
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.loading = false
				})
			},

			options: function(field) {
				this.field           = field
				this.dialogs.options = true
			},

			type(type) {
				switch (type) {
					case 'dropdown':
						return this.$t('t_dropdown')
						break;
					case 'checkbox':
						return this.$t('t_checkbox')
						break;
					case 'radio':
						return this.$t('t_radio')
						break;
				
					default:
						return this.$t('t_dropdown')
						break;
				}
			}
		}
  	}
</script>