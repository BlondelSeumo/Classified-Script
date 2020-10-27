<template>
  	<v-app id="inspire" :dark="theme.dark" :light="!theme.dark">
		<div class="administrator-section mobile-theme">
			
			<!-- Sidebar -->
			<v-navigation-drawer v-model="drawer" app clipped :right="$vuetify.rtl ? true : false" :left="$vuetify.rtl ? false : true">
				<v-list dense>
					<template v-for="(item, index) in sidebar">
						<v-list-group
							:key="index"
							:prepend-icon="item.icon"
							no-action
							v-if="item.enabled"
							>
							<template v-slot:activator>
								<v-list-item-content>
									<v-list-item-title v-t="item.title"></v-list-item-title>
								</v-list-item-content>
							</template>
							<template v-for="subnav in item.childs">
								<v-list-item :key="subnav.to" :to="subnav.to" exact v-if="subnav.enabled">
									<v-list-item-content>
										<v-list-item-title v-t="subnav.title"></v-list-item-title>
									</v-list-item-content>
								</v-list-item>
							</template>
						</v-list-group>
					</template>
					<v-list-item to="/administrator/maintenance" exact v-if="$owgate.allow($auth.user, 'access', 'maintenance')">
						<v-list-item-icon>
							<v-icon>mdi-power-plug</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>{{ $t('t_maintenance') }}</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</v-list>
			</v-navigation-drawer>

			<!-- Notifications -->
			<v-navigation-drawer v-model="ndrawer" app clipped :right="!$vuetify.rtl ? true : false" :left="$vuetify.rtl ? false : true">
				<v-img :aspect-ratio="16/9" :src="require('~/static/parallel.jpg')" v-if="$auth.loggedIn">
					<v-layout column fill-height class="lightbox white--text ma-0">
						<v-spacer></v-spacer>
						<v-flex shrink class="pa-3" v-if="$auth.loggedIn">
							<v-avatar size="50px" class="pb-3" @click="$router.push('/administrator/profile')">
								<img :src="avatar" alt="">
							</v-avatar>
							<div class="subheading">{{ $auth.user.username }}</div>
							<div class="body-1">{{ $auth.user.email }}</div>
							<div class="body-2 text-uppercase pt-3" @click="$router.push('/')">{{ $t('t_view_site') }}</div>
						</v-flex>
					</v-layout>
				</v-img>
				<v-list dense>
					<template v-for="n in notifications">
						<v-list-item :key="n.icon" :to="`/administrator/${n.to}`"  v-if="n.allowed">
							<v-list-item-action>
								<v-icon>mdi-{{ n.icon }}</v-icon>
							</v-list-item-action>
							<v-list-item-content>
								<v-list-item-title v-t="n.title"></v-list-item-title>
							</v-list-item-content>
							<v-list-item-action class="my-0">
								<v-btn v-if="n.count" icon disabled>
									{{ n.count }}
								</v-btn>
							</v-list-item-action>
						</v-list-item>
					</template>
				</v-list>
				<v-divider></v-divider>
				<v-list dense class="pt-0">

					<!-- Deal -->
					<v-list-item to="/administrator/deals/create" v-if="$adgate.allow(this.$auth.user, 'access', 'deals')">
						<v-list-item-action>
							<i class="v-icon mdi mdi-layers"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_deal'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

					<!-- Shop -->
					<v-list-item to="/administrator/shops/create" v-if="$adgate.allow(this.$auth.user, 'access', 'shops')">
						<v-list-item-action>
							<i class="v-icon mdi mdi-store"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_shop'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

					<!-- User -->
					<v-list-item to="/administrator/users/create" v-if="$adgate.allow(this.$auth.user, 'create', 'users')">
						<v-list-item-action>
							<i class="v-icon mdi mdi-account"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_user'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

					<!-- Category -->
					<v-list-item to="/administrator/categories/create" v-if="$adgate.allow(this.$auth.user, 'create', 'categories')">
						<v-list-item-action>
							<i class="v-icon mdi mdi-view-grid"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_category'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

					<!-- Custom field -->
					<v-list-item to="/administrator/fields/create" v-if="$adgate.allow(this.$auth.user, 'create', 'fields')">
						<v-list-item-action>
							<i class="v-icon mdi mdi-textbox"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_field'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

					<!-- Article -->
					<v-list-item to="/administrator/blog/create" v-if="$adgate.allow(this.$auth.user, 'create', 'blog')">
						<v-list-item-action>
							<i class="v-icon mdi mdi-newspaper"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_article'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

					<!-- Logout -->
					<v-list-item @click.prevent="logout()">
						<v-list-item-action>
							<i class="v-icon mdi mdi-power"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_sign_out'"></v-list-item-title>
						</v-list-item-content>
					</v-list-item>

				</v-list>
			</v-navigation-drawer>
			
			<!-- Toolbar -->
			<v-app-bar app :clipped-right="$vuetify.rtl ? true : false" :clipped-left="!$vuetify.rtl ? true : false" :color="$vuetify.theme.dark ? '#3f3f3f' : 'white'" fixed style="box-shadow: rgba(0, 0, 0, 0.04) 0px 0.2rem 0.5rem 0px !important" class="mobile-admin-header">
				<v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
				<v-toolbar-title class="mx-auto">
					<nuxt-link to="/administrator" style="height: 100%">
						<img :src="logo" :class="$vuetify.rtl ? 'pr-3' : 'pl-3'" style="margin-top:5px">
					</nuxt-link>
				</v-toolbar-title>

				<v-btn icon @click="ndrawer = !ndrawer">
					<v-icon size="22">mdi-bell</v-icon>
				</v-btn>

				
			</v-app-bar>
			
			<!-- Content -->
			<v-content>
				<nuxt/>
			</v-content>

		</div>
  	</v-app>
