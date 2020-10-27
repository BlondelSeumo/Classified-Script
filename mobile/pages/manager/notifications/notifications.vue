<template>
	<v-app id="inspire">

		<v-dialog v-model="loading" persistent elevation-0 width="100" content-class="elevation-0">
			<v-card flat>
				<v-card-text class="pa-4 text-center">
                    <v-progress-circular
                        :size="45"
						width="2"
                        color="light-blue"
                        indeterminate
                    ></v-progress-circular>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>Notifications</v-toolbar-title>
					<v-spacer></v-spacer>

			        <!-- Delete -->
			        <v-fade-transition>
				        <v-tooltip top v-if="selected.length > 0">
				          	<v-btn slot="activator" flat icon @click="dialogs.delete = true">
				            	<v-icon>mdi-delete</v-icon>
				         	</v-btn>
				          	<span>Delete</span>
				        </v-tooltip>
				    </v-fade-transition>

				</v-toolbar>
				<v-data-table
					v-model="selected"
					:headers="headers"
					:items="messages"
					item-key="id"
      				hide-actions
					show-select
					disable-pagination
					>
					<template slot="items" slot-scope="props">
						<td>
							<v-checkbox v-model="props.selected" color="grey darken-1" hide-details></v-checkbox>
						</td>

						<!-- From -->
						<td class="text-center">
							<nuxt-link :to="'/administrator/users/edit/' + props.item.sender.username" target="_blank">
			            		<v-avatar size="36px">
									<img :src="avatar(props.item.sender.avatar)">
								</v-avatar>
							</nuxt-link>
						</td>

						<!-- Deal -->
						<td class="text-center product-link">
							<v-list two-line class="transparent">
								<v-list-item :to="'/listing/' + props.item.deal.unique_slug" target="_blank">
									<v-list-item-avatar>
										<img :src="preview(props.item.deal)">
									</v-list-item-avatar>
									<v-list-item-content>
										<v-list-item-title class="table-wrap-title" v-html="props.item.deal.title"></v-list-item-title>
									</v-list-item-content>
								</v-list-item>
							</v-list>
						</td>

						<!-- Message -->
						<td class="text-center">
							<v-btn text color="grey darken-3" icon @click="show(props.item, props.index)">
								<v-icon>mdi-android-messages</v-icon>
							</v-btn>
						</td>

						<!-- Status -->
						<td class="text-center">
							<!-- Not read -->
							<v-tooltip top v-if="!props.item.isRead">
								<v-btn slot="activator" flat color="grey darken-3" icon v-if="!props.item.isRead">
									<v-icon>mdi-eye-off</v-icon>
								</v-btn>
								<span>User has not seen this message yet</span>
							</v-tooltip>

							<!-- Read -->
							<v-tooltip top v-else>
								<v-btn slot="activator" flat color="#22a7f0" icon>
									<v-icon>mdi-eye</v-icon>
								</v-btn>
								<span>User has seen this message</span>
							</v-tooltip>
						</td>

						<!-- Created at -->
						<td class="text-center">{{ $ago(props.item.created_at) }}</td>

						<!-- Options -->
						<td class="text-center">
							<v-btn small icon @click="remove([props.item], props.index)">
								<v-icon size="18px" :color="$vuetify.theme.dark ? 'white' : 'grey darken-1'">mdi-delete</v-icon>
							</v-btn>
				        </td>

					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="messagesData" @pagination-change-page="getNextPage" :limit=8>
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_left</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_right</i></span>
		      	</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/manager',

		middleware: 'auth',

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.get('manager/messages')
			return {
				messagesData: response.data,
				messages: response.data.data
			}
		},

		data: function() {
			return {
				selected: [],
				headers: [
		          	{ text: 'From', sortable: false},
		          	{ text: 'Deal', sortable: false},
		          	{ text: 'Message', sortable: false},
		          	{ text: 'Status', sortable: false},
		          	{ text: 'Created', sortable: false},
		          	{ text: 'Options', sortable: false}
		        ],
		        dialogs: {
		        	delete: false,
		        	read: false
		        },
		        message: {
		        	index: null,
		        	content: null
		        },
		        loading: false
			}
		},

		head() {
			return {
				title: 'Notification center',
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
				.get('manager/messages?page=' + page)
				.then(response => {
					this.selected  = []
					this.messagesData = response.data
					this.messages     = response.data.data
					this.loading   = false
				})
			},

		   	avatar: function(avatar) {
				if (avatar === null) {
		   			return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
		   		}else{
		   			return this.$homeApi('storage/app/' + avatar)
		   		}
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

		   	show: function(message, index) {
				this.message.index   = index
				this.message.content = message.message
				this.dialogs.read    = true
		   	},

		   	remove: function(messages = this.selected, index = null) {
				let vm     = this
				vm.loading = true
				this.$axios
				.post('manager/messages/options/delete', {
					messages: messages
				})
				.then(response => {
					if (index !== null) {
						this.messages.splice(index, 1)
					}else{
						vm.selected.forEach(function (selectedMessage, selectedIndex) {
							vm.messages.forEach(function(value, ind) {
								if (selectedMessage.unique_id === value.unique_id) {
									vm.messages.splice(ind, 1)
								}
							})
						})
					}
					vm.$toasted.show(`Selected ${messages.length > 1 ? 'messages' : 'message'} has been successfully deleted permanently`, {
						icon: 'done_all'
					})
					vm.dialogs.delete = false
					vm.loading        = false
				})
				.catch(error => {
					console.log(error)
					vm.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
						icon: 'error',
						action: null
					})
					vm.loading = false
				})
			}
		}
  	}
</script>