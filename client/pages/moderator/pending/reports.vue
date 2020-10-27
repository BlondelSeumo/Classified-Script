<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Read dialog -->
		<v-dialog v-model="dialogs.read" max-width="500px">
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
		<v-dialog v-model="dialogs.edit" max-width="500px" persistent>
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
									v-model="commentToEdit.newComment"
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
						<v-btn color="#2e3131" flat @click="updateComment()" :disabled="$striphtml(commentToEdit.comment.comment) === commentToEdit.newComment || commentToEdit.newComment === ''">{{ $t('t_update') }}</v-btn>
					</client-only>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" flat>
					<v-toolbar-title>{{ $t('t_pending_reports') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Mark as read -->
					<v-scroll-x-transition>
				        <v-tooltip top v-if="selected.length > 0">
				          	<v-btn slot="activator" flat icon color="grey darken-3" @click="mark()">
				            	<v-icon>mdi-check-all</v-icon>
				         	</v-btn>
				          	<span>{{ $t('t_mark_as_read') }}</span>
				        </v-tooltip>
				    </v-scroll-x-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="reports"
					item-key="id"
					:no-data-text="$t('t_table_no_data_available')"
      				hide-actions
					show-select
					disable-pagination
					>
					<template slot="items" slot-scope="props">
						<td>
							<v-checkbox v-model="props.selected" color="grey darken-1" hide-details></v-checkbox>
						</td>

						<!-- Reporter -->
						<td class="text-xs-left px-0">
							<v-list two-line class="transparent">
								<v-list-item>
									<v-list-item-avatar>
										<img :src="avatar(props.item.reporter.avatar)">
									</v-list-item-avatar>
									<v-list-item-content class="table-wrap-title">
										<v-list-item-title class="table-wrap-title" v-html="props.item.reporter.username"></v-list-item-title>
										<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="props.item.reporter.email"></v-list-item-subtitle>
									</v-list-item-content>
								</v-list-item>
							</v-list>
						</td>

						<!-- Post type -->
						<td class="text-center red--text text--lighten-1 font-weight-bold text-uppercase" v-if="props.item.isClassifieds">{{ $t('t_deal') }}</td>
						<td class="text-center green--text text--lighten-1 font-weight-bold text-uppercase" v-if="props.item.isComment">{{ $t('t_comment') }}</td>

						<!-- Deal -->
						<td class="text-xs-left px-0" v-if="props.item.isClassifieds">
							<v-list two-line class="transparent">
								<v-list-item :to="`/listing/${props.item.deal.unique_slug}`">
									<v-list-item-avatar>
										<img :src="preview(props.item.deal)">
									</v-list-item-avatar>
									<v-list-item-content class="table-wrap-title">
										<v-list-item-title class="table-wrap-title" v-html="props.item.deal.title"></v-list-item-title>
										<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="props.item.deal.unique_id"></v-list-item-subtitle>
									</v-list-item-content>
								</v-list-item>
							</v-list>
						</td>

						<!-- Comment -->
						<td class="text-center" v-if="props.item.isComment">
							<v-tooltip top>
								<v-btn text icon color="grey darken-3" slot="activator" @click="read(props.item.comment.comment)">
									<v-icon>mdi-message-reply-text</v-icon>
								</v-btn>
								<span>{{ $t('t_read_comment') }}</span>
							</v-tooltip>
						</td>

						<!-- Reported -->
						<td class="text-center">{{ $ago(props.item.created_at) }}</td>

						<!-- Options -->
						<td class="text-center" v-if="props.item.isClassifieds">

							<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
								<v-btn small slot="activator" icon>
									<v-icon size="18px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
								<v-list dense>

									<!-- Mark as read -->
									<v-list-item @click="mark([props.item], props.index)">
										<v-list-item-action>
											<v-icon>mdi-check-all</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_mark_as_read') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

									<!-- Edit -->
									<v-list-item :to="'/moderator/deals/options/edit/' + props.item.deal.unique_id" v-if="$mogate.allow($auth.user, 'edit', 'deals')">
										<v-list-item-action>
											<v-icon>mdi-square-edit-outline</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_edit_deal') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

								</v-list>
							</v-menu>
				        </td>
				        <td class="text-center" v-if="props.item.isComment">

							<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
								<v-btn small slot="activator" icon>
									<v-icon size="18px" color="grey darken-1">mdi-dots-vertical</v-icon>
								</v-btn>
								<v-list dense>

									<!-- Mark as read -->
									<v-list-item @click="mark([props.item], props.index)">
										<v-list-item-action>
											<v-icon>mdi-check-all</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_mark_as_read') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

									<!-- Edit -->
									<v-list-item @click="editComment(props.item.comment, props.index)" v-if="$mogate.allow($auth.user, 'edit', 'comments')">
										<v-list-item-action>
											<v-icon>mdi-square-edit-outline</v-icon>
										</v-list-item-action>
										<v-list-item-content>
											<v-list-item-title>{{ $t('t_edit_comment') }}</v-list-item-title>
										</v-list-item-content>
									</v-list-item>

								</v-list>
							</v-menu>
				        </td>

					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="reportsData" @pagination-change-page="getNextPage" :limit=8>
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_left</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_right</i></span>
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
			if (!app.$mogate.allow(app.$auth.user, 'reported', 'comments')) {
				redirect('/moderator')
			}

			let response = await $axios.get('moderator/pending/reports')
			return {
				reportsData: response.data,
				reports: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_reporter'), sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_post_type'), sortable: false, align: 'center'},
		          	{ text: this.$t('t_post'), sortable: false, align: 'center'},
		          	{ text: this.$t('t_reported'), sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), sortable: false, align: 'center'}
		        ],
		        dialogs: {
		        	read: false,
		        	edit: false
		        },
		        readComment: null,
		        commentToEdit: {
		        	newComment: '',
		        	comment: {},
		        	index: null
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_reports'),
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
				.get('moderator/pending/reports?page=' + page)
				.then(response => {
					this.selected     = []
					this.reportsData = response.data
					this.reports     = response.data.data
					this.loading      = false
				})
			},

			mark: function(reports = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/pending/reports/mark', {
					reports: reports
				})
				.then(response => {
					if (index !== null) {
						this.reports.splice(index, 1)
					}else{
						vm.selected.forEach(function (selectedReport, selectedIndex) {
							vm.reports.forEach(function(value, ind) {
								if (selectedReport.unique_id === value.unique_id) {
									vm.reports.splice(ind, 1)
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_reports_marked'), {
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

			read: function(comment) {
		   		this.readComment  = comment
		   		this.dialogs.read = true
		   	},

		   	editComment: function(comment, index) {
		   		if (process.client) {
					this.commentToEdit.comment    = comment
					this.commentToEdit.newComment = this.$striphtml(comment.comment)
					this.commentToEdit.index      = index
					this.dialogs.edit             = true
		   		}
		   	},

		   	updateComment: function() {
		   		this.loading = true
		   		this.$axios
		   		.post('moderator/comments/options/update', {
		   			id: this.commentToEdit.comment.id,
		   			comment: this.commentToEdit.newComment
		   		})
		   		.then(response => {
		   			this.reports[this.commentToEdit.index].comment.comment = this.commentToEdit.newComment
		   			this.$toasted.show(this.$t('t_toasted_comment_updated'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
					this.dialogs.edit = false
					this.loading      = false
		   		})
		   		.catch(error => {
		   			console.log(error)
		   			this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
		   			this.loading = false
		   		})
		   	},

			avatar: function(avatar) {
				if (avatar === null) {
		            return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png');
		        }else{
		            return this.$homeApi('storage/app/' + avatar);
		        }
			},

			preview: function(deal) {
				if (deal.photosNumber == 0) {
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');
		        }else{
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg');
		        }
			}
		}
	}
</script>