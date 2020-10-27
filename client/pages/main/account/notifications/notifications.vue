<template>
	<v-app id="inspire">
		<v-dialog v-model="loading" persistent width="300">
			<v-card color="black" dark>
				<v-card-text>
					Please stand by
					<v-progress-linear
						indeterminate
						color="white"
						class="mb-0"
						></v-progress-linear>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>

      				<!-- Account sidebar -->
          			<v-sidebar></v-sidebar>

          			<!-- Account page -->
					<v-flex xs9>
						<v-card class="m-card" flat>
							<v-toolbar color="white" flat>
								<v-toolbar-title class="title">Notifications center</v-toolbar-title>
								<v-spacer></v-spacer>

								<!-- Accepted deals -->
								<v-btn dark depressed small color="grey darken-1">
							        <v-icon dark left small>mdi-image-multiple</v-icon>Accepted deals (10)
							    </v-btn>

							    <!-- Deleted deals -->
								<v-btn dark depressed small color="grey darken-1">
							        <v-icon dark left small>mdi-image-multiple</v-icon>Deleted deals (2)
							    </v-btn>

							</v-toolbar>

					        <v-data-table
								:headers="headers"
								:items="notifications"
								item-key="id"
				  				hide-actions
								disable-pagination
								>
								<template slot="items" slot-scope="props">

									<!-- Plan -->
									<td class="text-center font-weight-bold text-uppercase black--text">
										{{ props.item.plan.title }}
									</td>

									<!-- Price -->
									<td class="text-center font-weight-bold">
										<div v-if="props.item.price && props.item.currency && props.item.locale">
											{{ $getCurrency(props.item.price, props.item.currency, props.item.locale) }}
										</div>
									</td>

									<!-- Subscribed at -->
									<td class="text-center">{{ $ago(props.item.subscribed_at) }}</td>

									<!-- Expires -->
									<td class="text-center">{{ $dateString(props.item.expires_at) }}</td>

									<!-- Status -->
									<td class="text-center font-weight-bold">
										<!-- Expired -->
										<v-btn small depressed dark color="#ff4444" v-if="props.item.isExpired">
											Expired
										</v-btn>
										<!-- Expires soon -->
										<v-btn small depressed dark color="#warning" v-if="!props.item.isExpired && props.item.expiresSoon">
											Expire soon
										</v-btn>
										<!-- Active -->
										<v-btn small depressed dark color="#2ecc71" v-if="!props.item.isExpired && !props.item.expiresSoon">
											Active
										</v-btn>
									</td>

								</template>
							</v-data-table>

							<div class="text-center pt-2">
						      	<pagination :data="notificationsData" @pagination-change-page="getNextPage" :limit=8>
						      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">keyboard_arrow_left</i></span>
									<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_right</i></span>
						      	</pagination>
						    </div>

						</v-card>
					</v-flex>

				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	import sidebar from '~/pages/main/account/layout/account'

	export default {
		middleware: 'auth',

		layout: 'default/main',

		components: {
			'v-sidebar': sidebar
		},

		async asyncData ({ $axios }) {
			let response = await $axios.get('account/subscription')
			return {
				notificationsData: response.data,
				notifications: response.data.data
			}
		},

		data: function() {
			return {
				headers: [
		          	{ text: 'Plan', sortable: false, align: 'center'},
		          	{ text: 'Price', sortable: false, align: 'center'},
		          	{ text: 'Subscribed at', sortable: false, align: 'center'},
		          	{ text: 'Expires', sortable: false, align: 'center'},
		          	{ text: 'Status', sortable: false, align: 'center'}
		        ],
				loading: false
			}
		},

		head() {
			return {
				title: 'Notifications'
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				axios
				.get('account/subscription?page=' + page)
				.then(response => {
					this.notificationsData = response.data
					this.notifications     = response.data.data
					this.loading           = false
				})
			}
		}
	}
</script>