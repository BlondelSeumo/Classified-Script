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
                                                <v-icon color="success" v-if="i === 0 || i === 1 || i === 2 || i === 3">mdi-checkbox-marked-outline</v-icon>
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
                                <v-toolbar-title class="body-2">{{ $t('t_website_config') }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-xl fluid class="pa-0">
                                    <v-layout wrap>
                                        
                                        <!-- Website title -->
                                        <v-flex xs12>
                                            <v-text-field
                                                v-model="form.title"
                                                color="grey lighten-1"
                                                :label="$t('t_site_title')"
                                                :placeholder="$t('t_enter_site_title')"
                                                :rules="errors.title"
                                                :error="errors.title ? true : false"
                                                ></v-text-field>
                                        </v-flex>

                                        <!-- Website tagline -->
                                        <v-flex xs12>
                                            <v-text-field
                                                v-model="form.tagline"
                                                color="grey lighten-1"
                                                :label="$t('t_site_tagline')"
                                                :placeholder="$t('t_enter_site_tagline')"
                                                :rules="errors.tagline"
                                                :error="errors.tagline ? true : false"
                                                ></v-text-field>
                                        </v-flex>

                                        <!-- Title separator -->
                                        <v-flex xs12>
                                            <v-text-field
                                                v-model="form.separator"
                                                color="grey lighten-1"
                                                :label="$t('t_title_separator')"
                                                :placeholder="$t('t_enter_title_separator')"
                                                :rules="errors.separator"
                                                :error="errors.separator ? true : false"
                                                ></v-text-field>
                                        </v-flex>

                                        <!-- Default site direction -->
                                        <v-flex xs12>
                                            <v-select
                                                v-model="form.direction"
                                                :items="directions"
                                                item-text="name"
                                                item-value="id"
                                                color="grey lighten-1"
                                                :label="$t('t_default_direction')"
                                                :placeholder="$t('t_choose_default_direction')"
                                                :rules="errors.direction"
                                                :error="errors.direction ? true : false"
                                                dense
                                                ></v-select>
                                        </v-flex>

                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions class="follow-btn px-4">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" depressed class="px-5" :disabled="!form.title || !form.tagline || !form.separator" @click="website">
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
        data() {
            return {
                form: {
                    title: 'EVEREST',
                    tagline: 'TOP PHP SCRIPTS',
                    separator: '|',
                    direction: null
                },
                errors: [],
                item: 4,
                items: [
                    { text: 't_welcome', icon: 'mdi-emoticon-happy-outline' },
                    { text: 't_verify_purchase', icon: 'mdi-barcode-scan' },
                    { text: 't_server_requiremnts', icon: 'mdi-server' },
                    { text: 't_database_config', icon: 'mdi-database' },
                    { text: 't_website_config', icon: 'mdi-sitemap' },
                    { text: 't_setup_account', icon: 'mdi-account' },
                    { text: 't_finish', icon: 'mdi-timer' },
                ],
                directions: [
		          	{ id: 1, name: this.$t('t_right_to_left') },
		          	{ id: 0, name: this.$t('t_left_to_right') }
                ],
                loading: false
            }
        },

        head () {
			return {
				title: this.$t('t_website_config'),
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
            website() {
                if (!this.form.title || !this.form.tagline || !this.form.separator) {
                    return
                }
                this.loading = true
                this.$axios
                .post('installation/website', {
                    title: this.form.title,
                    tagline: this.form.tagline,
                    separator: this.form.separator,
                    direction: this.form.direction
                })
                .then(response => {
                    this.$toasted.show(this.$t('t_toasted_website_updated'), {
                        icon: 'done_all',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                    this.loading = false
                    this.$router.push('/installation/account')
                })
                .catch(error => {
                    if (error.response.data.errors) {
                        this.errors = error.response.data.errors
                    }
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