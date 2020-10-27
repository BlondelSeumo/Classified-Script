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
                                                <v-icon color="success" v-if="i === 0 || i === 1">mdi-checkbox-marked-outline</v-icon>
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
                                <v-toolbar-title class="body-2">{{ $t('t_server_requiremnts') }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>

                                <!-- PHP version -->
                                <v-alert text  type="success" v-if="php" v-html="$t('t_serv_req_php_true', {php: php})"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_php_false')"></v-alert>

                                <!-- BCMath -->
                                <v-alert text  type="success" v-if="bcmath" v-html="$t('t_serv_req_bcmath_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_bcmath_false')"></v-alert>

                                <!-- Ctype -->
                                <v-alert text  type="success" v-if="ctype" v-html="$t('t_serv_req_ctype_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_ctype_false')"></v-alert>

                                <!-- Json -->
                                <v-alert text  type="success" v-if="json" v-html="$t('t_serv_req_json_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_json_false')"></v-alert>

                                <!-- Mbstring -->
                                <v-alert text  type="success" v-if="mbstring" v-html="$t('t_serv_req_mbstring_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_mbstring_false')"></v-alert>

                                <!-- OpenSSL -->
                                <v-alert text  type="success" v-if="openssl" v-html="$t('t_serv_req_openssl_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_openssl_false')"></v-alert>

                                <!-- PDO -->
                                <v-alert text  type="success" v-if="pdo" v-html="$t('t_serv_req_pdo_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_pdo_false')"></v-alert>

                                <!-- Tokenizer -->
                                <v-alert text  type="success" v-if="tokenizer" v-html="$t('t_serv_req_tokenizer_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_tokenizer_false')"></v-alert>

                                <!-- XML -->
                                <v-alert text  type="success" v-if="xml" v-html="$t('t_serv_req_xml_true')"></v-alert>
                                <v-alert text  type="error" v-else v-html="$t('t_serv_req_xml_false')"></v-alert>
                            </v-card-text>
                            <v-card-actions class="follow-btn px-4">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" depressed class="px-5" :disabled="!php || !bcmath || !ctype || !json || !mbstring || !openssl || !pdo || !tokenizer || !xml" @click="requirements">
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
    export default {

        async asyncData ({ $axios }) {
            let response = await $axios.post('installation/requirements')
			return {
				php: response.data.php,
				bcmath: response.data.bcmath,
				ctype: response.data.ctype,
				json: response.data.json,
				mbstring: response.data.mbstring,
				openssl: response.data.openssl,
				pdo: response.data.pdo,
				tokenizer: response.data.tokenizer,
				xml: response.data.xml
			}
		},

        data() {
            return {
                item: 2,
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
				title: this.$t('t_server_requiremnts'),
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
            requirements() {
                this.loading = true
                if (!this.php || !this.bcmath || !this.ctype || !this.json || !this.mbstring || !this.openssl || !this.pdo || !this.tokenizer || !this.xml) {
                    this.$toasted.error('Oops! You dont have requirements to run EVEREST, Please contact your hosting provider support', {
                        icon: 'error',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                    this.loading = false
                }else{
                    this.loading = false
                    this.$router.push('/installation/database')
                }
            }
        }
    }
</script>