<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'deals')">
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="error">mdi-alert-octagon-outline</v-icon>
					</div>
					<div class="mb-4 text-center headline font-weight-black text-uppercase">
						{{$t('t_are_you_sure') }}
					</div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="dialogs.delete = false">
						{{ $t('t_cancel') }}
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_deals') : $t('t_delete_deal') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_pending_reported_deals') }}</v-toolbar-title>
					<v-spacer></v-spacer>

				    <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'deals')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Archive -->
			        <v-fade-transition>
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="read()">
									<v-icon>mdi-eye</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_mark_as_read') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="reports"
					item-key="id"
      				hide-default-footer
					:loading="loading"
					show-select
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					:mobile-breakpoint="1"
					>
					<!-- Pictures -->
					<template v-slot:item.preview="{ item }">
						<v-avatar size="36px">
							<img :src="preview(item.deal)">
						</v-avatar>
					</template>

					<!-- Title -->
					<template v-slot:item.title="{ item }">
						<nuxt-link :to="'/listing/' + item.deal.unique_slug" target="_blank" class="table-wrap-title">{{ item.deal.title }}</nuxt-link>
						<small class="pb-1 font-weight-regular text-uppercase d-block">{{ item.deal.user.username }}</small>
					</template>

					<!-- Price -->
					<template v-slot:item.price="{ item }">
						<div v-if="item.deal.price === null && item.deal.currency === null && item.deal.locale === null"></div>
						<div v-else>{{ $getCurrency(item.deal.price, item.deal.currency, item.deal.locale) }}</div>
					</template>

					<!-- Reporter -->
					<template v-slot:item.reporter="{ item }">{{ item.reporter.username }}</template>

					<!-- status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deal.deleted_at === null">
							<!-- Featured -->
							<v-btn text color="#19b5fe" v-if="item.deal.isUpgraded && !item.deal.isPending && !item.deal.isArchived">
								<span v-if="item.deal.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span>
								<span v-if="item.deal.upgradedTo === 'highlight'">{{ $t('t_highlight') }}</span>
								<span v-if="item.deal.upgradedTo === 'featured'">{{ $t('t_featured') }}</span>
							</v-btn>
							<!-- Active -->
							<v-btn text color="#2ecc71" v-else-if="item.deal.isActive">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="warning" v-else-if="item.deal.isArchived">
								{{ $t('t_archived') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deal.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.deal.created_at) }}</template>

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
								<v-list-item :to="'/administrator/deals/options/edit/' + item.deal.unique_id" v-if="$adgate.allow($auth.user, 'edit', 'deals')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item v-if="item.deal.deleted_at === null && $adgate.allow($auth.user, 'delete', 'deals')" @click="remove([item], reports.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Mark as read -->
								<v-list-item @click="read([item], reports.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-eye</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_mark_as_read') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

							</v-list>
						</v-menu>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="reportsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
		      	</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/moderator',

		middleware: 'moderator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$mogate.allow(app.$auth.user, 'access', 'deals')) {
				redirect('/moderator')
			}

			let response = await $axios.get('moderator/reports/deals')
			return {
				reportsData: response.data,
				reports: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
		        dialogs: {
		        	delete: false
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_reported_deals'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
        },
        
        computed: {
            headers() {
                return [
		          	{ text: this.$t('t_preview'), value: 'preview', sortable: false, align: 'center'},
		          	{ text: this.$t('t_title'), value: 'title', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_reporter'), value: 'reporter', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ]
            }
        },

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('administrator/reports/deals?page=' + page)
				.then(response => {
					this.selected    = []
					this.reportsData = response.data
					this.reports     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading   = false
				})
			},

			read: function(reports = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/reports/deals/options/read', {
					reports: reports
				})
				.then(response => {
					if (index !== null) {
                        this.reports.splice(index, 1);
					}else{
						vm.selected.forEach(function (selectedReport, selectedIndex) {
							vm.reports.forEach(function(value, ind) {
								if (selectedReport.unique_id === value.unique_id) {
                                    vm.reports.splice(ind, 1);
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_reports_read'), {
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

			remove: function(reports = this.selected, index = null) {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'deals')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/reports/deals/options/delete', {
					reports: reports
				})
				.then(response => {
					if (index !== null) {
						this.reports[index].deal.isPending  = false
						this.reports[index].deal.isActive   = false
						this.reports[index].deal.isArchived = false
						this.reports[index].deal.deleted_at = true
					}else{
						vm.selected.forEach(function (selectedReport, selectedIndex) {
							vm.deals.forEach(function(value, ind) {
								if (selectedReport.unique_id === value.unique_id) {
									vm.reports[ind].deal.isArchived = false
									vm.reports[ind].deal.isActive   = false
									vm.reports[ind].deal.isPending  = false
									vm.reports[ind].deal.deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_deals_deleted'), {
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

		   	preview: function(deal) {
		   		if (deal.photosNumber == 0) {
            
		            // get default image
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');

		        }else{

		            // get first image
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg');

		        }
		   	},
		}
  	}
</script>