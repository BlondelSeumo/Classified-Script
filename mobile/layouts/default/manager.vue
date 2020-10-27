<template>
	<v-app id="inspire" class="administrator-section mobile-theme">

		<!-- Sidebar -->
		<v-navigation-drawer v-model="drawer" app clipped :right="$vuetify.rtl ? true : false" :left="$vuetify.rtl ? false : true" class="manager-side">
			<v-list dense>

				<template v-for="(item, index) in sidebar">
					<v-list-item :to="item.to" :key="index" exact nuxt active-class="active-manager-list primary--text">
						<v-list-item-icon>
							<v-icon>mdi-{{ item.icon }}</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title v-t="item.text"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</template>
				
				<v-divider dark class="my-3"></v-divider>

				<v-subheader>{{ $t('t_create') }}</v-subheader>

				<!-- Create new deal -->
				<v-list-item to="/manager/deals/create" exact nuxt active-class="active-manager-list primary--text">
					<v-list-item-icon>
						<v-icon>mdi-plus</v-icon>
					</v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title v-t="'t_create_deal'"></v-list-item-title>
					</v-list-item-content>
					<v-list-item-action v-if="$store.state.manager.deals_left === 0">
						<v-btn small ripple text class="small-badge px-2" color="error">
							{{ $t('t_no_deals_left') }}
						</v-btn>
					</v-list-item-action>
					<v-list-item-action v-else-if="$store.state.manager.deals_left">
						<v-btn small ripple text class="small-badge" color="error">
							{{ $t('t_deals_left', { number:  $store.state.manager.deals_left}) }}
						</v-btn>
					</v-list-item-action>
					<v-list-item-action v-else-if="dealsLeft === 0">
						<v-btn small ripple text class="small-badge px-2" color="error">
							{{ $t('t_no_deals_left') }}
						</v-btn>
					</v-list-item-action>
					<v-list-item-action v-else-if="dealsLeft">
						<v-btn small ripple text class="small-badge" color="error">
							{{ $t('t_deals_left', { number:  dealsLeft}) }}
						</v-btn>
					</v-list-item-action>
				</v-list-item>

				<!-- Create shops -->
				<v-list-item to="/manager/shops/create" exact nuxt active-class="active-manager-list primary--text">
					<v-list-item-icon>
						<v-icon>mdi-plus</v-icon>
					</v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title v-t="'t_create_shop'"></v-list-item-title>
					</v-list-item-content>
					<v-list-item-action v-if="$store.state.manager.shops_left === 0">
						<v-btn small ripple text class="small-badge px-2" color="error">
							{{ $t('t_no_shops_left') }}
						</v-btn>
					</v-list-item-action>
					<v-list-item-action v-else-if="$store.state.manager.shops_left">
						<v-btn small ripple text class="small-badge" color="error">
							{{ $t('t_shops_left', { number:  $store.state.manager.shops_left}) }}
						</v-btn>
					</v-list-item-action>
					<v-list-item-action v-else-if="storesLeft === 0">
						<v-btn small ripple text class="small-badge px-2" color="error">
							{{ $t('t_no_shops_left') }}
						</v-btn>
					</v-list-item-action>
					<v-list-item-action v-else-if="storesLeft">
						<v-btn small ripple text class="small-badge" color="error">
							{{ $t('t_shops_left', { number:  storesLeft}) }}
						</v-btn>
					</v-list-item-action>
				</v-list-item>

				<v-divider dark class="my-3"></v-divider>
				<v-subheader>{{ $t('t_settings') }}</v-subheader>

				<!-- General settings -->
				<v-list-item to="/manager/settings/general" exact nuxt active-class="active-manager-list primary--text">
					<v-list-item-icon>
						<v-icon>mdi-settings</v-icon>
					</v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title v-t="'t_general_settings'"></v-list-item-title>
					</v-list-item-content>
				</v-list-item>

				<!-- Autoshare settings -->
				<v-list-item to="/manager/settings/autoshare" exact nuxt active-class="active-manager-list primary--text">
					<v-list-item-icon>
						<v-icon>mdi-folder-account</v-icon>
					</v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title v-t="'t_autoshare_settings'"></v-list-item-title>
					</v-list-item-content>
				</v-list-item>

				<!-- Membership -->
				<v-list-item to="/manager/settings/subscription" exact nuxt active-class="active-manager-list primary--text">
					<v-list-item-icon>
						<v-icon>mdi-wallet-membership</v-icon>
					</v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title v-t="'t_my_subscriptions'"></v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>

		<!-- Navbar -->
		<v-app-bar :clipped-right="$vuetify.rtl ? true : false" app :clipped-left="!$vuetify.rtl ? true : false" :color="$vuetify.theme.dark ? '#1e1e1e' : 'light-blue'" dark fixed flat class="manager-header">
			<v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
			
			<v-toolbar-title class="align-center" :class="$vuetify.rtl ? 'ml-12' : 'mr-12'">
				<nuxt-link to="/manager" style="height: 100%">
					<img :src="logo" :class="$vuetify.rtl ? 'pr-3' : 'pl-3'" style="margin-top:5px">
				</nuxt-link>
      		</v-toolbar-title>

			<v-spacer></v-spacer>

			<!-- Notifications -->
			<v-tooltip bottom>
				<template v-slot:activator="{ on }">
					<v-btn text icon color="white" v-on="on">
						<v-icon>mdi-bell</v-icon>
					</v-btn>
				</template>
				<span>{{ $t('t_notification_center') }}</span>
			</v-tooltip>

			<!-- View site -->
			<v-tooltip bottom>
				<template v-slot:activator="{ on }">
					<v-btn text icon color="white" v-on="on" @click="$router.push('/')">
						<v-icon>mdi-home-import-outline</v-icon>
					</v-btn>
				</template>
				<span>{{ $t('t_view_site') }}</span>
			</v-tooltip>
		</v-app-bar>

		<!-- Content -->
		<v-content >
			<nuxt/>
		</v-content>
	</v-app>
