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
                        <v-card flat class="hd-fullscreen pa-12">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <h1 class="title font-weight-black mb-1">{{ $t('t_password_update') }}</h1>
                                        <span class="caption grey--text lighten-3">{{ $t('t_password_update_para') }}</span>
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
                                            autocomplete="off"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- Password confirmation -->
                                    <v-flex xs12>
                                        <v-text-field
                                            v-model="form.password_confirmation"
                                            color="grey lighten-1"
                                            :label="$t('t_password_confirmation')"
                                            :placeholder="$t('t_enter_password_confirmation')"
                                            :rules="errors.password_confirmation"
                                            :error="errors.password_confirmation ? true : false"
                                            type="password"
                                            autocomplete="off"
                                            ></v-text-field>
                                    </v-flex>

                                    <!-- Actions -->
                                    <v-flex xs12>

                                        <!-- update password -->
                                        <v-btn depressed @click.prevent="update" :disabled="loading" :loading="loading" block dark color="blue" class="mb-2">{{ $t('t_update_password') }}</v-btn>

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

        async asyncData ({ $axios, query, redirect }) {
            let response = await $axios.get(`auth/password/update?token=${query.token}`)
                                       .catch(error => {
                                            redirect('/')
                                       })

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
                    email: null,
                    password: null,
                    password_confirmation: null,
                },
                errors: [],
                loading : false
            }
        },

        head() {
            return {
                title: this.$t('t_password_update'),
                titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
                meta: [
                    { name: 'description', content: this.seo.description },
                    { name: 'robots', content: 'index, follow' },
                    { property: 'og:type', content: 'article' },
                    { property: 'og:title', content: `${this.$t('t_password_update')} ${this.seo.separator} ${this.seo.title}` },
                    { property: 'og:description', content: this.seo.description },
                    { property: 'og:image', content: this.logo },
                    { property: 'og:url', content: this.$home('auth/password/update') },
                    { property: 'og:site_name', content: this.seo.title },
                    { property: 'twitter:title', content: `${this.$t('t_password_update')} ${this.seo.separator} ${this.seo.title}` },
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
            async update() {
                if (!this.form.email || !this.form.password || !this.form.password_confirmation) {return}
                this.loading = true
                let response = await this.$axios.post(`auth/password/update?token=${this.$route.query.token}`, {
                                    email: this.form.email,
                                    password: this.form.password,
                                    password_confirmation: this.form.password_confirmation,
                                }).then(response => {
                                    this.$router.push('/auth/login')
                                    this.$toasted.show(this.$t('t_toasted_password_updated'), {
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