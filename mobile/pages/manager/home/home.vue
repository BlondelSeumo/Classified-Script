<template>
    <v-app id="inspire">
		<v-container fluid grid-list-xl id="home-stats">
			<v-layout wrap>

                <!-- Quick stats -->
                <template v-for="(item, index) in items">
                    <v-flex xs4 :key="index">
                        <v-card :color="$vuetify.theme.dark ? '#3a3939' : 'light-blue'" dark class="m-card no-border-radius">
                            <v-card class="text-center py-3" :color="$vuetify.theme.dark ? '#404040' : '#50C7FE'" flat>
                                <v-icon size="30px"> {{item.icon}} </v-icon>
                            </v-card>
                            <v-card-text class="text-center px-0 py-2">
                                <div class="font-weight-bold text-uppercase caption pb-2">{{item.title}}</div>
                                <div class="title font-weight-black">{{item.value}}</div>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </template>

                <!-- Top 10 visited deals -->
				<v-flex xs12>
					<div class="m-card mt-4">
						<v-toolbar flat>
							<v-toolbar-title>{{ $t('t_top_10_visited_deals') }}</v-toolbar-title>
						</v-toolbar>
						<v-data-table
							:headers="headers"
							:items="deals"
							item-key="id"
		      				hide-default-footer
							disable-pagination
							:no-data-text="$t('t_table_no_data_available')"
							:mobile-breakpoint="1"
							>
							<!-- Deal -->
							<template v-slot:item.deal="{ item }">
								<v-list two-line class="transparent">
									<v-list-item nuxt :to="`/listing/${item.unique_slug}`" :ripple="false" target="_blank">
										<v-list-item-avatar>
											<img :src="preview(item)">
										</v-list-item-avatar>
										<v-list-item-content class="table-wrap-title">
											<v-list-item-title class="table-wrap-title" v-html="item.title"></v-list-item-title>
											<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption"><b>{{ item.unique_id }}</b></v-list-item-subtitle>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</template>

							<!-- Price -->
							<template v-slot:item.price="{ item }">
								<div v-if="item.price === null && item.currency === null && item.locale === null"></div>
								<div v-else>{{ $getCurrency(item.price, item.currency, item.locale) }}</div>
							</template>

							<!-- Visits -->
							<template v-slot:item.visits="{ item }">
								<div class="font-weight-bold">{{ item.statistics_count }}</div>
							</template>

							<!-- status -->
							<template v-slot:item.status="{ item }">
								<div v-if="item.deleted_at === null">
									<!-- Featured -->
									<v-btn text color="#19b5fe" v-if="item.isUpgraded && !item.isPending && !item.isArchived">
										<span v-if="item.upgradedTo === 'urgent'" v-t="'t_urgent'"></span>
										<span v-if="item.upgradedTo === 'highlight'" v-t="'t_highlight'">Highlight</span>
										<span v-if="item.upgradedTo === 'featured'" v-t="'t_featured'">Featured</span>
									</v-btn>
									<!-- Active -->
									<v-btn text color="#2ecc71" v-else-if="item.isActive">
										{{ $t('t_active') }}
									</v-btn>
									<!-- Pending -->
									<v-btn text color="warning" v-else-if="item.isPending">
										{{ $t('t_pending') }}
									</v-btn>
									<!-- Archived -->
									<v-btn text color="#95a5a6" v-else-if="item.isArchived">
										{{ $t('t_archived') }}
									</v-btn>
								</div>
								<!-- Deleted -->
								<v-btn text color="error" v-if="item.deleted_at !== null">
									{{ $t('t_deleted') }}
								</v-btn>
							</template>
						</v-data-table>
					</div>
				</v-flex>

            </v-layout>
        </v-container>
    </v-app>
</template>

<script>
    export default {
        layout: 'default/manager',

        middleware: 'manager',
        
        async asyncData({ app, $axios }) {
            let response = await $axios.post('manager')
            return {
                items: [ 
                    {title: app.i18n.t('t_total_deals'), icon: "mdi-image-multiple", value: response.data.deals_count},
                    {title: app.i18n.t('t_total_shops'), icon: "mdi-store", value: response.data.shops_count},
                    {title: app.i18n.t('t_total_followers'), icon: "mdi-account-group", value: response.data.followers_count},
                    {title: app.i18n.t('t_total_messages'), icon: "mdi-message", value: response.data.messages_count},
                    {title: app.i18n.t('t_total_offers'), icon: "mdi-tag", value: response.data.offers_count},
                    {title: app.i18n.t('t_total_visits'), icon: "mdi-finance", value: response.data.visits_count},
                ],
                deals: response.data.recent
            }
        },

        computed: {
            headers() {
				return [
                    { text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
                    { text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
                    { text: this.$t('t_visits'), value: 'visits', sortable: false, align: 'center'},
                    { text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
                ]
			}
        },

        head () {
			return {
				title: this.$t('t_my_dashboard'),
		    	titleTemplate: `%s ${this.$store.state.settings.separator} ${this.$store.state.settings.title}`,
		    	meta: [
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.$store.state.settings.favicon ? this.$homeApi(`storage/app/${this.$store.state.settings.favicon}`) : this.$homeApi(`storage/app/uploads/default/settings/favicon/favicon.png`) },
      			]
			}
        },
        
        methods: {
            preview: function(deal) {
		   		if (deal.photosNumber == 0) {
            
		            // get default image
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');

		        }else{

		            // get first image
		            return this.$homeApi('storage/app/uploads/classifieds/' + deal.unique_id + '/preview_0.jpg');

		        }
		   	}
        }
    }
</script>            

<style scoped>
	.v-card:not(.v-sheet--tile) {
		border-radius: 0
	}
</style>