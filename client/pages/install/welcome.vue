<template>
	<v-app id="inspire">

        <!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content class="px-12">
			<v-container fluid grid-list-xl class="fill-height">
                <v-layout wrap>
                    
                    <!-- Installation contents -->
                    <v-flex xs3>
                        <v-card
                            class="mx-auto m-card px-3 pb-3"
                            >
                            <v-list dense rounded>
                                <v-subheader>{{ $t('t_installation') }}</v-subheader>
                                <v-list-item-group v-model="item" color="success">
                                    <v-list-item
                                        v-for="(item, i) in items"
                                        :key="i"
                                        @click="item = item"
                                        >
                                        <v-list-item-icon>
                                            <v-icon class="mt-1" v-text="item.icon"></v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content>
                                            <v-list-item-title v-t="item.text"></v-list-item-title>
                                        </v-list-item-content>
                                        <v-list-item-action>
                                                <v-icon color="grey">mdi-checkbox-blank-outline</v-icon>
                                        </v-list-item-action>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card>
                    </v-flex>

                    <!-- Content -->
                    <v-flex xs9>
                        <v-card class="m-card pa-4">
                            <v-toolbar
                                color="white"
                                dense
                                flat
                                >
                                <v-toolbar-title class="body-2">{{ $t('t_welcome') }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <p class="body-1" v-html="$t('t_welcome_para_install')"></p>
                                <!-- Languages -->
                                <v-item-group mandatory class="mt-12">
                                    <v-container class="pa-0">
                                        <v-row>
                                            <v-col
                                                v-for="(lang, index) in languages"
                                                :key="index"
                                                cols="12"
                                                md="2"
                                                >
                                                <v-item v-slot:default="{ active, toggle }">
                                                    <v-card
                                                        :color="lang.id === form.language ? 'info' : ''"
                                                        class="d-flex align-center"
                                                        dark
                                                        height="80"
                                                        @click="form.language = lang.id"
                                                        >
                                                        <v-scroll-y-transition>
                                                            <div
                                                                class="title flex-grow-1 text-center"
                                                                >
                                                                {{ lang.name }}
                                                            </div>
                                                        </v-scroll-y-transition>
                                                    </v-card>
                                                </v-item>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-item-group>
                            </v-card-text>
                            <v-card-actions class="follow-btn">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" depressed class="px-5" :disabled="!form.language" @click="welcome">
                                    {{ $t('t_next_step') }}
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
    import { loadMessages } from '~/plugins/i18n'

    export default {
        data() {
            return {
                languages: [
                    {id: 'ar', name: 'العربية'},
                    {id: 'fr', name: 'Français'},
                    {id: 'en', name: 'English'},
                    {id: 'ru', name: 'Pусский'},
                    {id: 'es', name: 'Español'},
                    {id: 'br', name: 'Português'},
                    {id: 'az', name: 'Azerbaycan'},
                    {id: 'de', name: 'Deutsch'},
                    {id: 'cn', name: '简体中文'},
                    {id: 'ct', name: '中文繁體'},
                    {id: 'id', name: 'Bahasa Indonesia'},
                    {id: 'in', name: 'भारतीय'},
                    {id: 'it', name: 'Italiano'},
                    {id: 'jp', name: '日本語'},
                    {id: 'kr', name: '한국어'},
                    {id: 'my', name: 'bahasa Melayu'},
                    {id: 'nl', name: 'Nederlands'},
                    {id: 'ph', name: 'Filipino'},
                    {id: 'pl', name: 'Polski'},
                    {id: 'ro', name: 'Română'},
                    {id: 'th', name: 'ไทย'},
                    {id: 'vi', name: 'Tiếng Việt'},
                    {id: 'tr', name: 'Türkçe'},
                    {id: 'uk', name: 'Українська'},
                ],
                form: {
                    language: this.$i18n.locale || 'en'
                },
                item: 0,
                items: [
                    { text: 't_welcome', icon: 'mdi-emoticon-happy-outline' },
                    { text: 't_verify_purchase', icon: 'mdi-barcode-scan' },
                    { text: 't_server_requiremnts', icon: 'mdi-server' },
                    { text: 't_database_config', icon: 'mdi-database' },
                    { text: 't_website_config', icon: 'mdi-sitemap' },
                    { text: 't_setup_account', icon: 'mdi-account' },
                    { text: 't_finish', icon: 'mdi-timer' },
                ],
                loading: false
            }
        },

        head () {
			return {
				title: this.$t('t_welcome'),
		    	titleTemplate: `%s - ${this.$t('t_installation')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$homeApi('storage/app/uploads/settings/favicon/favicon.png') }
      			]
			}
		},

        watch: {
            'form.language': {
                handler: function(locale) {
                    let langs = ['ar', 'en', 'fr'];

                    if (langs.includes(locale)) {

                        if (this.$i18n.locale !== locale) {
                            loadMessages(locale)
                            this.$store.dispatch('lang/setLocale', { locale })
                            if (locale === 'ar') {
                                this.$vuetify.rtl = true
                            }else{
                                this.$vuetify.rtl = false
                            }
                        }

                    }else{
                        this.$toasted.info("Selected langauge will be available in next few days", {
                            icon: 'error'
                        })
                        this.form.language = 'en'
                    }
                },
                deep: true
            }
        },

        methods: {
            welcome() {
                if (!this.form.language) {
                    return
                }
                this.loading = true
                this.$axios
                .post('installation/welcome', {
                    language: this.form.language
                })
                .then(response => {
                    this.$toasted.show(this.$t('t_toasted_language_set'), {
                        icon: 'done_all',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                    this.loading = false
                    this.$router.push('/installation/verify')
                })
                .catch(error => {
                    this.$toasted.error(this.$t('t_toasted_something_went_wrong'), {
                        icon: 'error',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                    this.loading = false
                })
            }
        }
    }
</script>