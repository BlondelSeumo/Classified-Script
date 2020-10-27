<template>
  	<v-app id="inspire">
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
				</v-list>
			</v-navigation-drawer>

			<!-- Notifications -->
			<v-navigation-drawer v-model="ndrawer" app clipped :right="!$vuetify.rtl ? true : false" :left="$vuetify.rtl ? false : true">
				<v-img :aspect-ratio="16/9" :src="require('~/static/parallel.jpg')" v-if="$auth.loggedIn">
					<v-layout column fill-height class="lightbox white--text ma-0">
						<v-spacer></v-spacer>
						<v-flex shrink class="pa-3" v-if="$auth.loggedIn">
							<v-avatar size="50px" class="pb-3" @click="$router.push('/moderator/profile')">
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
						<v-list-item :key="n.icon" :to="`/moderator/${n.to}`" v-if="n.allowed">
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
					<v-list-item to="/create">
						<v-list-item-action>
							<i class="v-icon mdi mdi-layers"></i>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title v-t="'t_create_deal'"></v-list-item-title>
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
					<nuxt-link to="/moderator" style="height: 100%">
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