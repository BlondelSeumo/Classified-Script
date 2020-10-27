<template>
    <v-app id="inspire" class="homepage">

    	<!-- layout loading -->
        <v-overlay v-model="loading.progress" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

        <!-- User navigation -->
        <v-navigation-drawer v-model="dialogs.navigation" fixed temporary :right="!$vuetify.rtl" :left="$vuetify.rtl" v-if="$auth.loggedIn" width="320px">
            <template v-slot:prepend>
                <v-list-item two-line>
                    <v-list-item-avatar>
                        <img :src="avatar">
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title class="navigator-username">{{ $auth.user.username }}</v-list-item-title>
                        <v-list-item-subtitle class="greetingTime">{{ $getGreetingTime($auth.user.username) }}</v-list-item-subtitle>
                    </v-list-item-content>
                    <v-list-item-action v-if="$isAdmin()">
                        <v-tooltip :right="$vuetify.rtl" :left="!$vuetify.rtl">
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" to="/administrator" nuxt>
                                    <v-icon color="#868686">mdi-view-dashboard</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ $t('t_dashboard') }}</span>
                        </v-tooltip>
                    </v-list-item-action>
                    <v-list-item-action v-else-if="$isManager()">
                        <v-tooltip :right="$vuetify.rtl" :left="!$vuetify.rtl">
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" to="/manager" nuxt>
                                    <v-icon color="#868686">mdi-view-dashboard</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ $t('t_dashboard') }}</span>
                        </v-tooltip>
                    </v-list-item-action>
                    <v-list-item-action v-else-if="$isModerator()">
                        <v-tooltip :right="$vuetify.rtl" :left="!$vuetify.rtl">
                            <template v-slot:activator="{ on }">
                                <v-btn icon v-on="on" to="/moderator" nuxt>
                                    <v-icon color="#868686">mdi-view-dashboard</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ $t('t_dashboard') }}</span>
                        </v-tooltip>
                    </v-list-item-action>
                </v-list-item>
            </template>
            <v-divider></v-divider>
            <v-list dense rounded class="pa-3">
                <template v-for="link in loops.navigation">
                    <v-list-item :key="link.to" :to="link.to" v-if="!link.disabled">
                        <v-list-item-icon>
                            <v-icon>{{ link.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-t="link.title"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
                <!-- Sign out -->
                <v-list-item @click.prevent="logout">
                    <v-list-item-icon>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title v-t="'t_sign_out'"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <!-- Header navbar -->
        <v-toolbar :color="index ? 'transparent' : 'white'" absolute flat class="py-6 px-12" :class="index ? '' : 'main-navbar'" style="height: auto">
        
            <!-- Logo -->
            <v-toolbar-title>
                <nuxt-link to="/"><img :src="logo"></nuxt-link>
            </v-toolbar-title>
        
            <v-spacer></v-spacer>

            <!-- Translate -->
            <v-dialog v-model="dialogs.language" scrollable max-width="300px">
                <template v-slot:activator="{ on }">
                    <v-btn text icon v-on="on" :color="index ? 'white' : 'grey darken-3'">
                        <v-icon>mdi-translate</v-icon>
                    </v-btn>
                </template>
                <v-card color="white">
                    <v-card-title>
                        <span class="font-weight-bold">{{ $t('t_select_your_lang') }}</span>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="dialogs.language = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text style="height: 300px;" class="translate-languages">
                        <v-container fluid grid-list-xl class="pa-0">
                            <v-layout wrap>
                                <v-flex xs12 v-for="(lang, index) in languages" :key="index" class="pb-0">
                                    <v-list :class="$store.state.lang.locale === lang.code ? 'grey lighten-4' : ''">
                                        <v-list-item @click.prevent="setLocale(lang.code), dialogs.language = false">
                                            <v-list-item-avatar size="25px">
                                                <img :src="$homeApi('public/images/flags/large/' + lang.icon)">
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title class="language-item">{{ lang.name }}</v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <!-- Search -->
            <v-search></v-search>

            <!-- Apps -->
            <v-dialog v-model="dialogs.apps" scrollable max-width="250px">
                <template v-slot:activator="{ on }">
                    <v-btn text icon v-on="on" :color="index ? 'white' : 'grey darken-3'">
                        <v-icon>mdi-apps</v-icon>
                    </v-btn>
                </template>
                <v-card color="white">
                    <v-card-text class="translate-languages">
                        <v-container fluid grid-list-xl class="pb-0">
                            <v-layout wrap>
                                <v-flex xs12 v-for="(app, index) in loops.apps" :key="index" class="pb-0">
                                    <v-list>
                                        <v-list-item :to="app.to" @click="dialogs.apps = false">
                                            <v-list-item-avatar size="20px" tile>
                                                <img :src="$homeApi(`public/images/apps/${app.icon}.svg`)">
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title class="language-item" v-t="app.title"></v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <!-- User account -->
            <v-chip v-if="$auth.loggedIn" link pill color="transparent" @click="dialogs.navigation = !dialogs.navigation" class="account-chip no-box-shadow" :class="$vuetify.rtl ? 'mr-2' : 'ml-2'">
                <v-avatar left>
                    <v-img :src="avatar" :alt="$auth.user.username"></v-img>
                </v-avatar>
                <span class="username" :class="index ? 'white--text' : 'grey--text text--darken-4'">{{ $auth.user.username }}</span>
            </v-chip>
            
            <!-- Sing in -->
            <v-btn v-else :to="`/auth/login?redirect=${this.$router.currentRoute.fullPath}`" text icon :color="index ? 'white' : 'grey darken-3'">
                <v-icon>mdi-lock</v-icon>
            </v-btn>
        
        </v-toolbar>

        <!-- Parallax -->
        <v-parallax :style="'background-image:url(' + $homeApi('storage/app/uploads/settings/wallpaper/' + this.$store.state.settings.home.wallpaper) + '.jpg)'" height="600" v-if="index">
            <v-layout
                align-center
                column
                justify-center
                >
                <div class="display-3 text-center text-uppercase font-weight-black px-12" v-html="this.$store.state.settings.home.text"></div>
            </v-layout>
        </v-parallax>

        <!-- Content -->
        <div>
            <nuxt/>
        </div>

        <!-- Footer -->
        <v-footer height="auto" class="pa-0">
            <v-card flat class="white black--text text-center pa-12" style="width:100%">
                <v-layout row>

                    <v-flex xs3 class="mb-4">
                        <h5>{{ this.$store.state.settings.footer.config.rows.first }}</h5>
                        <v-list>
                            <template v-for="(page,index) in this.$store.state.settings.footer.firstColumn">
                                <v-list-item v-if="page.isPageLink" :href="page.pageLink" target="_blank" :key="index" class="mb-2">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-else :key="index" class="mb-2" :to="`/page/${page.pageSlug}`">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-list>
                    </v-flex>

                    <v-flex xs3 class="mb-4">
                        <h5>{{ this.$store.state.settings.footer.config.rows.second }}</h5>
                        <v-list>
                            <template v-for="(page,index) in this.$store.state.settings.footer.secondColumn">
                                <v-list-item v-if="page.isPageLink" :href="page.pageLink" target="_blank" :key="index" class="mb-2">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-else :key="index" class="mb-2" :to="`/page/${page.pageSlug}`">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-list>
                    </v-flex>

                    <v-flex xs3 class="mb-4">
                        <h5>{{ this.$store.state.settings.footer.config.rows.third }}</h5>
                        <v-list>
                            <template v-for="(page,index) in this.$store.state.settings.footer.thirdColumn">
                                <v-list-item v-if="page.isPageLink" :href="page.pageLink" target="_blank" :key="index" class="mb-2">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-else :key="index" class="mb-2" :to="`/page/${page.pageSlug}`">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-list>
                    </v-flex>

                    <v-flex xs3 class="mb-4">
                        <h5>{{ this.$store.state.settings.footer.config.rows.fourth }}</h5>
                        <v-list>
                            <template v-for="(page,index) in this.$store.state.settings.footer.fourthColumn">
                                <v-list-item v-if="page.isPageLink" :href="page.pageLink" target="_blank" :key="index" class="mb-2">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-else :key="index" class="mb-2" :to="`/page/${page.pageSlug}`">
                                    <v-list-item-content>
                                        <v-list-item-title class="text-center">{{ page.pageTitle }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-list>
                    </v-flex>

                </v-layout>
                <v-divider class="mx-5" style="border-color: rgba(0, 0, 0, 0.03);"></v-divider>
                <v-card-text class="mt-4 copyrights text-uppercase font-weight-light">
                    <div v-html="this.$store.state.settings.footer.config.copyrights"></div>
                    <div class="mt-4 follow-btn">

                        <!-- Facebook -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.facebook" fixed>
                            <template v-slot:activator="{ on }">
                                <v-btn v-on="on" :href="$store.state.settings.footer.config.links.facebook" target="_blank" text class="mx-1 mb-2" icon color="#3B5998">
                                    <v-icon size="20px">mdi-facebook</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_facebook'"></span>
                        </v-tooltip>

                        <!-- Twitter -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.twitter">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.twitter" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#1DA1F2">
                                    <v-icon size="20px">mdi-twitter</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_twitter'"></span>
                        </v-tooltip>

                        <!-- Google -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.google">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.google" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#DC4E41">
                                    <v-icon size="20px">mdi-google-plus</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_google'"></span>
                        </v-tooltip>

                        <!-- Instagram -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.instagram">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.instagram" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#E4405F">
                                    <v-icon size="20px">mdi-instagram</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_instagram'"></span>
                        </v-tooltip>

                        <!-- Linkedin -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.linkedin">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.linkedin" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#0077B5">
                                    <v-icon size="20px">mdi-linkedin</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_linkedin'"></span>
                        </v-tooltip>

                        <!-- Vk -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.vk">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.vk" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#6383A8">
                                    <v-icon size="20px">mdi-vk</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_vk'"></span>
                        </v-tooltip>

                        <!-- Tumblr -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.tumblr">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.tumblr" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#36465D">
                                    <v-icon size="20px">mdi-tumblr</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_tumblr'"></span>
                        </v-tooltip>

                        <!-- Youtube -->
                        <v-tooltip top v-if="this.$store.state.settings.footer.config.links.youtube">
                            <template v-slot:activator="{ on }">
                                <v-btn :href="$store.state.settings.footer.config.links.youtube" target="_blank" v-on="on" text class="mx-1 mb-2" icon color="#FF0000">
                                    <v-icon size="20px">mdi-youtube</v-icon>
                                </v-btn>
                            </template>
                            <span v-t="'t_follow_us_youtube'"></span>
                        </v-tooltip>
                    </div>
                </v-card-text>
            </v-card>
            <client-only>
                <cookie-law theme="base--rounded" :buttonText="$t('t_cookies_accept')">
                    <div slot="message" v-t="'t_cookie_message'"></div>
                </cookie-law>
            </client-only>
        </v-footer>

    </v-app>
</template>

<script>
    import CookieLaw from 'vue-cookie-law'
    import Search from '~/components/main/Search'
    import { loadMessages } from '~/plugins/i18n'

    export default {

    	components: {
            "cookie-law": CookieLaw,
            "v-search": Search
        },

	  	data: function() {
	  		return {
                loading: {
                	progress: false
                },
                dialogs: {
                    navigation: false,
                    apps: false,
                    language: false,
                },
                languages: [
                    {name: 'English', code: 'en', icon: 'US.png'},
                    {name: 'Française', code: 'fr', icon: 'FR.png'},
                    {name: 'العربية', code: 'ar', icon: 'SA.png'},
                ],
                loops: {
                    navigation: [
                        {to: "/create", title: 't_post_new', icon: "mdi-plus"},
                        {to: "/account/deals", title: 't_my_deals', icon: "mdi-image-multiple"},
                        {to: "/account/settings", title: 't_account_settings', icon: "mdi-settings"},
                        {to: "/account/notifications", title: 't_notification_center', icon: "mdi-bell-ring"},
                        {to: "/account/messages", title: 't_message_center', icon: "mdi-email", disabled: this.$auth.loggedIn &&  !this.$megate.allow(this.$auth.user, 'receive', 'messages')},
                        {to: "/chat", title: 't_live_chat', icon: "mdi-headset"},
                        {to: "/account/offers", title: 't_received_offers', icon: "mdi-tag-text-outline", disabled: this.$auth.loggedIn &&  !this.$megate.allow(this.$auth.user, 'receive', 'offers')},
                        {to: "/account/search", title: 't_saved_search', icon: "mdi-folder-search"},
                        {to: "/account/following", title: 't_favorite_shops', icon: "mdi-store"},
                        {to: "/account/subscription", title: 't_my_subscriptions', icon: "mdi-wallet-membership"}
                    ],
                    apps: [
                        {icon: 'shops', title: 't_shops', to: '/browse/shops'},
                        {icon: 'deals', title: 't_deals', to: '/browse/deals'},
                        {icon: 'pricing', title: 't_pricing', to: '/pricing'},
                        {icon: 'blog', title: 't_blog', to: '/blog'},
                        {icon: 'feedback', title: 't_feedback', to: '/feedback'},
                        {icon: 'contact', title: 't_contact_us', to: '/contact'}
                    ]
                },
                language: 'en'
	  		}
        },

		computed: {
			index: function() {
				return this.$route.name === 'mainIndex'
			},

			logo: function() {
                if (this.index) {
                    if (this.$store.state.settings.logo.transparent !== null) {
                        return this.$homeApi('storage/app/' + this.$store.state.settings.logo.transparent)
                    }else{
                        return this.$homeApi('storage/app/uploads/default/settings/logo/transparent.png')
                    }
                }else{
                    if (this.$store.state.settings.logo.white !== null) {
                        return this.$homeApi('storage/app/' + this.$store.state.settings.logo.white)
                    }else{
                        return this.$homeApi('storage/app/uploads/default/settings/logo/white.png')
                    }
                }
            },

            avatar: function() {
                if (this.user.avatar !== null) {
                    return this.$homeApi('storage/app/' + this.user.avatar)
                }else{
                    return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
                }
            }
		},

        methods: {
            setLocale (locale) {
                if (this.$i18n.locale !== locale) {
                    loadMessages(locale)
                    this.$store.dispatch('lang/setLocale', { locale })
                    if (locale === 'ar') {
                        this.$vuetify.rtl = true
                    }else{
                        this.$vuetify.rtl = false
                    }
                }
            },

            async logout () {
                this.loading.progress = true
		      	// Log out the user.
                await this.$auth.logout()
                  
                this.loading.progress = false

		      	// Redirect to Home.
		      	this.$router.push({ name: 'mainIndex' })
		    },
        }
    }
</script>

<style>
    .main-navbar .v-toolbar__content {
        height: 64px !important;
    }
    .translate-languages .v-list__tile__avatar {
        min-width: 38px
    }
    .translate-languages .v-list--two-line .v-list__tile {
        height: 38px
    }
    .Cookie--base--rounded {
        padding: 3.25em !important
    }
    .Cookie--base--rounded .Cookie__content {
        font-size: 16px;
        font-family: "Hind", 'Noto Kufi Arabic', sans-serif;
    }
    .Cookie--base--rounded .Cookie__button {
        padding: 10px 30px;
        font-size: 13px !important;
        text-transform: uppercase;
        font-family: "Hind", 'Noto Kufi Arabic', sans-serif;;
        letter-spacing: 2px;
    }
    .v-application--is-rtl .Cookie--base--rounded .Cookie__button {
        letter-spacing: 0px;
    }
    .greetingTime {
        line-height: 23px !important
    }
</style>