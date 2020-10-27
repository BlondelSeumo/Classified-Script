<template>
    <v-app id="inspire">
        <v-container fluid class="pa-0">

            <v-overlay v-model="loading" opacity="0.8">
                <v-progress-circular indeterminate size="80" width="4" color="white">
                    {{ $t('t_loading') }}
                </v-progress-circular>
            </v-overlay>

            <v-layout row>

                    <!-- Wallpaper -->
                    <v-flex xs8 class="hd-fullscreen auth-wallpaper">
                        <v-parallax :style="'background-image:url(' + $homeApi('public/images/auth/wallpaper.jpg' + '')" height="100%">
                            <v-layout
                                align-center
                                column
                                class="pa-12"
                                >
                                <nuxt-link to="/">
                                    <img :src="logo" alt="">
                                </nuxt-link>

                                <v-flex xs12 class="mt-5 follow-btn">

                                    <!-- Facebook -->
                                    <v-btn v-if="settings.social.isFacebook" block depressed dark small text color="#3C5A99" class="px-5 mb-4" @click="$auth.loginWith('facebook')">
                                        {{ $t('t_login_via_facebook') }}
                                    </v-btn>

                                    <!-- Twitter -->
                                    <v-btn v-if="settings.social.isTwitter" block depressed dark small text color="#1DA1F2" class="px-5 mb-4" @click.prevent="authenticate('twitter')">
                                        {{ $t('t_login_via_twitter') }}
                                    </v-btn>

                                    <!-- Google -->
                                    <v-btn v-if="settings.social.isGoogle" block depressed dark small text color="#DC4E41" class="px-5 mb-4" @click="$auth.loginWith('google')">
                                        {{ $t('t_login_via_google') }}
                                    </v-btn>

                                    <!-- Instagram -->
                                    <v-btn v-if="settings.social.isInstagram" block depressed dark small text color="#E4405F" class="px-5 mb-4" @click="authenticate('instagram')">
                                        {{ $t('t_login_via_instagram') }}
                                    </v-btn>

                                    <!-- Pinterest -->
                                    <v-btn v-if="settings.social.isPinterest" block depressed dark small text color="#BD081C" class="px-5 mb-4" @click="authenticate('pinterest')">
                                        {{ $t('t_login_via_pinterest') }}
                                    </v-btn>

                                    <!-- Linkedin -->
                                    <v-btn v-if="settings.social.isLinkedin" block depressed dark small text color="#0077B5" class="px-5 mb-4" @click="authenticate('linkedin')">
                                        {{ $t('t_login_via_linkedin') }}
                                    </v-btn>

                                    <!-- Vk -->
                                    <v-btn v-if="settings.social.isVk" block depressed dark small text color="#6383A8" class="px-5 mb-4" @click="authenticate('vk')">
                                        {{ $t('t_login_via_vk') }}
                                    </v-btn>

                                </v-flex>
                            </v-layout>
                        </v-parallax>
                    </v-flex>

                    <!-- Form -->
                    <v-flex xs4 order-lg2 v-if="settings.register">
                        <v-card flat class="hd-fullscreen pa-12" color="white">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <h1 class="title font-weight-black mb-1">{{ $t('t_create_account') }}</h1>
                                        <span class="caption grey--text lighten-3">{{ $t('t_create_account_para') }}</span>
                                    </v-flex>

                                    <!-- Username -->
                                    <v-flex xs12>
                                        <v-text-field
                                            v-model="form.username"
                                            color="grey lighten-1"
                                            :label="$t('t_username')"
                                            :placeholder="$t('t_enter_username')"
                                            :rules="errors.username"
                                            :error="errors.username ? true : false"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- E-mail address -->
                                    <v-flex xs12>
                                        <v-text-field
                                            v-model="form.email"
                                            color="grey lighten-1"
                                            :label="$t('t_email')"
                                            :placeholder="$t('t_enter_email')"
                                            :rules="errors.email"
                                            :error="errors.email ? true : false"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- Password -->
                                    <v-flex xs12>
                                        <v-text-field
                                            v-model="form.password"
                                            color="grey lighten-1"
                                            :label="$t('t_password')"
                                            :placeholder="$t('t_enter_password')"
                                            :rules="errors.password"
                                            :error="errors.password ? true : false"
                                            type="password"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- Actions -->
                                    <v-flex xs12>

                                        <!-- Register -->
                                        <v-btn depressed @click.prevent="register" :disabled="loading" :loading="loading" block color="blue white--text" light class="mb-2">{{ $t('t_register') }}</v-btn>

                                        <!-- Login -->
                                        <nuxt-link to="/auth/login">
                                            <v-btn depressed block light color="blue" class="mb-2 white--text">{{ $t('t_login') }}</v-btn>
                                        </nuxt-link>

                                    </v-flex>

                                </v-layout>
                            </v-container>
                        </v-card>
                    </v-flex>
                    <v-flex xs4 order-lg2 v-else>
                        <v-card flat class="hd-fullscreen pa-5">
                            <div style="position:absolute;top:50%;text-align:center;right:5%;left:5%;">
                                <h1 class="title font-weight-black mb-1">{{ $t('t_register_disabled') }}</h1>
                                <span class="caption grey--text lighten-3">{{ $t('t_register_disabled_para') }}</span>
                            </div>
                        </v-card>
                    </v-flex>

            </v-layout>

        </v-container>
    </v-app>
