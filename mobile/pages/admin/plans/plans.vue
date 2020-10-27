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

		<!-- Features dialog -->
		<v-dialog v-model="dialogs.features" max-width="500px" persistent v-if="$owgate.allow($auth.user, 'access', 'plans')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_plan_features') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.features = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
					
					<div class="text-center pt-4" style="position:absolute">
						<small v-if="choosed.isRecommended" class="recommended font-weight-light px-1 yellow text-uppercase">Most popular</small>
					</div>
					<v-card-title primary-title class="layout justify-center">
						<div class="title text-center font-weight-black">{{ choosed.title }}</div>
					</v-card-title>
					<v-card-text class="text-center">
						<div v-if="choosed.price === null">
							<span class="display-1 ml-1">0</span>
						</div>

						<div v-else>
							<div>
								<span class="caption font-weight-medium grey--text">{{ $currencySymbol(choosed.currency, choosed.locale) }}</span>
								<span class="display-1 ml-1 font-weight-bold">{{ choosed.price }}</span>
								<span class="caption ml-1 grey--text">/{{ choosed.frequency }}</span>
							</div>
						</div>
						<div class="body-1 grey--text">
							<span>{{ choosed.subtitle }}</span><br/>
							<br/>
						</div>

						<v-avatar tile size="50" color="white" class="mb-5">
				          	<img  v-if="choosed.icon !== null" :src="icon(choosed.icon)">
				        </v-avatar>

						<v-list dense class="text-xs-left transparent">

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isStores ? 'blue lighten-1' : 'grey'">{{ choosed.isStores ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Create shops</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>You can create up to {{ choosed.stores_limit }} shops</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isOnlineShopping ? 'blue lighten-1' : 'grey'">{{ choosed.isOnlineShopping ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Online shopping system</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>Sell your products online, you can add up to {{ choosed.products_limit }} monthly</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isWorkingHours ? 'blue lighten-1' : 'grey'">{{ choosed.isWorkingHours ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Business working hours</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>You can determine when your stores are open and when they are close</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isCoupons ? 'blue lighten-1' : 'grey'">{{ choosed.isCoupons ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Coupons, Gift card deals</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>You can create coupons and share them to get more sells</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isTeams ? 'blue lighten-1' : 'grey'">{{ choosed.isTeams ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Multiple store managers</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>You can invite other users to be a part of your stores</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isCustomTheme ? 'blue lighten-1' : 'grey'">{{ choosed.isCustomTheme ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Shops themes customization</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>You have the ability to choose a custom theme for your stores</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isCustomWatermark ? 'blue lighten-1' : 'grey'">{{ choosed.isCustomWatermark ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Watermarks for product photos</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>You can add logo or stickers to your products photos as watermark</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

							<v-list-item>
								<v-list-item-avatar>
									<v-icon :color="choosed.isAutoshare ? 'blue lighten-1' : 'grey'">{{ choosed.isAutoshare ? 'check' : 'close' }}</v-icon>
								</v-list-item-avatar>
								<v-list-item-content>
									<span class="body-1">Social networks auto post</span>                                           
								</v-list-item-content>

								<v-list-item-action>
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn icon text ripple color="blue lighten-1" v-on="on">
												<v-icon class="mdi mdi-information-variant"></v-icon>
											</v-btn>
										</template>
					              		<span>Auto share a product after create it on your social media accounts (Facebook, Twitter, Telegram, Instagram or Vk)</span>
					              	</v-tooltip>
					            </v-list-item-action>
							</v-list-item>

						</v-list>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<v-btn :color="$vuetify.theme.dark ? '#bfbfbf' : '#2e3131'" text :to="'/administrator/membership/plans/edit/' + choosed.unique_id">Edit</v-btn>
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
							<v-btn to="/administrator/membership/plans/create" v-on="on" text icon>
								<v-icon>add</v-icon>
							</v-btn>
						</template>
			          	<span>{{ $t('t_create') }}</span>
			        </v-tooltip>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="plans"
					item-key="id"
      				hide-default-footer
					:mobile-breakpoint="1"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Icon -->
					<template v-slot:item.icon="{ item }">
						<v-avatar size="36px">
							<img :src="icon(item.icon)">
						</v-avatar>
					</template>

					<!-- Title -->
					<template v-slot:item.title="{ item }">
						{{ item.title }}
					</template>

					<!-- Price -->
					<template v-slot:item.price="{ item }">
						<div v-if="item.price === null && item.currency === null && item.locale === null">Free</div>
						<div v-else>{{ $getCurrency(item.price, item.currency, item.locale) }}</div>
					</template>

					<!-- Frequency -->
					<template v-slot:item.frequency="{ item }">{{ frequency(item.frequency) }}</template>

					<!-- Features -->
					<template v-slot:item.features="{ item }">
						<v-tooltip top>
							<template v-slot:activator="{ on }">
								<v-btn text icon color="grey" v-on="on" @click="features(item)">
									<v-icon>mdi-eye</v-icon>
								</v-btn>
							</template>
							<span>{{ $t('t_show_plan_features') }}</span>
						</v-tooltip>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<!-- Active -->
						<v-btn text color="#26c281" v-if="item.deleted_at === null">
							{{ $t('t_active') }}
						</v-btn>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
							<v-btn small v-on="on" icon>
								<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-dots-vertical</v-icon>
							</v-btn>
							</template>
							<v-list dense>

								<!-- Edit -->
								<v-list-item :to="'/administrator/membership/plans/edit/' + item.unique_id" v-if="$owgate.allow($auth.user, 'edit', 'plans')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item @click="remove([item], plans.indexOf(item))" v-if="item.deleted_at === null && $owgate.allow($auth.user, 'delete', 'plans')">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item @click="restore([item], plans.indexOf(item))" v-if="item.deleted_at !== null && $owgate.allow($auth.user, 'delete', 'plans')">
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
		      	<pagination :data="plansData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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

			let response = await $axios.get('administrator/membership/plans')
			return {
				plansData: response.data,
				plans: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_icon'), value: 'icon', sortable: false, align: 'center'},
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_frequency'), value: 'frequency', sortable: false, align: 'center'},
		          	{ text: this.$t('t_features'), value: 'features', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	delete: false,
		        	features: false
		        },
		        choosed: {},
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_plans'),
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
				.get('administrator/membership/plans?page=' + page)
				.then(response => {
					this.selected  = []
					this.plansData = response.data
					this.plans     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

		   	icon: function(icon) {
				if (icon === null) {
					return this.$homeApi('storage/app/uploads/default/plan/no-image.jpg')
				}else{
					return this.$homeApi('storage/app/' + icon)
				}
			},

			features: function(plan) {
				this.choosed          = plan
				this.dialogs.features = true
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
			},

			frequency(frequency) {
				switch (frequency) {
					case 'daily':
						return this.$t('t_daily')
						break;
					case 'weekly':
						return this.$t('t_weekly')
						break;
					case 'monthly':
						return this.$t('t_monthly')
						break;
					case 'yearly':
						return this.$t('t_yearly')
						break;
				
					default:
						break;
				}
			}
		}
  	}
</script>