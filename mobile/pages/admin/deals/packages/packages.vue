<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$owgate.allow($auth.user, 'delete', 'plans')">
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
						{{ selected.length > 1 ? $t('t_delete_plans') : $t('t_delete_plan') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_packages') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Delete -->
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'delete', 'plans')">
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
			        <v-fade-transition v-if="$owgate.allow($auth.user, 'delete', 'plans')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

					<!-- Create -->
			        <v-tooltip top v-if="$owgate.allow($auth.user, 'create', 'plans')">
						<template v-slot:activator="{ on }">
							<v-btn to="/administrator/deals/packages/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="packages"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Title -->
					<template v-slot:item.title="{ item }">
						<v-list two-line class="transparent">
							<v-list-item>
								<v-list-item-content class="table-wrap-title">
									<v-list-item-title class="table-wrap-title pb-2" v-html="item.title"></v-list-item-title>
									<v-list-item-subtitle class="pb-1 text-uppercase text-small font-weight-regular d-block" v-html="item.slug"></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Price -->
					<template v-slot:item.price="{ item }">
						<span class="font-weight-bold">{{ $getCurrency(item.price, item.currency, item.locale) }}</span>
					</template>

					<!-- Discount -->
					<template v-slot:item.discount="{ item }">
						<div v-if="item.discount" class="font-weight-black">
							{{ item.discount }} %
						</div>
					</template>

					<!-- Type -->
					<template v-slot:item.type="{ item }">
						<!-- Featured -->
						<v-btn text color="#44add5" v-if="item.type === 'featured'">
							{{ $t('t_featured') }}
						</v-btn>

						<!-- Urgent -->
						<v-btn text color="#f85050" v-if="item.type === 'urgent'">
							{{ $t('t_urgent') }}
						</v-btn>

						<!-- Highlight -->
						<v-btn text color="#fcc62c" v-if="item.type === 'highlight'">
							{{ $t('t_highlight') }}
						</v-btn>
					</template>

					<!-- Days -->
					<template v-slot:item.days="{ item }">
						<div class="font-weight-black">{{ item.days | numeralFormat }}</div>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="20px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Edit -->
								<v-list-item :to="'/administrator/deals/packages/edit/' + item.unique_id" v-if="$owgate.allow($auth.user, 'edit', 'plans')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item @click="remove([item], packages.indexOf(item))" v-if="item.deleted_at === null && $owgate.allow($auth.user, 'delete', 'plans')">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item @click="restore([item], packages.indexOf(item))" v-if="item.deleted_at !== null && $owgate.allow($auth.user, 'delete', 'plans')">
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
		      	<pagination :data="packagesData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$owgate.allow(app.$auth.user, 'access', 'plans')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/deals/packages')
			return {
				packagesData: response.data,
				packages: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_discount'), value: 'discount', sortable: false, align: 'center'},
		          	{ text: this.$t('t_type'), value: 'type', sortable: false, align: 'center'},
		          	{ text: this.$t('t_head_days'), value: 'days', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_packages'),
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
				.get('administrator/deals/packages?page=' + page)
				.then(response => {
					this.selected  = []
					this.packagesData = response.data
					this.packages     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			remove: function(plans = this.selected, index = null) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'delete', 'plans')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/membership/plans/options/delete', {
					plans: plans
				})
				.then(response => {
					if (index !== null) {
						this.plans[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedPlan, selectedIndex) {
							vm.plans.forEach(function(value, ind) {
								if (selectedPlan.unique_id === value.unique_id) {
									vm.plans[ind].deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_plans_deleted'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					vm.dialogs.delete = false
					vm.loading        = false
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

			restore: function(plans = this.selected, index = null) {
				// Not allowed?
				if (!this.$owgate.allow(this.$auth.user, 'delete', 'plans')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/membership/plans/options/restore', {
					plans: plans
				})
				.then(response => {
					if (index !== null) {
						this.plans[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedPlan, selectedIndex) {
							vm.plans.forEach(function(value, ind) {
								if (selectedPlan.unique_id === value.unique_id) {
									vm.plans[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_plans_restored'), {
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
			}
		}
  	}
</script>