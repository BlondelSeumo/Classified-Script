<template>
	<v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<div class="m-card">
				<v-toolbar flat>
					<v-toolbar-title>{{ $t('t_followers') }}</v-toolbar-title>
				</v-toolbar>
				<v-data-table
					:headers="headers"
					:items="followers"
					item-key="id"
      				hide-default-footer
					disable-pagination
					:no-data-text="$t('t_table_no_data_available')"
					:mobile-breakpoint="1"
					>
					<!-- Shop -->
					<template v-slot:item.shop="{ item }">
						<v-list two-line class="transparent" nuxt :ripple="false">
							<v-list-item :to="'/shop/' + item.shop.store" target="_blank">
								<v-list-item-avatar>
									<img :src="logo(item.shop.logo)">
								</v-list-item-avatar>
								<v-list-item-content class="table-wrap-title">
									<v-list-item-title class="table-wrap-title" v-html="item.shop.title"></v-list-item-title>
									<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="item.shop.store"></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Follower -->
					<template v-slot:item.follower="{ item }">
						<span class="table-wrap-title font-weight-bold text-uppercase">{{ item.follower.username }}</span>
					</template>

					<!-- Followed at -->
					<template v-slot:item.followed_at="{ item }">{{ $ago(item.followed_at) }}</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="followersData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
					<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
				</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
	export default {
		layout: 'default/manager',

		middleware: 'manager',

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.get('manager/followers')
			return {
				followersData: response.data,
				followers: response.data.data
			}
		},

		data: function() {
			return {
		        loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_followers'),
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
			headers(){
				return [
					{ text: this.$t('t_following'), value: 'shop', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_follower'), value: 'follower', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_followed'), value: 'followed_at', sortable: false, align: 'center'}
				]
			}
		},

		methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('manager/followers?page=' + page)
				.then(response => {
					this.selected      = []
					this.followersData = response.data
					this.followers     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading       = false
				})
			},

		   	logo: function(logo) {
				if (logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			}
		}
  	}
</script>