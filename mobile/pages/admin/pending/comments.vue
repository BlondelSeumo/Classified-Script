<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<!-- Read comment -->
		<v-dialog v-model="read.dialog" max-width="400">
			<v-card class="py-2">
				<v-card-text>
					<div class="text-center mb-5">
						<v-icon size="100px" color="grey darken-1">mdi-comment-text-multiple-outline</v-icon>
					</div>
					<div class="mb-4 text-center block-1 font-weight-black" v-html="read.text"></div>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="grey darken-1" text @click="read.dialog = false">
						{{ $t('t_cancel') }}
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_pending_comments') }}</v-toolbar-title>
					<v-spacer></v-spacer>

					<!-- Publish selected comments -->
					<v-scroll-x-transition>
				        <v-tooltip top v-if="selected.length > 0">
							<template v-slot:activator="{ on }">
								<v-btn v-on="on" text icon :color="$vuetify.theme.dark ? 'white' : 'grey darken-3'" @click="publish()">
									<v-icon>mdi-check-all</v-icon>
								</v-btn>
							</template>
				          	<span>{{ $t('t_activate') }}</span>
				        </v-tooltip>
				    </v-scroll-x-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="comments"
					item-key="id"
      				hide-default-footer
					show-select
					disable-pagination
					:mobile-breakpoint="1"
					:no-data-text="$t('t_table_no_data_available')"
					>
					<!-- Author -->
					<template v-slot:item.author="{ item }">
						<v-list two-line class="transparent">
							<v-list-item>
								<v-list-item-avatar>
									<img :src="avatar(item.user.avatar)">
								</v-list-item-avatar>
								<v-list-item-content class="table-wrap-title">
									<v-list-item-title class="table-wrap-title" v-html="item.user.username"></v-list-item-title>
									<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="item.user.email"></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Post type -->
					<template v-slot:item.type="{ item }">
						<!-- Deal -->
						<v-btn text color="#2ecc71" v-if="item.isDeal">
							{{ $t('t_deal') }}
						</v-btn>
						<!-- Article -->
						<v-btn text color="#95a5a6" v-if="item.isArticle">
							{{ $t('t_article') }}
						</v-btn>
					</template>

					<!-- Created at -->
					<template v-slot:item.created="{ item }">{{ $ago(item.created_at) }}</template>

					<!-- Options -->
					<template v-slot:item.options="{ item }">
						<!-- Publish -->
						<v-btn small icon @click.prevent="publish([item], comments.indexOf(item))">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-check</v-icon>
						</v-btn>

						<!-- Read -->
						<v-btn small icon @click.prevent="readComment(item.comment)">
							<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-eye</v-icon>
						</v-btn>
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
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			// Check if allowed
			if (!app.$adgate.allow(app.$auth.user, 'approve', 'comments')) {
				redirect('/administrator')
			}

			let response = await $axios.get('administrator/pending/comments')
			return {
				commentsData: response.data,
				comments: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: this.$t('t_author'), value: 'author', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_post_type'), value: 'type', sortable: false, align: 'center'},
		          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
		          	{ text: this.$t('t_options'), value: 'options', sortable: false, align: 'center'}
		        ],
		        read: {
		        	text: '',
		        	dialog: false
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_pending_comments'),
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
				.get('administrator/pending/comments?page=' + page)
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

			publish: function(comments = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('administrator/comments/options/publish', {
					comments: comments
				})
				.then(response => {
					if (index !== null) {
						this.$delete(this.comments, index)
					}else{
						vm.selected.forEach(function (selectedComments, selectedIndex) {
							vm.comments.forEach(function(value, index) {
								if (selectedComments.unique_id === value.unique_id) {
									vm.$delete(vm.comments, index)
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

			readComment: function(comment) {
				this.read.text   = comment
				this.read.dialog = true
			},

			avatar: function(avatar) {
				if (avatar === null) {
		            return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png');
		        }else{
		            return this.$homeApi('storage/app/' + avatar);
		        }
			}
		}
  	}
</script>