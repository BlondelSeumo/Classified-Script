<template>
  	<v-app id="inspire" class="administrator-section">

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
			</v-list>
		</v-navigation-drawer>

  		<!-- Toolbar -->
		<v-app-bar app :clipped-right="$vuetify.rtl ? true : false" :clipped-left="!$vuetify.rtl ? true : false" color="white" fixed style="box-shadow: rgba(0, 0, 0, 0.04) 0px 0.2rem 0.5rem 0px !important" class="admin-header">
			<v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
			<nuxt-link to="/moderator" style="height: 100%">
        		<img :src="logo" :class="$vuetify.rtl ? 'pr-3' : 'pl-3'" style="margin-top:5px">
        	</nuxt-link>

			<v-spacer></v-spacer>

			<!-- Pending -->
			<template v-for="(n, index) in notifications">
				<v-badge v-if="n.allowed" :key="index" :left="$vuetify.rtl" :right="!$vuetify.rtl" color="error" class="custom-badge" :class="!$vuetify.rtl ? 'mr-3' : 'ml-3'">
			      	<template v-slot:badge v-if="n.count">
			        	<span>{{ n.count }}</span>
			      	</template>
					<v-tooltip bottom>
						<template v-slot:activator="{ on }">
							<v-btn icon v-on="on" :to="`/moderator/${n.to}`" color="grey darken-3" text>
								<v-icon :size="`${n.size}px`">mdi-{{ n.icon }}</v-icon>
							</v-btn>
						</template>
						<span v-t="n.title"></span>
					</v-tooltip>
				</v-badge>
			</template>

			<v-menu fixed nudge-bottom="50" v-model="menu" :close-on-content-click="false" :nudge-width="200" bottom  :right="$vuetify.rtl" :left="!$vuetify.rtl">
				<template v-slot:activator="{ on }">
					<v-avatar v-on="on" size="36px" :class="$vuetify.rtl ? 'mr-5' : 'ml-5'" class="cursor-pointer">
						<img :src="avatar">
					</v-avatar>
				</template>
			   	<v-card>
			      	<v-list style="background-color:#04a3ff">
			         	<v-list-item>
							<v-list-item-avatar @click="menu = false, $router.push('/moderator/profile')" class="cursor-pointer">
								<img :src="avatar">        	
							</v-list-item-avatar>
			            	<v-list-item-content>
			               		<v-list-item-title class="white--text">{{ $auth.user.username }}</v-list-item-title>
			               		<v-list-item-subtitle class="text-uppercase font-weight-light white--text" style="font-size: 10px !important;letter-spacing: 3px;">{{ $auth.user.role.name }}</v-list-item-subtitle>
			            	</v-list-item-content>
			            	<v-list-item-action>
			            		<v-tooltip :left="!$vuetify.rtl" :right="$vuetify.rtl">
									<template v-slot:activator="{ on }">
			               				<v-btn to="/" v-on="on" color="white" icon text><v-icon small>mdi-desktop-mac</v-icon></v-btn>
			               			</template>
									<span>{{ $t('t_view_site') }}</span>
			          	 		</v-tooltip>
			            	</v-list-item-action>
			         	</v-list-item>
			      	</v-list>
			      	<v-divider></v-divider>
			      	<v-list dense class="pt-0">

			      		<!-- Deal -->
						<v-list-item to="/moderator/deals/create" active-class="black--text">
							<v-list-item-action style="min-width: 35px">
								<i class="v-icon mdi mdi-layers theme--light"></i>
							</v-list-item-action>
							<v-list-item-content>
								<v-list-item-title>{{ $t('t_create_deal') }}</v-list-item-title>
							</v-list-item-content>
						</v-list-item>

			      		<!-- Logout -->
						<v-list-item @click.prevent="logout()">
							<v-list-item-action style="min-width: 35px">
								<i class="v-icon mdi mdi-power theme--light"></i>
							</v-list-item-action>
							<v-list-item-content>
								<v-list-item-title>{{ $t('t_sign_out') }}</v-list-item-title>
							</v-list-item-content>
						</v-list-item>

					</v-list>
			   	</v-card>
			</v-menu>
		</v-app-bar>

		
		
		<!-- Content -->
		<v-content>
			<nuxt/>
		</v-content>
  	</v-app>
