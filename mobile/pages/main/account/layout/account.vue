<template>
	<v-flex xs3 class="user-area">
		<v-navigation-drawer
			width="auto"
			class="m-card pb-3"
			style="height: auto;"
			>
			<v-list class="user-sidebar" dense>
				<template v-for="(item, index) in items">
					<v-divider v-if="item.divider" :key="item.to"></v-divider>
					<nuxt-link :key="index" v-if="!item.divider" :to="item.to" tag="v-list-item" active-class="active-item">
						<v-list-item >
							<v-list-item-action>
								<v-icon class="v-icon theme--light mdi" :class="item.icon"></v-icon>
							</v-list-item-action>
							<v-list-item-title v-t="item.title"></v-list-item-title>
						</v-list-item>
					</nuxt-link>
				</template>

				<!-- Sign out -->
				<v-list-item @click.prevent="logout()" class="user-signout">
					<v-list-item-action>
						<v-icon class="v-icon theme--light mdi mdi-logout"></v-icon>
					</v-list-item-action>
					<v-list-item-title>{{ $t('t_sign_out') }}</v-list-item-title>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>
	</v-flex>
</template>

<script>
	export default {
		name: 'sidebar',
		
		middleware: 'auth',

		layout: 'default/main',

		data: function() {
			return {
				items: [
			        {icon: 'mdi-settings', title: 't_account_settings', to: '/account/settings', enabled : true},
			        {icon: 'mdi-image-multiple', title: 't_my_deals', to: '/account/deals', enabled : true},
			        {icon: 'mdi-message', title: 't_message_center', to: '/account/messages', enabled : this.$megate.allow(this.$auth.user, 'receive', 'messages')},
			        {icon: 'mdi-tag', title: 't_received_offers', to: '/account/offers', enabled : this.$megate.allow(this.$auth.user, 'receive', 'offers')},
			        {icon: 'mdi-folder-search', title: 't_saved_search', to: '/account/search', enabled: this.$megate.allow(this.$auth.user, 'save', 'search')},
			        {icon: 'mdi-store', title: 't_following_shops', to: '/account/following', enabled: this.$megate.allow(this.$auth.user, 'follow', 'shops')},
			        {icon: 'mdi-wallet-membership', title: 't_membership', to: '/account/subscription', enabled: true},
			        {divider: true},
			        {icon: 'mdi-two-factor-authentication', title: 't_two_factor_auth', to: '/account/2fa', enabled: true}
			    ]
			}
		},

		methods: {
			async logout () {
		      	// Log out the user.
		      	await this.$auth.logout()

		      	// Redirect to Home.
		      	this.$router.push({ name: 'mainIndex' })
		    },
		}
	}
</script>

<style>
	.user-area .v-navigation-drawer__border {
	    background-color: rgba(0,0,0, 0) !important
	}
	.user-signout .v-list__tile {
		padding: 0 30px
	}
</style>

<style scoped>
	.active-item {
	    background: rgba(0,0,0,.04);
	}
  	.v-navigation-drawer {
    	transition: none !important;
  	}
	.user-sidebar .v-list__tile {
		padding: 0 7px
	}
	.user-signout {
		padding: 0 34px;
	}
  	.lightbox {
    	box-shadow: 0 0 20px inset rgba(0, 0, 0, 0.2);
        background-image: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 72px);
  	}
</style>