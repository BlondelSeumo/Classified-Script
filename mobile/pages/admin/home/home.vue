<template>
	<v-app id="inspire">
		<v-container fluid grid-list-xl id="home-stats">
			<v-layout wrap>
				
				<!-- Users -->
				<v-flex xs6 v-if="$adgate.allow($auth.user, 'access', 'users')">
					<v-card class="m-card" text height="180">
						<v-sheet class="v-sheet--offset mx-auto text-center" text>
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" :color="$vuetify.theme.dark ? '#f2f2f2' : 'grey darken-3'" size="40px" class="pa-3">mdi-account-group</v-icon>
								</template>
								<span>{{ $t('t_users_statistics') }}</span>
							</v-tooltip>
						</v-sheet>
						<v-card-text class="py-0 text-center">
							<div class="display-1 font-weight-black mt-1 text-center">{{ stats.users.counter | numeralFormat }}</div>
						</v-card-text>
						<v-bottom-navigation
							absolute
							:background-color="$vuetify.theme.dark ? '#606060' : '#f9f9f9'"
							class="elevation-0"
							mandatory
							>
							<v-btn color="#333333" text @click="stats.users.counter = stats.users.today" class="font-weight-bold text-uppercase">
								{{ $t('t_today') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.users.counter = stats.users.month" class="font-weight-bold text-uppercase">
								{{ $t('t_month') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.users.counter = stats.users.total" class="font-weight-bold text-uppercase">
								{{ $t('t_total') }}
							</v-btn>
						</v-bottom-navigation>
					</v-card>
				</v-flex>

				<!-- Deals -->
				<v-flex xs6 v-if="$adgate.allow($auth.user, 'access', 'deals')">
					<v-card class="m-card" text height="180">
						<v-sheet class="v-sheet--offset mx-auto text-center" text>
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" :color="$vuetify.theme.dark ? '#f2f2f2' : 'grey darken-3'" size="40px" class="pa-3">mdi-image-multiple</v-icon>
								</template>
								<span>{{ $t('t_deals_statistics') }}</span>
							</v-tooltip>
						</v-sheet>
						<v-card-text class="pt-0 text-center">
							<div class="display-1 font-weight-black mt-2 text-center">{{ stats.deals.counter | numeralFormat }}</div>
						</v-card-text>
						<v-bottom-navigation
							absolute
							:background-color="$vuetify.theme.dark ? '#606060' : '#f9f9f9'"
							class="elevation-0"
							mandatory
							>
							<v-btn color="#333333" text @click="stats.deals.counter = stats.deals.today" class="font-weight-bold text-uppercase">
								{{ $t('t_today') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.deals.counter = stats.deals.month" class="font-weight-bold text-uppercase">
								{{ $t('t_month') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.deals.counter = stats.deals.total" class="font-weight-bold text-uppercase">
								{{ $t('t_total') }}
							</v-btn>
						</v-bottom-navigation>
					</v-card>
				</v-flex>

				<!-- Shops -->
				<v-flex xs6 v-if="$adgate.allow($auth.user, 'access', 'shops')">
					<v-card class="m-card" text height="180">
						<v-sheet class="v-sheet--offset mx-auto text-center" text>
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" :color="$vuetify.theme.dark ? '#f2f2f2' : 'grey darken-3'" size="40px" class="pa-3">mdi-store</v-icon>
								</template>
								<span>{{ $t('t_shops_statistics') }}</span>
							</v-tooltip>
						</v-sheet>
						<v-card-text class="pt-0 text-center">
							<div class="display-1 font-weight-black mt-2 text-center">{{ stats.shops.counter | numeralFormat }}</div>
						</v-card-text>
						<v-bottom-navigation
							absolute
							:background-color="$vuetify.theme.dark ? '#606060' : '#f9f9f9'"
							class="elevation-0"
							mandatory
							>
							<v-btn color="#333333" text @click="stats.shops.counter = stats.shops.today" class="font-weight-bold text-uppercase">
								{{ $t('t_today') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.shops.counter = stats.shops.month" class="font-weight-bold text-uppercase">
								{{ $t('t_month') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.shops.counter = stats.shops.total" class="font-weight-bold text-uppercase">
								{{ $t('t_total') }}
							</v-btn>
						</v-bottom-navigation>
					</v-card>
				</v-flex>

				<!-- Comments -->
				<v-flex xs6 v-if="$adgate.allow($auth.user, 'access', 'comments')">
					<v-card class="m-card" text height="180">
						<v-sheet class="v-sheet--offset mx-auto text-center" text>
							<v-tooltip top>
								<template v-slot:activator="{ on }">
									<v-icon v-on="on" :color="$vuetify.theme.dark ? '#f2f2f2' : 'grey darken-3'" size="40px" class="pa-3">mdi-comment-text-multiple</v-icon>
								</template>
								<span>{{ $t('t_comments_statistics') }}</span>
							</v-tooltip>
						</v-sheet>
						<v-card-text class="pt-0 text-center">
							<div class="display-1 font-weight-black mt-2 text-center">{{ stats.comments.counter | numeralFormat }}</div>
						</v-card-text>
						<v-bottom-navigation
							absolute
							:background-color="$vuetify.theme.dark ? '#606060' : '#f9f9f9'"
							class="elevation-0"
							mandatory
							>
							<v-btn color="#333333" text @click="stats.comments.counter = stats.comments.today" class="font-weight-bold text-uppercase">
								{{ $t('t_today') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.comments.counter = stats.comments.month" class="font-weight-bold text-uppercase">
								{{ $t('t_month') }}
							</v-btn>
							<v-btn color="#333333" text @click="stats.comments.counter = stats.comments.total" class="font-weight-bold text-uppercase">
								{{ $t('t_total') }}
							</v-btn>
						</v-bottom-navigation>
					</v-card>
				</v-flex>

				<!-- Users last week -->
				<v-flex xs12 v-if="$adgate.allow($auth.user, 'access', 'users')">
					<v-card text class="m-card pa-3">
						<v-card-title primary-title>
				          	<div> 
				            	<div class="title">{{ $t('t_users_this_week') }}</div>
				            	<span class="card-description">{{ $t('t_users_this_week_para') }}</span>
				          	</div>
				        </v-card-title>
				        <v-card-text>
							<div class="chartBox" id="usersWeekChart" ref="usersWeekChart"></div>
						  </v-card-text>
					</v-card>
				</v-flex>

				<!-- Deals last week -->
				<v-flex xs12 v-if="$adgate.allow($auth.user, 'access', 'deals')">
					<v-card text class="m-card pa-3">
						<v-card-title primary-title>
				          	<div> 
				            	<div class="title">{{ $t('t_deals_this_week') }}</div>
				            	<span class="card-description">{{ $t('t_deals_this_week_para') }}</span>
				          	</div>
				        </v-card-title>
				        <v-card-text>
						  	<div class="chartBox" id="dealsWeekChart" ref="dealsWeekChart"></div>
						  </v-card-text>
					</v-card>
				</v-flex>

				<!-- Visits map -->
				<v-flex xs12 v-if="$adgate.allow($auth.user, 'statistics', 'deals')">
					<v-card text class="m-card pt-3">
						<div class="mapDiv" id="mapChart" ref="mapChart"></div>
					</v-card>
				</v-flex>

				<!-- Top 10 visited deals -->
				<v-flex xs12 v-if="$adgate.allow($auth.user, 'statistics', 'deals')">
					<div class="m-card">
						<v-toolbar flat>
							<v-toolbar-title>{{ $t('t_top_10_visited_deals') }}</v-toolbar-title>
						</v-toolbar>
						<v-data-table
							:headers="headers[0].deals"
							:items="stats.deals.top"
							item-key="id"
		      				hide-default-footer
							disable-pagination
							:mobile-breakpoint="1"
							:no-data-text="$t('t_table_no_data_available')"
							>
							<!-- Deal -->
							<template v-slot:item.deal="{ item }">
								<v-list two-line class="transparent">
									<v-list-item :to="'/listing/' + item.unique_slug" target="_blank">
										<v-list-item-avatar>
											<img :src="preview(item)">
										</v-list-item-avatar>
										<v-list-item-content class="table-wrap-title">
											<v-list-item-title class="table-wrap-title" v-html="item.title"></v-list-item-title>
											<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption"><b>{{ item.user.username }}</b></v-list-item-subtitle>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</template>

							<!-- Visits -->
							<template v-slot:item.visits="{ item }">
								{{ item.statistics_count }}
							</template>

							<!-- status -->
							<template v-slot:item.status="{ item }">
								<div v-if="item.deleted_at === null">
									<!-- Featured -->
									<v-btn text color="#19b5fe" v-if="item.isUpgraded && !item.isPending && !item.isArchived">
										<span v-if="item.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span>
										<span v-if="item.upgradedTo === 'highlight'">{{ $t('t_highlight') }}</span>
										<span v-if="item.upgradedTo === 'featured'">{{ $t('t_featured') }}</span>
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

				<!-- Recent shops -->
				<v-flex xs12 v-if="$adgate.allow($auth.user, 'access', 'shops')">
					<div class="m-card">
						<v-toolbar flat>
							<v-toolbar-title>{{ $t('t_recent_shops') }}</v-toolbar-title>
						</v-toolbar>
						<v-data-table
							:headers="headers[0].shops"
							:items="stats.shops.recent"
							item-key="id"
		      				hide-default-footer
							disable-pagination
							:mobile-breakpoint="1"
							:no-data-text="$t('t_table_no_data_available')"
							>
							<!-- Shop -->
							<template v-slot:item.shop="{ item }">
								<v-list two-line class="transparent">
									<v-list-item :to="'/shop/' + item.store" target="_blank">
										<v-list-item-avatar>
											<img :src="logo(item.logo)">
										</v-list-item-avatar>
										<v-list-item-content class="table-wrap-title">
											<v-list-item-title class="table-wrap-title" v-html="item.title"></v-list-item-title>
											<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption" v-html="item.store"></v-list-item-subtitle>
										</v-list-item-content>
									</v-list-item>
								</v-list>
							</template>

							<!-- Status -->
							<template v-slot:item.status="{ item }">
								<div v-if="item.deleted_at === null">
									<!-- Active -->
									<v-btn text color="#26c281" v-if="item.isActive && !item.isExpired">
										{{ $t('t_active') }}
									</v-btn>
									<!-- Pending -->
									<v-btn text color="warning" v-else-if="item.isPending">
										{{ $t('t_pending') }}
									</v-btn>
									<!-- Expired -->
									<v-btn text color="#7f8c8d" v-else-if="item.isExpired">
										{{ $t('t_expired') }}
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
		layout: 'default/admin',

		middleware: 'administrator',

		async asyncData({ app, $axios, redirect }) {
			let response = await $axios.post('administrator')
			return {
				stats: {
					users: {
						counter: response.data.users.today,
						today: response.data.users.today,
						yesterday: response.data.users.yesterday,
						week: response.data.users.week,
						month: response.data.users.month,
						total: response.data.users.total
					},
					deals: {
						counter: response.data.deals.today,
						today: response.data.deals.today,
						yesterday: response.data.deals.yesterday,
						week: response.data.deals.week,
						month: response.data.deals.month,
						total: response.data.deals.total,
						top: response.data.deals.top
					},
					shops: {
						counter: response.data.shops.today,
						today: response.data.shops.today,
						yesterday: response.data.shops.yesterday,
						month: response.data.shops.month,
						total: response.data.shops.total,
						recent: response.data.shops.recent,
					},
					comments: {
						counter: response.data.comments.today,
						today: response.data.comments.today,
						yesterday: response.data.comments.yesterday,
						month: response.data.comments.month,
						total: response.data.comments.total,
					}
				},
				countries: response.data.countries
			}
		},

		data() {
			return {
				headers: [
				{
					deals: [
						{ text: this.$t('t_deal'), value: 'deal', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
			          	{ text: this.$t('t_visits'), value: 'visits', sortable: false, align: 'center'},
			          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	],
		          	shops: [
		          		{ text: this.$t('t_shop'), value: 'shop', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
			          	{ text: this.$t('t_status'), value: 'status', sortable: false, align: 'center'},
		          	]
		        }],
			}
		},

		head() {
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
			setMap: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				var chart = am4core.create("mapChart", am4maps.MapChart);

				// Set map definition
				chart.geodata = am4geodata_worldLow;

				// Set projection
				chart.projection = new am4maps.projections.Miller();

				// Create map polygon series
				var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

				// Make map load polygon (like country names) data from GeoJSON
				polygonSeries.useGeodata = true;

				// Configure series
				var polygonTemplate = polygonSeries.mapPolygons.template;
				// Add zoom control
				chart.zoomControl = new am4maps.ZoomControl();
				polygonSeries.exclude = ["AQ"];

				polygonTemplate.tooltipText = "{value} visits from {name}";
				polygonTemplate.fill = am4core.color("#e2e2e2");

				polygonTemplate.stroke = am4core.color("#a9a9a9");

				// Add heat rule
				polygonSeries.heatRules.push({
				  	"property": "fill",
				  	"target": polygonSeries.mapPolygons.template,
				  	"min": am4core.color("#737373"),
				  	"max": am4core.color("#292929")
				});

				// Add expectancy data
				polygonSeries.data = this.countries
			},

			setUsersThisWeek: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				// Create chart instance
				var chart = am4core.create("usersWeekChart", am4charts.XYChart);

				// Add data
			    let data = new Array()
				for (const item in this.stats.users.week) {
			        let obj = {
			        	date: item,
			        	value: this.stats.users.week[item]
			        }
			        data.push(obj)
			    }
				chart.data = data;

				// Create axes
				var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
				dateAxis.renderer.minGridDistance = 10;
				chart.cursor = new am4charts.XYCursor();
				chart.colors.list = [
				  	am4core.color("#424242")
				];

				// Set date label formatting
				dateAxis.dateFormats.setKey("day", "dt");
				dateAxis.periodChangeDateFormats.setKey("day", "dt");

				// Create value axis
				var valueAxis           = chart.yAxes.push(new am4charts.ValueAxis());
				valueAxis.renderer.labels.template.adapter.add("text", function(text, target) {
				  return text;
				});

				// Create series
				var series = chart.series.push(new am4charts.ColumnSeries());
				series.dataFields.valueY = "value";
				series.dataFields.dateX = "date";
				series.name = "Users last week";
			},

			setDealsThisWeek: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				// Create chart instance
				var chart = am4core.create("dealsWeekChart", am4charts.XYChart);

				// Add data
			    let data = new Array()
				for (const item in this.stats.deals.week) {
			        let obj = {
			        	date: item,
			        	value: this.stats.deals.week[item]
			        }
			        data.push(obj)
			    }
				chart.data = data;

				// Create axes
				var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
				dateAxis.renderer.minGridDistance = 10;
				chart.cursor = new am4charts.XYCursor();
				chart.colors.list = [
				  	am4core.color("#424242")
				];

				// Set date label formatting
				dateAxis.dateFormats.setKey("day", "dt");
				dateAxis.periodChangeDateFormats.setKey("day", "dt");

				// Create value axis
				var valueAxis           = chart.yAxes.push(new am4charts.ValueAxis());
				valueAxis.renderer.labels.template.adapter.add("text", function(text, target) {
				  return text;
				});

				// Create series
				var series = chart.series.push(new am4charts.ColumnSeries());
				series.dataFields.valueY = "value";
				series.dataFields.dateX = "date";
				series.name = "Users last week";
			},

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
		},

		mounted() {
			if (this.$adgate.allow(this.$auth.user, 'access', 'users')) {
				this.setUsersThisWeek()
			}

			if (this.$adgate.allow(this.$auth.user, 'access', 'deals')) {
				this.setDealsThisWeek()
			}

			if (this.$adgate.allow(this.$auth.user, 'statistics', 'deals')) {
				this.setMap()
			}
		}
	}
</script>

<style>
	.chartBox {
  		width: 100%;
 	 	height: 30vh;
	}
	.mapDiv {
		width: 100%;
 	 	height: 40vh;
	}
	#home-stats .v-item-group.v-bottom-navigation .v-btn--active .v-btn__content {
	    font-size: 10px !important;
	}
	#home-stats .v-item-group.v-bottom-navigation .v-btn .v-btn__content {
	    font-size: 9px !important;
		letter-spacing: 1px !important;
		height: auto;
	}
	.v-application--is-rtl #home-stats .v-item-group.v-bottom-navigation .v-btn .v-btn__content {
		letter-spacing: 0 !important;
		font-size: 11px !important;
	}
	#home-stats .v-item-group.v-bottom-navigation .v-btn {
		min-width: 50px !important;
		padding: 8px 12px 8px !important;
	}
</style>