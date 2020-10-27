<template>
	<v-app id="inspire">
		<v-container fluid grid-list-xl id="home-stats">
			<v-layout wrap>

				<!-- Recent deals -->
				<v-flex xs12 v-if="$mogate.allow($auth.user, 'access', 'deals')">
					<div class="m-card">
						<v-toolbar color="white" flat>
							<v-toolbar-title>{{ $t('t_recent_deals') }}</v-toolbar-title>
						</v-toolbar>
						<v-data-table
							:headers="headers[0].deals"
							:items="deals"
							item-key="id"
							hide-default-footer
							disable-pagination
							:no-data-text="$t('t_table_no_data_available')"
							>
							<!-- Deal -->
							<template v-slot:item.deal="{ item }">
								<v-list two-line class="transparent">
									<v-list-item nuxt :ripple="false" :to="'/listing/' +  item.unique_slug" target="_blank">
										<v-list-item-avatar>
											<img :src="preview( item)">
										</v-list-item-avatar>
										<v-list-item-content class="table-wrap-title">
											<v-list-item-title class="table-wrap-title" v-html=" item.title"></v-list-item-title>
											<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption"><b>{{  item.user.username }}</b></v-list-item-subtitle>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</template>

							<!-- Price -->
							<template v-slot:item.price="{ item }">
								<div class="font-weight-black" v-if=" item.price === null &&  item.currency === null &&  item.locale === null"></div>
								<div class="font-weight-black" v-else>{{ $getCurrency( item.price,  item.currency,  item.locale) }}</div>
							</template>

							<!-- Category -->
							<template v-slot:item.category="{ item }">{{  item.category.title }}</template>

							<!-- Created -->
							<template v-slot:item.created="{ item }">
								{{ $ago( item.created_at) }}
							</template>

							<!-- Expires -->
							<template v-slot:item.expires="{ item }">{{ $dateString( item.expires_at) }}</template>

							<!-- status -->
							<template v-slot:item.status="{ item }">
								<div v-if=" item.deleted_at === null">
									<!-- Featured -->
									<v-btn text color="#19b5fe" v-if=" item.isUpgraded && ! item.isPending && ! item.isArchived">
										<span v-if=" item.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span>
										<span v-if=" item.upgradedTo === 'highlight'">{{ $t('t_highlight') }}</span>
										<span v-if=" item.upgradedTo === 'featured'">{{ $t('t_featured') }}</span>
									</v-btn>
									<!-- Active -->
									<v-btn text color="#2ecc71" v-else-if=" item.isActive">
										{{ $t('t_active') }}
									</v-btn>
									<!-- Pending -->
									<v-btn text color="warning" v-else-if=" item.isPending">
										{{ $t('t_pending') }}
									</v-btn>
									<!-- Archived -->
									<v-btn text color="#95a5a6" v-else-if=" item.isArchived">
										{{ $t('t_archived') }}
									</v-btn>
								</div>
								<!-- Deleted -->
								<v-btn text color="error" v-if=" item.deleted_at !== null">
									{{ $t('t_deleted') }}
								</v-btn>
							</template>
						</v-data-table>
					</div>
				</v-flex>

				<!-- Recent shops -->
				<v-flex xs12 v-if="$mogate.allow($auth.user, 'access', 'shops')">
					<div class="m-card">
						<v-toolbar color="white" flat>
							<v-toolbar-title>{{ $t('t_recent_shops') }}</v-toolbar-title>
						</v-toolbar>
						<v-data-table
							:headers="headers[0].shops"
							:items="shops"
							item-key="id"
							:no-data-text="$t('t_table_no_data_available')"
		      				hide-default-footer
							disable-pagination
							>
							<template v-slot:item.shop="{ item }">
								<v-list two-line class="transparent">
									<v-list-item nuxt :ripple="false" :to="'/shop/' +  item.store" target="_blank">
										<v-list-item-avatar>
											<img :src="logo( item.logo)">
										</v-list-item-avatar>
										<v-list-item-content class="table-wrap-title">
											<v-list-item-title class="table-wrap-title" v-html=" item.title"></v-list-item-title>
											<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html=" item.store"></v-list-item-subtitle>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</template>

							<!-- Country -->
							<template v-slot:item.country="{ item }">
								<v-avatar size="36px" v-if=" item.country">
									<img :src="$homeApi('public/images/flags/large/' +  item.country.code + '.png')">
								</v-avatar>
								<v-avatar size="36px" v-else>
									<img :src="$homeApi('public/images/flags/large/unknown.png')">
								</v-avatar>
							</template>

							<!-- Created -->
							<template v-slot:item.created="{ item }">{{ $ago( item.created_at) }}</template>

							<!-- Status -->
							<template v-slot:item.status="{ item }">
								<div v-if=" item.deleted_at === null">
									<!-- Active -->
									<v-btn text color="#26c281" v-if=" item.isActive && ! item.isExpired">
										{{ $t('t_active') }}
									</v-btn>
									<!-- Pending -->
									<v-btn text color="warning" v-else-if=" item.isPending">
										{{ $t('t_pending') }}
									</v-btn>
									<!-- Expired -->
									<v-btn text color="#7f8c8d" v-else-if=" item.isExpired">
										{{ $t('t_expired') }}
									</v-btn>
								</div>
								<!-- Deleted -->
								<v-btn text color="error" v-if=" item.deleted_at !== null">
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
		layout: 'default/moderator',

		middleware: 'moderator',

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.post('moderator')
			return {
				deals: response.data.deals,
				shops: response.data.shops
			}
		},

		data() {
			return {
				headers: [
				{
					deals: [
						{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left' },
			          	{ text: this.$t('t_price'), value: 'price', sortable: false, align: 'center'},
			          	{ text: this.$t('t_category'), value: 'category', sortable: false, align: 'center'},
			          	{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
			          	{ text: this.$t('t_expires_in'), value: 'expires', sortable: false, align: 'center'},
			          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	],
		          	shops: [
		          		{ text: this.$t('t_shop'), value: 'shop', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left' },
		          		{ text: this.$t('t_country'), value: 'country', sortable: false, align: 'center'},
		          		{ text: this.$t('t_created'), value: 'created', sortable: false, align: 'center'},
			          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	]
		        }],
			}
		},

		head () {
			return {
				title: this.$t('t_dashboard'),
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
		   	},

		   	logo: function(logo) {
				if (logo === null) {
					return this.$homeApi('storage/app/uploads/default/store/no-logo.png')
				}else{
					return this.$homeApi('storage/app/' + logo)
				}
			}
		}
	}
</script>