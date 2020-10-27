<template>
    <v-app id="inspire">

		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>
			<div class="m-card">
				<v-toolbar color="white" class="elevation-0">
					<v-toolbar-title>{{ $t('t_my_subscriptions') }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn depressed small class="px-3" to="/pricing" target="_blank" nuxt>
                        {{ $t('t_renew_your_subscription') }}
                    </v-btn>
				</v-toolbar>
				<v-data-table
					:headers="headers"
					:items="subscriptions"
					item-key="id"
      				hide-default-footer
					:no-data-text="$t('t_table_no_data_available')"
					disable-pagination
					>
					<template v-slot:item.package="{ item }">
						<v-list two-line class="transparent">
							<v-list-item>
								<v-list-item-avatar>
									<img :src="icon(item.plan)">
								</v-list-item-avatar>
								<v-list-item-content>
									<v-list-item-title class="table-wrap-title" v-html="item.plan.title"></v-list-item-title>
									<v-list-item-subtitle v-html="item.plan.subtitle"></v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</v-list>
					</template>

					<!-- Price -->
					<template v-slot:item.price="{ item }">
						<div v-if="item.price === null && item.currency === null && item.locale === null"></div>
						<div v-else>{{ $getCurrency(item.price, item.currency, item.locale) }}<span class="font-weight-light">/{{ frequency(item.plan.frequency) }}</span></div>
					</template>

					<!-- Subscribed at -->
					<template v-slot:item.subscribed="{ item }">{{ $ago(item.subscribed_at) }}</template>

					<!-- Expires -->
					<template v-slot:item.expires="{ item }">{{ $dateString(item.expires_at) }}</template>

					<!-- status -->
					<template v-slot:item.status="{ item }">
						<!-- Expired -->
						<v-btn text color="error" v-if="item.isExpired">
							{{ $t('t_expired') }}
						</v-btn>
						<!-- Soon -->
						<v-btn text color="#949494" v-else-if="item.expirySoon">
							{{ $t('t_expire_soon') }}
						</v-btn>
						<!-- Active -->
						<v-btn text color="success" v-else>
							{{ $t('t_active') }}
						</v-btn>
					</template>
				</v-data-table>
			</div>
			<div class="text-center pt-2">
		      	<pagination :data="subscriptionsData" @pagination-change-page="getNextPage" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
		      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
					<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
		      	</pagination>
		    </div>
		</v-container>
	</v-app>
</template>

<script>
    export default {
        layout: 'default/manager',

        middleware: 'manager',

        async asyncData({ $axios }) {
            let response = await $axios.get('manager/settings/subscription')
            return {
				subscriptionsData: response.data,
				subscriptions: response.data.data
			}
        },

        data() {
            return {
                loading: false
            }
        },
        
        head() {
			return {
				title: this.$t('t_my_subscriptions'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$t('t_dashboard')}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
		},
		
		computed: {
			headers(){
				return [
					{ text: this.$t('t_package'), value: 'package', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
		          	{ text: this.$t('t_subscribed'), value: 'subscribed', sortable: false, align: 'center'},
		          	{ text: this.$t('t_expires_in'), value: 'expires', sortable: false, align: 'center'},
		          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'}
				]
			}
		},
        
        methods: {
			getNextPage(page = 1) {
				this.loading = true
				this.$axios
				.get('manager/settings/subscription?page=' + page)
				.then(response => {
					this.subscriptionsData = response.data
					this.subscriptions     = response.data.data
					if (typeof window !== undefined) {
						window.scrollTo(0,0)
					}
					this.loading           = false
				})
			},
			
			frequency: function(frequency)  {
				switch (frequency) {
					case 'daily':
						return this.$t('t_daily')
						break;
					case 'weekly':
						return this.$t('t_weekly')
						break;
					case 'monthly':
						return this.$t('t_monthly')
						break;
					case 'yearly':
						return this.$t('t_yearly')
						break;
				
					default:
						return this.$t('t_yearly')
						break;
				}
			},

            icon: function(icon) {
				if (icon.icon === null) {
					return this.$homeApi('storage/app/uploads/default/plan/no-image.jpg')
				}else{
					return this.$homeApi('storage/app/' + icon.icon)
				}
			}
        }
    }
</script>