</template>

<script>
    export default {
        middleware: 'guest',

        async asyncData ({ $axios }) {
            let response = await $axios.get('auth/login')

            return {
                settings: {
                    login_type: response.data.login.login_type,
                    register: response.data.login.register,
                    logo: response.data.logo.logo,
                    social: response.data.social,
                    recaptcha: response.data.recaptcha.recaptcha,
                    siteKey: response.data.siteKey,
                },
                seo: {
                    title: response.data.seo.title,
                    tagline: response.data.seo.tagline,
                    separator: response.data.seo.separator,
                    description: response.data.seo.description,
                    favicon: response.data.seo.favicon
                }
            }
        },

        data: function() {
            return {
                loading : false,
                form: {
                    username: null,
                    email: null,
                    password: null
                },
                errors: []
            }
        },

        head() {
            return {
                title: this.$t('t_register'),
                titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
                meta: [
                    { name: 'description', content: this.seo.description },
                    { name: 'robots', content: 'index, follow' },
                    { property: 'og:type', content: 'article' },
                    { property: 'og:title', content: `${this.$t('t_create_account')} ${this.seo.separator} ${this.seo.title}` },
                    { property: 'og:description', content: this.seo.description },
                    { property: 'og:image', content: this.logo },
                    { property: 'og:url', content: this.$home('auth/register') },
                    { property: 'og:site_name', content: this.seo.title },
                    { property: 'twitter:title', content: `${this.$t('t_create_account')} ${this.seo.separator} ${this.seo.title}` },
                    { property: 'twitter:description', content: this.seo.description },
                    { property: 'twitter:image', content: this.logo }
                ],
                link: [
                    { rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
                ]
            }
        },

        computed: {
            logo: function() {
                if (this.settings.logo !== null) {
                    return this.$homeApi('storage/app/' + this.settings.logo)
                }else{
                    return this.$homeApi('storage/app/uploads/default/settings/logo/white.png')
                }
            }
        },

        methods: {
            async register() {
                if (!this.form.username || !this.form.password || !this.form.email) {return}
                try {
                    this.loading = true
                    let response = await this.$axios.post('auth/register', {
                                        username: this.form.username,
                                        email: this.form.email,
                                        password: this.form.password
                                    })

                    if (response.data === 'activation_required_email') {
                        this.$router.push('/auth/login')
                        this.$toasted.show(this.$t('t_toasted_activation_sent'), {
                            icon: 'alternate_email',
                            className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                        })
                        return
                    }else if (response.data === 'activation_required_manually') {
                        this.$router.push('/auth/login')
                        this.$toasted.show(this.$t('t_toasted_account_will_active_soon'), {
                            icon: 'sync',
                            className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                        })
                        return
                    }

                    await this.$auth.login({
                        data: {
                            credentials: this.form.email,
                            password: this.form.password
                        },
                    })

                    this.$router.push(this.$route.query.redirect ? this.$route.query.redirect : '/')
                } catch (error) {
                   if (error.response.data.errors) {
                        this.errors  = error.response.data.errors
                    } 
                    this.loading = false
                    this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
                        icon: 'error',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                }
            },
        }
    }
</script>