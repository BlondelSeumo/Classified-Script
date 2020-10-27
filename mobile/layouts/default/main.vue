<template>
    <v-app id="inspire" :dark="theme.dark" :light="!theme.dark">
        <div class="mobile-theme">

            <!-- layout loading -->
            <v-overlay v-model="loading.progress" opacity="0.8">
                <v-progress-circular indeterminate size="80" width="4" color="white">
                    {{ $t('t_loading') }}
                </v-progress-circular>
            </v-overlay>

            <!-- Toolbar -->
            <v-app-bar app fixed class="primary-navbar">

                <!-- Left panel -->
                <v-app-bar-nav-icon @click="navigation.left = !navigation.left"></v-app-bar-nav-icon>

                <!-- Logo -->
                <v-toolbar-title class="mx-auto">
                    <nuxt-link to="/" class="d-block"><img style="margin-top: 10px" :src="logo"></nuxt-link>
                </v-toolbar-title>

                <!-- Right panel -->
                <v-btn icon @click="navigation.right = !navigation.right">
                    <v-icon>mdi-apps</v-icon>
                </v-btn>

            </v-app-bar>

            <!-- Mobile slidshow -->
            <div v-if="index" v-swiper:mySwiper="slideshow" style="height: 250px" class="slideshow">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <v-img :aspect-ratio="16/9" height="250" :src="$homeApi(`storage/app/uploads/settings/wallpaper/${this.$store.state.settings.home.wallpaper}.jpg`)">
                            <v-layout align-content-center justify-center fill-height wrap class="slider-lightbox white--text">
                                <v-flex shrink class="text-center px-8">
                                    <div class="headline text-uppercase font-weight-bold" v-html="this.$store.state.settings.home.text"></div>
                                </v-flex>
                            </v-layout>
                        </v-img>
                    </div>
                </div>
                <div class="swiper-pagination"  slot="pagination"></div>
            </div>

            <!-- User navigation -->
            <v-navigation-drawer v-model="navigation.left" fixed temporary :right="$vuetify.rtl" :left="!$vuetify.rtl">
                <v-img :aspect-ratio="16/9" :src="require('~/static/parallel.jpg')" v-if="$auth.loggedIn">
                    <v-layout column fill-height class="lightbox white--text ma-0">
                        <v-spacer></v-spacer>
                        <v-flex shrink class="pa-3" v-if="$auth.loggedIn">
                            <v-avatar size="50px" class="pb-3">
                                <img :src="avatar" alt="">
                            </v-avatar>
                            <div class="subheading">{{ $auth.user.username }}</div>
                            <div class="body-1">{{ $auth.user.email }}</div>
                            <div class="body-2 text-uppercase pt-3" @click.prevent="logout">{{ $t('t_sign_out') }}</div>
                        </v-flex>
                    </v-layout>
                </v-img>
                <v-list dense rounded class="pa-3" v-if="$auth.loggedIn">
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
                </v-list>
                <v-list dense rounded class="pa-3" v-else>
                    <!-- Sign in -->
                    <v-list-item :to="`/auth/login?redirect=${this.$router.currentRoute.fullPath}`" nuxt>
                        <v-list-item-icon>
                            <v-icon>mdi-login</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-t="'t_sign_in'"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <!-- Create account -->
                    <v-list-item :to="`/auth/register`" nuxt>
                        <v-list-item-icon>
                            <v-icon>mdi-account-plus</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-t="'t_sign_up'"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>

            <!-- Settings -->
            <v-navigation-drawer v-model="navigation.right" fixed temporary :left="$vuetify.rtl" :right="!$vuetify.rtl">
                <v-list dense rounded class="pa-3">
                    <template v-for="(app, index) in loops.apps">
                        <v-list-item :key="index" :to="app.to" @click="dialogs.apps = false" nuxt>
                            <v-list-item-icon>
                                <v-icon>{{ app.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title v-t="app.title"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                </v-list>
                <div class="pt-12 text-center">
                    <!-- Search -->
                    <v-search></v-search>
                    <v-btn :color="$vuetify.theme.dark ? '#b5b5b5' : 'info'" text depressed icon @click="theme.dark = !theme.dark">
                        <v-icon>mdi-brightness-4</v-icon>
                    </v-btn>
                    <v-btn :color="$vuetify.theme.dark ? '#b5b5b5' : 'info'" text depressed icon @click="navigation.right = false, dialogs.language = true">
                        <v-icon>mdi-translate</v-icon>
                    </v-btn>
                    <v-btn :color="$vuetify.theme.dark ? '#b5b5b5' : 'info'" text depressed icon v-if="$isAdmin()" to="/administrator">
                        <v-icon>mdi-desktop-mac-dashboard</v-icon>
                    </v-btn>
                    <v-btn :color="$vuetify.theme.dark ? '#b5b5b5' : 'info'" text depressed icon v-else-if="$isManager()" to="/manager">
                        <v-icon>mdi-desktop-mac-dashboard</v-icon>
                    </v-btn>
                    <v-btn :color="$vuetify.theme.dark ? '#b5b5b5' : 'info'" text depressed icon v-else-if="$isModerator()" to="/moderator">
                        <v-icon>mdi-desktop-mac-dashboard</v-icon>
                    </v-btn>
                </div>
            </v-navigation-drawer>

            <!-- Translate dialog -->
            <v-dialog v-model="dialogs.language" scrollable max-width="300px">
                <v-card>
                    <v-card-title>
                        <span class="font-weight-bold">{{ $t('t_select_your_lang') }}</span>
                        <v-spacer></v-spacer>
                        <v-btn icon @click="dialogs.language = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text style="height: 250px;" class="translate-languages mt-8">
                        <v-container fluid grid-list-xl class="px-0">
                            <v-layout wrap>
                                <v-flex xs12 class="py-1 px-0" v-for="(lang, index) in languages" :key="index">
                                    <v-list rounded two-line :class="$store.state.lang.locale === lang.code ? 'selected-language' : ''" dense>
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

            <!-- Content -->
            <div>
                <nuxt/>
            </div>

            <!-- Footer -->
            <v-footer height="auto" class="pa-0">
                <v-card flat class="white black--text text-center pa-12" style="width:100%">
                    <v-layout row>

                        <v-flex xs6 class="mb-4">
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

                        <v-flex xs6 class="mb-4">
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

                        <v-flex xs6 class="mb-4">
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

                        <v-flex xs6 class="mb-4">
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

        </div>
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
                theme: {
                    dark: this.$cookies.get('darkMode') || false,
                },
                navigation: {
                    left: false,
                    right: false
                },
                loading: {
                	progress: false
                },
                slideshow: {
		          	pagination: {
			            el: '.swiper-pagination'
			        },
			        autoplay: true
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
                        {to: "/account/subscription", title: 't_my_subscriptions', icon: "mdi-wallet-membership"},
                        {icon: 'mdi-two-factor-authentication', title: 't_two_factor_auth', to: '/account/2fa'}
			    
                    ],
                    apps: [
                        {icon: 'mdi-store', title: 't_shops', to: '/browse/shops'},
                        {icon: 'mdi-newspaper', title: 't_deals', to: '/browse/deals'},
                        {icon: 'mdi-check-decagram', title: 't_pricing', to: '/pricing'},
                        {icon: 'mdi-file', title: 't_blog', to: '/blog'},
                        {icon: 'mdi-message-alert', title: 't_feedback', to: '/feedback'},
                        {icon: 'mdi-face-agent', title: 't_contact_us', to: '/contact'}
                    ]
                },
                language: 'en'
	  		}
        },

        watch: {
            'theme.dark': {
                handler: function(isDarkMode) {
                    if (isDarkMode) {
                        this.$cookies.set('darkMode', true, {maxAge: 60 * 60 * 24 * 7})
                        this.$vuetify.theme.dark = true
                    }else{
                        this.$cookies.set('darkMode', false, {maxAge: 60 * 60 * 24 * 7})
                        this.$vuetify.theme.dark = false
                    }
                },
                deep: true
            }
        },

		computed: {
			index: function() {
				return this.$route.name === 'mainIndex'
			},

			logo: function() {
                if (this.$store.state.settings.logo.transparent !== null) {
                    return this.$homeApi('storage/app/' + this.$store.state.settings.logo.transparent)
                }else{
                    return this.$homeApi('storage/app/uploads/default/settings/logo/transparent.png')
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
                this.navigation.left  = false
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