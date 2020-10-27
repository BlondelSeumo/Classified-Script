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

                                <v-flex xs12 class="mt-12 follow-btn">

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
                    <v-flex xs4 order-lg2>
                        <v-card flat class="hd-fullscreen pa-12" color="white">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <h1 class="title font-weight-black mb-1">{{ $t('t_login') }}</h1>
                                        <span class="caption grey--text lighten-3">{{ $t('t_login_to_your_account') }}</span>
                                    </v-flex>

                                    <!-- Credentials -->
                                    <v-flex xs12>
                                        <v-text-field
                                            v-model="form.credentials"
                                            color="grey lighten-1"
                                            :label="loginType"
                                            :placeholder="$t('t_enter_your_credentials')"
                                            :rules="errors.credentials"
                                            :error="errors.credentials ? true : false"
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
                                            autocomplete="off"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- Two Factor Authentication verification code -->
                                    <v-flex xs12 v-if="loginRequires2FA">
                                        <v-text-field
                                            v-model="form.verifyCode"
                                            color="grey lighten-1"
                                            :label="$t('t_2fa_verification')"
                                            :hint="$t('t_6_numbers')"
                                            :placeholder="$t('t_enter_verification_code')"
                                            :rules="errors.verifyCode"
                                            :error="errors.verifyCode ? true : false"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- Action -->
                                    <v-flex xs12>
                                        <!-- Login -->
                                        <v-btn depressed @click.prevent="login" :disabled="loading" :loading="loading" block color="blue" class="mb-2 white--text">{{ $t('t_login') }}</v-btn>

                                        <!-- Register -->
                                        <v-btn v-if="settings.register" to="/auth/register" depressed block dark color="blue" class="mb-2">{{ $t('t_register') }}</v-btn>

                                        <!-- Password recovery -->
                                        <v-btn to="/auth/password/reset" depressed block dark color="blue" class="mb-2">{{ $t('t_password_reset') }}</v-btn>
                                    </v-flex>

                                </v-layout>
                            </v-container>
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
                isClient: false,
                loading : false,
                isRequired2FA: false,
                loginRequires2FA: false,
                directLogin: false,
                form: {
                    credentials: '',
                    password: '',
                    verifyCode: '',
                    remember: ''
                },
                errors: []
            }
        },

        head() {
            return {
                title: this.$t('t_login'),
                titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
                meta: [
                    { name: 'description', content: this.seo.description },
                    { name: 'robots', content: 'index, follow' },
                    { property: 'og:type', content: 'article' },
                    { property: 'og:title', content: `${this.$t('t_login')} ${this.seo.separator} ${this.seo.title}` },
                    { property: 'og:description', content: this.seo.description },
                    { property: 'og:image', content: this.logo },
                    { property: 'og:url', content: this.$home('auth/login') },
                    { property: 'og:site_name', content: this.seo.title },
                    { property: 'twitter:title', content: `${this.$t('t_login')} ${this.seo.separator} ${this.seo.title}` },
                    { property: 'twitter:description', content: this.seo.description },
                    { property: 'twitter:image', content: this.logo }
                ],
                link: [
                    { rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
                ]
            }
        },

        methods: {
            async login () {
                if (!this.form.credentials || !this.form.password) {return}
                this.loading = true

                // Get recaptcha token if enabled
                const token = this.recaptcha ? await this.$recaptcha.execute('login') : null
                
                let data = {
                    credentials: this.form.credentials,
                    password: this.form.password,
                    verifyCode: this.form.verifyCode,
                    captchaToken: token
                }
                await this.$auth.login({ data: data })
                          .then(() => {
                                this.loading         = false
                                this.$router.push(this.$route.query.redirect ? this.$route.query.redirect : '/')
                          })
                          .catch((error) => {
                                if (error.response.data.state === "two_factor_required") {
                                    this.loginRequires2FA = true
                                    this.$toasted.error(this.$t('t_toasted_2fa_required'), {
                                        icon: 'error',
                                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                                    })
                                }else if (error.response.data.state === "two_factor_wrong") {
                                    this.errors = []
                                    this.$toasted.error(this.$t('t_toasted_2fa_wrong_code'), {
                                        icon: 'error',
                                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                                    })
                                }else if (error.response.data.error === "Unauthorized") {
                                    this.$toasted.error(this.$t('t_toasted_credentials_wrong'), {
                                        icon: 'error',
                                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                                    })
                                }else if (error.response.status === 429) {
                                    this.$toasted.clear()
                                    if (error.response.data.errors) {
                                        var message = error.response.data.errors.credentials[0]
                                    }else{
                                        var message = this.$t('t_toasted_too_many_attempts')
                                    }
                                    this.$toasted.error(message, {
                                        icon: 'block',
                                        className: this.$vuetify.rtl ? 'toasted-warning toasted-rtl' : 'toasted-warning'
                                    })
                                }else{
                                    this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
                                        icon: 'error',
                                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                                    })
                                    this.errors = []
                                }
                                this.loading  = false
                          })
            },

            async authenticate (provider) {

                let response = await this.$axios.get('auth/twitter')

                window.location.href = response.data
            },

            socialite: function(provider, data){
                this.loading = true
                this.$axios
                .post('auth/' + provider, data)
                .then(response => {
                    this.$auth.token('default_auth_token', response.data)
                    this.$auth.watch.authenticated = true
                    this.$auth.fetch({
                        params: {},
                        success: function () {},
                        error: function () {}
                    });
                    this.$router.push('/')
                    this.loading = false
                })
                .catch(error => {
                    this.loading = false
                    console.log(error)
                })
            }
        },

        computed: {
            loginType: function() {
                if (this.settings.login_type === 'username') {return this.$t('t_username')}
                if (this.settings.login_type === 'email') {return this.$t('t_email')}
                if (this.settings.login_type === 'phone') {return this.$t('t_phone')}
                if (this.settings.login_type === 'ue') {return this.$t('t_username_or_email')}
                if (this.settings.login_type === 'uep') {return this.$t('t_username_or_email_or_phone')}
            },

            logo: function() {
                if (this.settings.logo !== null) {
                    return this.$homeApi('storage/app/' + this.settings.logo)
                }else{
                    return this.$homeApi('storage/app/uploads/default/settings/logo/white.png')
                }
            }
        },

        async mounted() {
            await this.$recaptcha.init()
        },
    }
</script>

<style>
    .captcha-btn {
        border: 0;
        border-radius: 0;
        background: none;
        -webkit-appearance: none;
    }
</style>