</template>

<script>
	export default {
		data() {
			return {
				drawer: true,
				menu: false,
				sidebar: [
					{icon: "home", title: this.$t('t_dashboard'), enabled: true, childs: [
						{title: this.$t('t_pending_deal'), to: "/moderator/pending/deals", enabled: this.$mogate.allow(this.$auth.user, 'approve', 'deals')},
						{title: this.$t('t_pending_shops'), to: "/moderator/pending/shops", enabled: this.$mogate.allow(this.$auth.user, 'approve', 'shops')},
						{title: this.$t('t_pending_comments'), to: "/moderator/pending/comments", enabled: this.$mogate.allow(this.$auth.user, 'approve', 'comments')},
						{title: 't_pending_reported_comments', to: "/moderator/reports/comments", enabled: this.$mogate.allow(this.$auth.user, 'reported', 'comments')},
						{title: 't_pending_reported_deals', to: "/moderator/reports/deals", enabled: this.$mogate.allow(this.$auth.user, 'access', 'deals')},
					]},
					{icon: "collections", title: this.$t('t_deals'), enabled: this.$mogate.allow(this.$auth.user, 'access', 'deals'), childs: [
						{title: this.$t('t_browse_all'), to: "/moderator/deals", enabled: this.$mogate.allow(this.$auth.user, 'access', 'deals')}
					]},
					{icon: "store", title: this.$t('t_shops'), enabled: this.$mogate.allow(this.$auth.user, 'access', 'shops'), childs: [
						{title: this.$t('t_browse_all'), to: "/moderator/shops", enabled: this.$mogate.allow(this.$auth.user, 'access', 'shops')}
					]},
					{icon: "comment", title: this.$t('t_comments'), enabled: this.$mogate.allow(this.$auth.user, 'access', 'comments'), childs: [
						{title: this.$t('t_browse_all'), to: "/moderator/comments", enabled: this.$mogate.allow(this.$auth.user, 'access', 'comments')},
					]}
				],
				notifications: {
					deals: {
						count: null,
						icon: 'image',
						size: '20',
						to: 'pending/deals',
						title: this.$t('t_pending_deal'),
						allowed: this.$mogate.allow(this.$auth.user, 'approve', 'deals')
					},
					comments: {
						count: null,
						icon: 'message-reply',
						size: '20',
						to: 'pending/comments',
						title: this.$t('t_pending_shops'),
						allowed: this.$mogate.allow(this.$auth.user, 'approve', 'comments')
					},
					reportedDeals: {
						count: null,
						icon: 'image-broken',
						size: '22',
						to: 'reports/deals',
						title: 't_pending_reported_deals',
						allowed: this.$mogate.allow(this.$auth.user, 'access', 'deals')
					},
					reportedComments: {
						count: null,
						icon: 'comment-question',
						size: '22',
						to: 'reports/comments',
						title: 't_pending_reported_comments',
						allowed: this.$mogate.allow(this.$auth.user, 'reported', 'comments')
					},
					shops: {
						count: null,
						icon: 'store',
						size: '22',
						to: 'pending/shops',
						title: this.$t('t_pending_comments'),
						allowed: this.$mogate.allow(this.$auth.user, 'approve', 'shops')
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
			.post('moderator/ajax/fetch/notifications')
			.then(response => {
				this.notifications.deals.count            = response.data.deals,
				this.notifications.comments.count         = response.data.comments,
				this.notifications.reportedComments.count = response.data.reportedComments
				this.notifications.reportedDeals.count    = response.data.reportedDeals
				this.notifications.shops.count            = response.data.shops
			})
		}
	}
</script>

<style>
	.administrator-section .theme--light.v-navigation-drawer:not(.v-navigation-drawer--floating) .v-navigation-drawer__border {
	    background-color: rgba(0, 0, 0, 0);
	}
	.no-action-style .v-list__group__items--no-action .v-list__tile {
		padding:0 16px !important
	}
	.last-item-admin .v-list__tile {
		padding: 0 8px
	}
	.administrator-section .v-toolbar__content {
		height: 64px !important
	}
</style>