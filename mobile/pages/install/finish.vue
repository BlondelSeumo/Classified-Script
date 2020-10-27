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
                                                <v-icon color="success" v-if="i === 0 || i === 1 || i === 2 || i === 3 || i === 4 || i === 5 || finished">mdi-checkbox-marked-outline</v-icon>
                                                <v-icon color="grey" v-else>mdi-checkbox-blank-outline</v-icon>
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
                                <v-toolbar-title class="body-2">{{ $t('t_finish_installation') }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-banner two-line>
                                    <v-icon slot="icon" color="info" size="36">
                                        mdi-check-all
                                    </v-icon>
                                    <div v-html="$t('t_finish_installation_para')"></div>
                                </v-banner>
                            </v-card-text>
                            <v-card-actions class="follow-btn px-4">

                                <!-- Supoort links -->
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" class="mx-2" fab depressed text dark small color="#959595" href="https://tawk.to/mendelman" target="_blank">
                                            <v-icon dark>mdi-chat</v-icon>
                                        </v-btn>
                                    </template>
                                    <span v-t="'t_live_chat'"></span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" class="mx-2" fab depressed text dark small color="#959595" href="https://facebook.com/MendelManGroup" target="_blank">
                                            <v-icon dark>mdi-facebook-box</v-icon>
                                        </v-btn>
                                        </template>
                                    <span v-t="'t_mendelman_on_fb'"></span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" class="mx-2" fab depressed text dark small color="#959595" href="https://codecanyon.net/item/everest-php-classified-ads-script/20234300/support" target="_blank">
                                            <v-icon dark>mdi-face-agent</v-icon>
                                        </v-btn>
                                        </template>
                                    <span v-t="'t_everest_on_codecanyon'"></span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn v-on="on" class="mx-2" fab depressed text dark small color="#959595" href="mailto:ezzaroual@mail.com" target="_blank">
                                            <v-icon dark>mdi-at</v-icon>
                                        </v-btn>
                                    </template>
                                    <span v-t="'t_email'"></span>
                                </v-tooltip>

                                <v-spacer></v-spacer>
                                <v-btn text color="primary" depressed class="px-5" @click="finish" v-if="!finished">
                                    {{ $t('t_finish_installation') }}
                                </v-btn>
                                <v-btn text color="primary" depressed class="px-5" to="/auth/login?redirect=/administrator" nuxt v-if="finished">
                                    {{ $t('t_loginto_dashboard') }}
                                </v-btn>
                                <v-btn text color="primary" depressed class="px-5" to="/auth/login?redirect=/moderator" nuxt v-if="finished">
                                    {{ $t('t_loginto_moderator') }}
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
    export default {
        data() {
            return {
                item: 6,
                items: [
                    { text: 't_welcome', icon: 'mdi-emoticon-happy-outline' },
                    { text: 't_verify_purchase', icon: 'mdi-barcode-scan' },
                    { text: 't_server_requiremnts', icon: 'mdi-server' },
                    { text: 't_database_config', icon: 'mdi-database' },
                    { text: 't_website_config', icon: 'mdi-sitemap' },
                    { text: 't_setup_account', icon: 'mdi-account' },
                    { text: 't_finish', icon: 'mdi-timer' },
                ],
                finished: false,
                loading: false
            }
        },

        head () {
			return {
				title: this.$t('t_finish'),
		    	titleTemplate: `%s - ${this.$t('t_installation')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$homeApi('storage/app/uploads/settings/favicon/favicon.png') }
      			]
			}
		},

        methods: {
            finish() {
                this.loading = true
                this.$axios
                .post('installation/finish')
                .then(response => {
                    this.$toasted.show(this.$t('t_toasted_website_ready'), {
                        icon: 'done_all',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                    this.finished = true
                    this.loading  = false
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