<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Delete dialog -->
		<v-dialog v-model="dialogs.delete" max-width="400" persistent v-if="$mogate.allow($auth.user, 'delete', 'comments')">
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
						{{ selected.length > 1 ? $t('t_delete_comments') : $t('t_delete_comment') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

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
		<v-dialog v-model="dialogs.edit" max-width="500px" persistent v-if="$mogate.allow($auth.user, 'edit', 'comments')">
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
						<v-btn :color="$vuetify.theme.dark ? '#bfbfbf' : '#2e3131'" text @click="update()" :disabled="$striphtml(editComment.comment.comment) === editComment.newComment || editComment.newComment === ''">{{ $t('t_update') }}</v-btn>
					</client-only>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_comments') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Publish -->
					<v-fade-transition v-if="$mogate.allow($auth.user, 'approve', 'comments')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="publish()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							 </template>
				          	<span>{{ $t('t_publish') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

			        <!-- Delete -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'delete', 'comments')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="dialogs.delete = true">
									<v-icon>mdi-delete</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_delete') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Restore -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'delete', 'comments')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="restore()">
									<v-icon>mdi-delete-restore</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_restore') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				    <!-- Spam -->
			        <v-fade-transition v-if="$mogate.allow($auth.user, 'edit', 'comments')">
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon @click="spam()">
									<v-icon>mdi-alert-octagon</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_spam') }}</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="comments"
					item-key="id"
					:no-data-text="$t('t_table_no_data_available')"
      				hide-default-footer
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					>
					<!-- Author -->
					<template v-slot:item.author="{ item }">
						<div class="font-weight-black">
							{{ item.user.username }}
						</div>
					</template>

					<!-- Post -->
					<template v-slot:item.post="{ item }">
						<v-list two-line class="transparent">
							<v-list-item :to="`/listing/${item.deal.unique_slug}`" target="_blank" nuxt :ripple="false">
								<v-list-item-avatar>
									<img :src="preview(item)">
								</v-list-item-avatar>
								<v-list-item-content class="table-wrap-title">
									<v-list-item-title class="table-wrap-title" v-text="item.deal.title"></v-list-item-title>
									<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-text="item.deal.unique_id">Deals</v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Status -->
					<template v-slot:item.status="{ item }">
						<div v-if="item.deleted_at === null">
							<!-- Active -->
							<v-btn text color="#26c281" v-if="item.isActive">
								{{ $t('t_active') }}
							</v-btn>
							<!-- Pending -->
							<v-btn text color="warning" v-else-if="item.isPending">
								{{ $t('t_pending') }}
							</v-btn>
							<!-- Spam -->
							<v-btn text color="info" v-else-if="item.isSpam">
								{{ $t('t_spam') }}
							</v-btn>
						</div>
						<!-- Deleted -->
						<v-btn text color="error" v-if="item.deleted_at !== null">
							{{ $t('t_deleted') }}
						</v-btn>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<v-menu bottom :left="$vuetify.rtl ? false : true" :right="$vuetify.rtl ? true : false" origin="center center" max-height="300px">
							<template v-slot:activator="{ on }">
								<v-btn small v-on="on" icon>
									<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-dots-vertical</v-icon>
								</v-btn>
							</template>
							<v-list dense>

								<!-- Read -->
								<v-list-item @click="read(item.comment)">
									<v-list-item-action>
										<v-icon>mdi-comment-eye</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_read') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Publish -->
								<v-list-item @click="publish([item], comments.indexOf(item))" v-if="$mogate.allow($auth.user, 'approve', 'comments') && (item.isPending || item.isSpam)">
									<v-list-item-action>
										<v-icon>mdi-check-all</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_publish') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Edit -->
								<v-list-item @click="edit(item, comments.indexOf(item))" v-if="$mogate.allow($auth.user, 'edit', 'comments')">
									<v-list-item-action>
										<v-icon>mdi-square-edit-outline</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_edit') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Delete -->
								<v-list-item @click="remove([item], comments.indexOf(item))" v-if="$mogate.allow($auth.user, 'delete', 'comments') && item.deleted_at === null">
									<v-list-item-action>
										<v-icon>mdi-delete</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_delete') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Restore -->
								<v-list-item @click="restore([item], comments.indexOf(item))" v-if="$mogate.allow($auth.user, 'delete', 'comments') && item.deleted_at !== null">
									<v-list-item-action>
										<v-icon>mdi-delete-restore</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_restore') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>

								<!-- Spam -->
								<v-list-item @click="spam([item], comments.indexOf(item))" v-if="$mogate.allow($auth.user, 'edit', 'comments')">
									<v-list-item-action>
										<v-icon>mdi-alert-octagon</v-icon>
									</v-list-item-action>
									<v-list-item-content>
										<v-list-item-title>{{ $t('t_spam') }}</v-list-item-title>
									</v-list-item-content>
								</v-list-item>
							</v-list>
						</v-menu>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="commentsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
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
			if (!app.$mogate.allow(app.$auth.user, 'access', 'comments')) {
				redirect('/moderator')
			}

			let response = await $axios.get('moderator/comments')
			return {
				commentsData: response.data,
				comments: response.data.data
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
				title: this.$t('t_comments'),
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
		          	{ text: this.$t('t_author'), value: 'author', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_post'), value: 'post', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
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
				.get('moderator/comments?page=' + page)
				.then(response => {
					this.selected     = []
					this.commentsData = response.data
					this.comments     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading      = false
				})
			},

		   	preview: function(post) {
		   		if (post.deal.photosNumber === 0) {
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png')
		        }else{
		            return this.$homeApi('storage/app/uploads/classifieds/' + post.deal.unique_id + '/preview_0.jpg')
		        }
		   	},

		   	title: function(post) {
		   		if (post.deal !== null) {
		   			return post.deal.title
		   		}else if (post.product !== null) {
		   			return post.product.title
		   		}
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
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'edit', 'comments')) {
					return
				}

		   		this.loading = true
		   		this.$axios
		   		.post('moderator/comments/options/update', {
		   			id: this.editComment.comment.id,
		   			comment: this.editComment.newComment
		   		})
		   		.then(response => {
		   			this.comments[this.editComment.index].comment = this.editComment.newComment
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

		   	publish: function(comments = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'approve', 'comments')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/comments/options/publish', {
					comments: comments
				})
				.then(response => {
					if (index !== null) {
						this.comments[index].isPending  = false
						this.comments[index].isActive   = true
						this.comments[index].isSpam     = false
						this.comments[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedComment, selectedIndex) {
							vm.comments.forEach(function(value, ind) {
								if (selectedComment.unique_id === value.unique_id) {
									vm.comments[ind].isSpam     = false
									vm.comments[ind].isActive   = true
									vm.comments[ind].isPending  = false
									vm.comments[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_comments_activated'), {
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

		   	remove: function(comments = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'delete', 'comments')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/comments/options/delete', {
					comments: comments
				})
				.then(response => {
					if (index !== null) {
						this.comments[index].isPending  = false
						this.comments[index].isActive   = false
						this.comments[index].isSpam     = false
						this.comments[index].deleted_at = true
					}else{
						vm.selected.forEach(function (selectedComment, selectedIndex) {
							vm.comments.forEach(function(value, ind) {
								if (selectedComment.unique_id === value.unique_id) {
									vm.comments[ind].isSpam     = false
									vm.comments[ind].isActive   = false
									vm.comments[ind].isPending  = false
									vm.comments[ind].deleted_at = true
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
			},

		   	restore: function(comments = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'delete', 'comments')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/comments/options/restore', {
					comments: comments
				})
				.then(response => {
					if (index !== null) {
						this.comments[index].isPending  = false
						this.comments[index].isActive   = true
						this.comments[index].isSpam     = false
						this.comments[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedComment, selectedIndex) {
							vm.comments.forEach(function(value, ind) {
								if (selectedComment.unique_id === value.unique_id) {
									vm.comments[ind].isSpam     = false
									vm.comments[ind].isActive   = true
									vm.comments[ind].isPending  = false
									vm.comments[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_comments_restored'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
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

		   	spam: function(comments = this.selected, index = null) {
				// Check if allowed
				if (!this.$mogate.allow(this.$auth.user, 'edit', 'comments')) {
					return
				}

				let vm     = this
				vm.loading = true
				this.$axios
				.post('moderator/comments/options/spam', {
					comments: comments
				})
				.then(response => {
					if (index !== null) {
						this.comments[index].isPending  = false
						this.comments[index].isActive   = false
						this.comments[index].isSpam     = true
						this.comments[index].deleted_at = null
					}else{
						vm.selected.forEach(function (selectedComment, selectedIndex) {
							vm.comments.forEach(function(value, ind) {
								if (selectedComment.unique_id === value.unique_id) {
									vm.comments[ind].isSpam     = true
									vm.comments[ind].isActive   = false
									vm.comments[ind].isPending  = false
									vm.comments[ind].deleted_at = null
								}
							})
						})
					}
					vm.$toasted.show(this.$t('t_toasted_comments_marked_spam'), {
						icon: 'done_all',
						className: this.$vuetify.rtl ? 'toasted-rtl' : ''
					})
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