</template>

<script>
	export default {
		data() {
			return {
				drawer: false,
				ndrawer: false,
				menu: false,
				theme: {
                    dark: this.$cookies.get('darkMode') || false,
                },
				sidebar: [
					{icon: "home", title: 't_dashboard', enabled: true, childs: [
						{title: 't_pending_deal', to: "/administrator/pending/deals", enabled: this.$adgate.allow(this.$auth.user, 'approve', 'deals')},
						{title: 't_pending_users', to: "/administrator/pending/users", enabled: this.$adgate.allow(this.$auth.user, 'approve', 'users')},
						{title: 't_pending_shops', to: "/administrator/pending/shops", enabled: this.$adgate.allow(this.$auth.user, 'approve', 'shops')},
						{title: 't_pending_comments', to: "/administrator/pending/comments", enabled: this.$adgate.allow(this.$auth.user, 'approve', 'comments')},
						{title: 't_pending_reported_comments', to: "/administrator/reports/comments", enabled: this.$adgate.allow(this.$auth.user, 'reported', 'comments')},
						{title: 't_pending_reported_deals', to: "/administrator/reports/deals", enabled: this.$adgate.allow(this.$auth.user, 'access', 'deals')},
					]},
					{icon: "group", title: 't_users', enabled: this.$adgate.allow(this.$auth.user, 'access', 'users'), childs: [
						{title: 't_browse_all', to: "/administrator/users", enabled: this.$adgate.allow(this.$auth.user, 'access', 'users')},
						{title: 't_create', to: "/administrator/users/create", enabled: this.$adgate.allow(this.$auth.user, 'create', 'users')},
						{title: 't_roles', to: "/administrator/roles", enabled: this.$owgate.allow(this.$auth.user, 'access', 'roles')}
					]},
					{icon: "collections", title: 't_deals', enabled: this.$adgate.allow(this.$auth.user, 'access', 'deals'), childs: [
						{title: 't_browse_all', to: "/administrator/deals", enabled: this.$adgate.allow(this.$auth.user, 'access', 'deals')},
						{title: 't_create', to: "/administrator/deals/create", enabled: this.$adgate.allow(this.$auth.user, 'access', 'deals')},
						{title: 't_packages', to: "/administrator/deals/packages", enabled: this.$owgate.allow(this.$auth.user, 'access', 'plans')},
						{title: 't_payments', to: "/administrator/deals/payments", enabled: this.$owgate.allow(this.$auth.user, 'access', 'subscriptions')},
						{title: 't_offers', to: "/administrator/deals/offers", enabled: this.$adgate.allow(this.$auth.user, 'access', 'deals')}
					]},
					{icon: "format_list_bulleted", title: 't_categories', enabled: this.$adgate.allow(this.$auth.user, 'access', 'categories'), childs: [
						{title: 't_browse_all', to: "/administrator/categories", enabled: this.$adgate.allow(this.$auth.user, 'access', 'categories')},
						{title: 't_create', to: "/administrator/categories/create", enabled: this.$adgate.allow(this.$auth.user, 'create', 'categories')}
					]},
					{icon: "extension", title: 't_custom_fields', enabled: this.$adgate.allow(this.$auth.user, 'access', 'fields'), childs: [
						{title: 't_browse_all', to: "/administrator/fields", enabled: this.$adgate.allow(this.$auth.user, 'access', 'fields')},
						{title: 't_create', to: "/administrator/fields/create", enabled: this.$adgate.allow(this.$auth.user, 'create', 'fields')}
					]},
					{icon: "comment", title: 't_comments', enabled: this.$adgate.allow(this.$auth.user, 'access', 'comments'), childs: [
						{title: 't_browse_all', to: "/administrator/comments", enabled: this.$adgate.allow(this.$auth.user, 'access', 'comments')},
					]},
					{icon: "store", title: 't_shops', enabled: this.$adgate.allow(this.$auth.user, 'access', 'shops'), childs: [
						{title: 't_browse_all', to: "/administrator/shops", enabled: this.$adgate.allow(this.$auth.user, 'access', 'shops')},
						{title: 't_create', to: "/administrator/shops/create", enabled: this.$adgate.allow(this.$auth.user, 'access', 'shops')}
					]},
					{icon: "headset_mic", title: 't_conversations', enabled: this.$adgate.allow(this.$auth.user, 'access', 'conversations'), childs: [
						{title: 't_chat_rooms', to: "/administrator/conversations/chat", enabled: this.$adgate.allow(this.$auth.user, 'chat', 'conversations')},
						{title: 't_users_messages', to: "/administrator/conversations/users", enabled: this.$adgate.allow(this.$auth.user, 'users', 'conversations')},
						{title: 't_admins_messages', to: "/administrator/conversations/admin", enabled: this.$adgate.allow(this.$auth.user, 'admins', 'conversations')}
					]},
					{icon: "card_membership", title: 't_membership', enabled: this.$owgate.allow(this.$auth.user, 'access', 'plans') || this.$owgate.allow(this.$auth.user, 'access', 'subscriptions'), childs: [
						{title: 't_plans', to: "/administrator/membership/plans", enabled: this.$owgate.allow(this.$auth.user, 'access', 'plans')},
						{title: 't_subscriptions', to: "/administrator/membership/subscriptions", enabled: this.$owgate.allow(this.$auth.user, 'access', 'subscriptions')}
					]},
					{icon: "local_library", title: 't_blog', enabled: this.$adgate.allow(this.$auth.user, 'access', 'blog'), childs: [
						{title: 't_browse_all', to: "/administrator/blog", enabled: this.$adgate.allow(this.$auth.user, 'access', 'blog')},
						{title: 't_create', to: "/administrator/blog/create", enabled: this.$adgate.allow(this.$auth.user, 'create', 'blog')}
					]},
					{icon: "attach_money", title: 't_currencies', enabled: this.$owgate.allow(this.$auth.user, 'access', 'currencies'), childs: [
						{title: 't_browse_all', to: "/administrator/currencies", enabled: this.$owgate.allow(this.$auth.user, 'access', 'currencies')}
					]},
					{icon: "description", title: 't_pages', enabled: this.$adgate.allow(this.$auth.user, 'access', 'pages'), childs: [
						{title: 't_browse_all', to: "/administrator/pages", enabled: this.$adgate.allow(this.$auth.user, 'access', 'pages')},
						{title: 't_create', to: "/administrator/pages/create", enabled: this.$adgate.allow(this.$auth.user, 'create', 'pages')}
					]},
					{icon: "format_paint", title: 't_themes', enabled: this.$owgate.allow(this.$auth.user, 'access', 'themes'), childs: [
						{title: 't_browse_all', to: "/administrator/themes", enabled: this.$owgate.allow(this.$auth.user, 'access', 'themes')},
						{title: 't_request_new', to: "/administrator/themes/request", enabled: this.$owgate.allow(this.$auth.user, 'request', 'themes')}
					]},
					{icon: "public", title: 't_geolocation', enabled: this.$owgate.allow(this.$auth.user, 'access', 'geo'), childs: [
						{title: 't_countries', to: "/administrator/geolocation/countries", enabled: this.$owgate.allow(this.$auth.user, 'access', 'geo')},
						{title: 't_states', to: "/administrator/geolocation/states", enabled: this.$owgate.allow(this.$auth.user, 'access', 'geo')},
						{title: 't_cities', to: "/administrator/geolocation/cities", enabled: this.$owgate.allow(this.$auth.user, 'access', 'geo')}
					]},
					{icon: "art_track", title: 't_advertisements', enabled: this.$owgate.allow(this.$auth.user, 'access', 'advertisements'), childs: [
						{title: 't_adsense', to: "/administrator/advertisements/adsense", enabled: this.$owgate.allow(this.$auth.user, 'access', 'advertisements')},
						//{title: 't_companies', to: "/administrator/advertisements/companies", enabled: this.$owgate.allow(this.$auth.user, 'access', 'advertisements')}
					]},
					{icon: "business_center", title: 't_services', enabled: this.$owgate.allow(this.$auth.user, 'sms', 'services'), childs: [
						{title: 't_sms', to: "/administrator/services/sms", enabled: this.$owgate.allow(this.$auth.user, 'sms', 'services')},
						{title: 't_clouds', to: "/administrator/services/clouds", enabled: this.$owgate.allow(this.$auth.user, 'clouds', 'services')}
					]},
					{icon: "settings", title: 't_settings', enabled: this.$owgate.allow(this.$auth.user, 'access', 'settings'), childs: [
						{title: 't_general_settings', to: "/administrator/settings/general", enabled: this.$owgate.allow(this.$auth.user, 'general', 'settings')},
						{title: 't_home_settings', to: "/administrator/settings/home", enabled: this.$owgate.allow(this.$auth.user, 'access', 'settings')},
						{title: 't_auth_settings', to: "/administrator/settings/auth", enabled: this.$owgate.allow(this.$auth.user, 'auth', 'settings')},
						{title: 't_smtp_settings', to: "/administrator/settings/smtp", enabled: this.$owgate.allow(this.$auth.user, 'smtp', 'settings')},
						{title: 't_security_settings', to: "/administrator/settings/security", enabled: this.$owgate.allow(this.$auth.user, 'security', 'settings')},
						{title: 't_geo_settings', to: "/administrator/settings/geo", enabled: this.$owgate.allow(this.$auth.user, 'geo', 'settings')},
						{title: 't_seo_settings', to: "/administrator/settings/seo", enabled: this.$owgate.allow(this.$auth.user, 'seo', 'settings')},
						{title: 't_uploading_settings', to: "/administrator/settings/upload", enabled: this.$owgate.allow(this.$auth.user, 'upload', 'settings')},
						{title: 't_posting_settings', to: "/administrator/settings/posting", enabled: this.$owgate.allow(this.$auth.user, 'access', 'settings')},
						{title: 't_payment_gateways_settings', to: "/administrator/settings/payments", enabled: this.$owgate.allow(this.$auth.user, 'payments', 'settings')},
						{title: 't_social_settings', to: "/administrator/settings/social", enabled: this.$owgate.allow(this.$auth.user, 'social', 'settings')},
						{title: 't_watermark_settings', to: "/administrator/settings/watermark", enabled: this.$owgate.allow(this.$auth.user, 'watermark', 'settings')},
						{title: 't_footer_settings', to: "/administrator/settings/footer", enabled: this.$owgate.allow(this.$auth.user, 'footer', 'settings')},
					]},
					{icon: "contact_support", title: 't_support', enabled: this.$owgate.allow(this.$auth.user, 'access', 'support'), childs: [
						{title: 't_contact_support', to: "/administrator/support/contact", enabled: this.$owgate.allow(this.$auth.user, 'access', 'support')},
						{title: 't_support_settings', to: "/administrator/support/settings", enabled: this.$owgate.allow(this.$auth.user, 'access', 'support')}
					]}
				],
				notifications: {
					deals: {
						count: null,
						icon: 'image',
						size: '20',
						to: 'pending/deals',
						title: 't_pending_deal',
						enabled: this.$adgate.allow(this.$auth.user, 'approve', 'deals')
					},
					comments: {
						count: null,
						icon: 'message-reply',
						size: '20',
						to: 'pending/comments',
						title: 't_pending_comments',
						enabled: this.$adgate.allow(this.$auth.user, 'approve', 'comments')
					},
					reportedDeals: {
						count: null,
						icon: 'image-broken',
						size: '22',
						to: 'reports/deals',
						title: 't_pending_reported_deals',
						enabled: this.$adgate.allow(this.$auth.user, 'delete', 'deals')
					},
					reportedComments: {
						count: null,
						icon: 'comment-question',
						size: '22',
						to: 'reports/comments',
						title: 't_pending_reported_comments',
						enabled: this.$adgate.allow(this.$auth.user, 'reported', 'comments')
					},
					shops: {
						count: null,
						icon: 'store',
						size: '22',
						to: 'pending/shops',
						title: 't_pending_shops',
						enabled: this.$adgate.allow(this.$auth.user, 'approve', 'shops')
					},
					users: {
						count: null,
						icon: 'account',
						size: '22',
						to: 'pending/users',
						title: 't_pending_users',
						enabled: this.$adgate.allow(this.$auth.user, 'approve', 'users')
					},
					dphn: {
						count: null,
						icon: 'image-auto-adjust',
						size: '22',
						to: 'deals/payments',
						title: 't_deals_payments',
						enabled: this.$owgate.allow(this.$auth.user, 'access', 'subscriptions')
					}
				},

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

		mounted() {
			this.$axios
			.post('administrator/ajax/fetch/notifications')
			.then(response => {
				this.notifications.deals.count            = response.data.deals
				this.notifications.comments.count         = response.data.comments
				this.notifications.reportedComments.count = response.data.reportedComments
				this.notifications.reportedDeals.count    = response.data.reportedDeals
				this.notifications.shops.count            = response.data.shops
				this.notifications.users.count            = response.data.users

				if (response.data.dphn) {
					this.notifications.dphn.count    = response.data.dphn				
				}
			})
		}
	}
</script>

<style>
	.administrator-section .theme--light.v-navigation-drawer:not(.v-navigation-drawer--floating) .v-navigation-drawer__border {
	    background-color: rgba(100, 100, 100, 0.11) !important
	}
	.no-action-style .v-list__group__items--no-action .v-list__tile {
		padding:0 16px !important
	}
	.last-item-admin .v-list__tile {
		padding: 0 8px
	}
	.administrator-section .mobile-admin-header .v-toolbar__content {
		height: 56px !important;
    	padding: 0 20px;
	}
</style>