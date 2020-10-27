<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$adgate.allow($auth.user, 'delete', 'comments')">
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
						Cancel
					</v-btn>
					<v-btn color="error" text @click="remove()">
						{{ selected.length > 1 ? $t('t_delete_comments') : $t('t_delete_comment') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<!-- Read dialog -->
		<v-dialog v-model="dialogs.read" max-width="500px" v-if="$adgate.allow($auth.user, 'access', 'comments')">
			<v-card class="pa-3">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_read_comment') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.read = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text v-html="readComment"></v-card-text>
			</v-card>
		</v-dialog>

		<!-- Edit dialog -->
		<v-dialog v-model="dialogs.edit" max-width="500px" persistent v-if="$adgate.allow($auth.user, 'edit', 'comments')">
			<v-card class="pa-2">
				<v-card-title primary-title class="pt-1">
					<h3 class="body-2 mb-0 text-uppercase font-weight-black">{{ $t('t_edit_comment') }}</h3>
					<v-spacer></v-spacer>
					<v-btn icon @click="dialogs.edit = false">
						<v-icon>mdi-close</v-icon>
					</v-btn>
				</v-card-title>
				<v-card-text>
					<v-container grid-list-md class="pa-0">
						<v-layout wrap>
							<v-flex xs12>
								<v-textarea
									v-model="editComment.newComment"
									color="grey lighten-1"
									:label="$t('t_comment')"
									:placeholder="$t('t_enter_comment')"
									counter="500"
									maxlength="500"
									no-resize
									required
									></v-textarea>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card-text>
				<v-card-actions class="pa-2">
					<v-spacer></v-spacer>
					<client-only>
						<v-btn color="#2e3131" text @click="update()" :disabled="$striphtml(editComment.comment.comment) === editComment.newComment || editComment.newComment === ''">{{ $t('t_update') }}</v-btn>
					</client-only>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_pending_reported_comments') }}</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition v-if="$adgate.allow($auth.user, 'delete', 'comments')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
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
					>
					<!-- Reporter -->
					<template v-slot:item.author="{ item }">
						{{ item.reporter.username }}
					</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.comment.deleted_at === null">
							<!-- Active -->
							<v-btn text color="#26c281" v-if="item.comment.isActive">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="warning" v-else-if="item.comment.isPending">
								{{ $t('t_pending') }}
							</v-btn>
							<!-- Spam -->
							<v-btn text color="info" v-else-if="item.comment.isSpam">
								{{ $t('t_spam') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.comment.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.comment.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Read -->
								<v-list-item @click="read(item.comment.comment)" v-if="$adgate.allow($auth.user, 'access', 'comments')">
									<v-list-item-action>
										<v-icon>mdi-comment-eye</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_read') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Edit -->
								<v-list-item @click="edit(item.comment, reports.indexOf(item))" v-if="$adgate.allow($auth.user, 'edit', 'comments')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item @click="remove([item], reports.indexOf(item))" v-if="item.deleted_at === null && $adgate.allow($auth.user, 'delete', 'comments')">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

                                <!-- Mark as read -->
								<v-list-item @click="readReport([item], reports.indexOf(item))">
									<v-list-item-action>
										<v-icon>mdi-eye</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_mark_as_read') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

                                <!-- Delete -->
								<v-list-item @click="remove([item], reports.indexOf(item))" v-if="item.comment.deleted_at === null && $adgate.allow($auth.user, 'delete', 'comments')">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
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
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'access', 'comments')) {
				redirect('/administrator')
			}

            let response = await $axios.get('administrator/reports/comments')
			return {
				reportsData: response.data,
				reports: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
		        dialogs: {
		        	delete: false,
		        	read: false,
		        	edit: false,
		        },
		        readComment: '',
		        editComment: {
		        	newComment: '',
		        	comment: {},
		        	index: null
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_reported_comments'),
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
		          	{ text: this.$t('t_reporter'), value: 'author', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
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
				.get('administrator/reports/comments?page=' + page)
				.then(response => {
					this.selected    = []
					this.reportsData = response.data
					this.reports     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading      = false
				})
			},

		   	read: function(comment) {
		   		this.readComment  = comment
		   		this.dialogs.read = true
		   	},

		   	edit: function(comment, index) {
		   		if (process.client) {
					this.editComment.comment    = comment
					this.editComment.newComment = this.$striphtml(comment.comment)
					this.editComment.index      = index
					this.dialogs.edit           = true
		   		}
		   	},

		   	update: function() {
				// Not allowed?
				if (!this.$adgate.allow(this.$auth.user, 'edit', 'comments')) {
					this.$router.push('/administrator')	
					return
				}

		   		this.loading = true
		   		this.$axios
		   		.post('administrator/comments/options/update', {
		   			id: this.editComment.comment.id,
		   			comment: this.editComment.newComment
		   		})
		   		.then(response => {
		   			this.reports[this.editComment.index].comment.comment = this.editComment.newComment
		   			this.$toasted.show(this.$t('t_toasted_comment_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.dialogs.edit = false
					this.loading      = false
		   		})
		   		.catch(error => {
		   			this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
		   			this.loading = false
		   		})
            },
               
            readReport: function(reports = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/reports/comments/options/read', {
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
				if (!this.$adgate.allow(this.$auth.user, 'delete', 'comments')) {
					this.$router.push('/administrator')	
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/reports/comments/options/delete', {
					reports: reports
				})
				.then(response => {
					if (index !== null) {
						this.reports[index].comment.isPending  = false
						this.reports[index].comment.isActive   = false
						this.reports[index].comment.isSpam     = false
						this.reports[index].comment.deleted_at = true
					}else{
						vm.selected.forEach(function (selectedReport, selectedIndex) {
							vm.reports.forEach(function(value, ind) {
								if (selectedReport.unique_id === value.unique_id) {
									vm.reports[ind].comment.isSpam     = false
									vm.reports[ind].comment.isActive   = false
									vm.reports[ind].comment.isPending  = false
									vm.reports[ind].comment.deleted_at = true
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_comments_moved_to_trash'), {
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
			}
		}
  	}
</script>