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
                                                <v-icon color="success" v-if="i === 0 || i === 1 || i === 2 || i === 3 || i === 4">mdi-checkbox-marked-outline</v-icon>
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
                                <v-toolbar-title class="body-2">{{ $t('t_setup_account') }}</v-toolbar-title>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-xl fluid class="pa-0">
                                    <v-layout wrap>
                                        
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
                                                @click:append="visible1 = !visible1"
                                                :append-icon="visible1 ? 'visibility' : 'visibility_off'"
                                                :type="visible1 ? 'text' : 'password'"
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
                                                @click:append="visible2 = !visible2"
                                                :append-icon="visible2 ? 'visibility' : 'visibility_off'"
                                                :type="visible2 ? 'text' : 'password'"
                                                ></v-text-field>
                                        </v-flex>

                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions class="follow-btn px-4">
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" depressed class="px-5" :disabled="!form.username || !form.email || !form.password || !form.password_confirmation" @click.prevent="account">
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
                    username: null,
                    email: null,
                    password: null,
                    password_confirmation: null
                },
                errors: [],
                item: 5,
                items: [
                    { text: 't_welcome', icon: 'mdi-emoticon-happy-outline' },
                    { text: 't_verify_purchase', icon: 'mdi-barcode-scan' },
                    { text: 't_server_requiremnts', icon: 'mdi-server' },
                    { text: 't_database_config', icon: 'mdi-database' },
                    { text: 't_website_config', icon: 'mdi-sitemap' },
                    { text: 't_setup_account', icon: 'mdi-account' },
                    { text: 't_finish', icon: 'mdi-timer' },
                ],
                visible1: false,
                visible2: false,
                loading: false
            }
        },

        head () {
			return {
				title: this.$t('t_setup_account'),
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
            account() {
                if (!this.form.username || !this.form.email || !this.form.password || !this.form.password_confirmation) {
                    return
                }
                this.loading = true
                this.$axios
                .post('installation/account', {
                    username: this.form.username,
                    email: this.form.email,
                    password: this.form.password,
                    password_confirmation: this.form.password_confirmation
                })
                .then(response => {
                    this.$toasted.show(this.$t('t_toasted_account_created'), {
                        icon: 'done_all',
                        className: this.$vuetify.rtl ? 'toasted-rtl' : ''
                    })
                    this.loading = false
                    this.$router.push('/installation/finish')
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