</template>
<script>
	export default {

	  	data() {
			return {
				drawer: null,
				sidebar: [
					{icon: 'view-dashboard', to: '/manager', text: 't_my_dashboard'},
					{icon: 'image', to: '/manager/deals', text: 't_my_deals'},
					{icon: 'store', to: '/manager/shops', text: 't_my_shops'},
					{icon: 'message', to: '/manager/messages', text: 't_message_center'},
					{icon: 'bell', to: '/manager/notifications', text: 't_notification_center'},
					{icon: 'account-multiple', to: '/manager/followers', text: 't_followers'},
				],
				storesLeft: null,
				dealsLeft: null,
			}
		},
		 
	  	computed: {
			logo: function() {
                if (this.$store.state.settings.logo.white !== null) {
                    return this.$homeApi('storage/app/' + this.$store.state.settings.logo.white)
                }else{
                    return this.$homeApi('storage/app/uploads/default/settings/logo/white.png')
                }
            },

            avatar: function() {
                if (this.$auth.user.avatar !== null) {
                    return this.$homeApi('storage/app/' + this.$auth.user.avatar)
                }else{
                    return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
                }
            },
		},

		methods: {
			async logout () {
		      	// Log out the user.
		      	await this.$auth.logout()

		      	// Redirect to Home.
		      	this.$router.push({ name: 'mainIndex' })
		    },
		},

		created() {
			this.$axios
			.post('manager/info')
			.then(response => {
				this.storesLeft = response.data.storesLeft
				this.dealsLeft  = response.data.dealsLeft
			})
			.catch(error => {
				console.log(error)
			})
		}
	}
</script>

<style>
	.manager-side .v-list-item__action {
		margin: 5px 0
	}
	.active-manager-list:before, .active-manager-list:hover:before, .active-manager-list:focus:before {
		opacity: 0 !important;
	}
</style>