<template>
    <v-app id="inspire">
        <v-container fluid class="pa-0">

            <v-overlay v-model="loading" opacity="0.8">
                <v-progress-circular indeterminate size="80" width="4" color="white">
                    {{ $t('t_loading') }}
                </v-progress-circular>
            </v-overlay>

            <v-layout row>

                    <!-- Form -->
                    <v-flex xs12>
                        <v-card flat class="hd-fullscreen pa-12 no-border-radius">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 class="follow-btn">
                                        <v-btn class="auth-goback" text icon nuxt to="/">
                                            <v-icon>{{ $vuetify.rtl ? 'mdi-arrow-left-thick' : 'mdi-arrow-right-thick' }}</v-icon>
                                        </v-btn>
                                        <h1 class="title font-weight-black mb-1">{{ $t('t_password_reset') }}</h1>
                                        <span class="caption grey--text lighten-3">{{ $t('t_password_reset_para') }}</span>
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

                                    <!-- Actions -->
                                    <v-flex xs12>

                                        <!-- Reset password -->
                                        <v-btn depressed @click.prevent="reset" :disabled="loading" :loading="loading" block dark color="blue" class="mb-2">{{ $t('t_password_reset') }}</v-btn>

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
            let response = await $axios.get('auth/password/reset')

            return {
                settings: {
                    logo: response.data.logo.logo,
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
                form: {
                    email: null
                },
                errors: [],
                loading : false
            }
        },

        head() {
            return {
                title: this.$t('t_password_reset'),
                titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
                meta: [
                    { name: 'description', content: this.seo.description },
                    { name: 'robots', content: 'index, follow' },
                    { property: 'og:type', content: 'article' },
                    { property: 'og:title', content: `${this.$t('t_password_reset')} ${this.seo.separator} ${this.seo.title}` },
                    { property: 'og:description', content: this.seo.description },
                    { property: 'og:image', content: this.logo },
                    { property: 'og:url', content: this.$home('auth/password/reset') },
                    { property: 'og:site_name', content: this.seo.title },
                    { property: 'twitter:title', content: `${this.$t('t_password_reset')} ${this.seo.separator} ${this.seo.title}` },
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
            async reset() {
                if (!this.form.email) {return}
                this.loading = true
                let response = await this.$axios.post('auth/password/reset', {
                                    email: this.form.email
                                }).then(response => {
                                    this.$router.push('/auth/login')
                                    this.$toasted.show(this.$t('t_toasted_password_reset_code_sent'), {
                                        icon: 'done_all',
                                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                                    })
                                    this.loading = false
                                }).catch(error => {
                                    if (error.response.data.errors) {
                                        this.errors  = error.response.data.errors
                                    } 
                                    this.loading = false
                                    this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
                                        icon: 'error',
                                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                                    })
                                })
            }
        }
    }
</script>