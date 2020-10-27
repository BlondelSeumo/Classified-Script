<template>
	<v-app id="inspire">

		<!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-container>

			<v-container grid-list-xl fluid>
				<v-layout wrap>

					<!-- Deal -->
					<v-flex xs12>
						<v-card class="m-card" :class="listing.isUpgraded && listing.upgradedTo === 'highlight' ? 'deal-highlight' : ''">
							<nuxt-link :to="'/listing/' + listing.unique_slug" style="position:absolute;height:100%;width:100%;"></nuxt-link>
							<v-container fluid grid-list-xl>
								<v-layout>
									<v-flex xs4>
										<v-img
											@click="$router.push('/listing/' + listing.unique_slug)"
											:src="poster"
											height="80px"
											contain
											></v-img>
									</v-flex>
									<v-flex xs8 class="pr-4 pl-0">
										<v-card-title primary-title class="pa-0">
											<div>
												<div class="deal-title"><span class="deal-urgent" v-if="listing.isUpgraded && listing.upgradedTo === 'urgent'">{{ $t('t_urgent') }}</span> {{ listing.title }}</div>
												<div class="deal-description">{{ $description(listing.description) }}</div>
											</div>
										</v-card-title>
									</v-flex>
								</v-layout>
							</v-container>
							<v-divider light></v-divider>
							<v-card-actions class="py-0 px-2">

								<v-list-item class="grow deal-avatar">
									<v-list-item-avatar size="25px">
										<v-img :src="avatar"></v-img>
									</v-list-item-avatar>
									<v-list-item-content>
										<v-list-item-title class="deal-username">{{ listing.user.username }}</v-list-item-title>
									</v-list-item-content>
									<v-layout align-center justify-end style="margin-bottom: 14px">
										<span class="deal-price" v-if="listing.price && listing.currency && listing.locale">{{ $getCurrency(listing.price, listing.currency, listing.locale) }}</span>
									</v-layout>
								</v-list-item>
							</v-card-actions>
						</v-card>
					</v-flex>

					<!-- Visits map -->
					<v-flex xs12>
						<v-card flat class="m-card pt-3">
						  	<div class="mapDiv" id="mapChart" ref="mapChart"></div>
						</v-card>
					</v-flex>

					<!-- Visits past week -->
					<v-flex xs12>
						<v-card flat class="m-card pa-3">
							<v-card-title primary-title>
					          	<div> 
					            	<div class="title">{{ $t('t_visits_last_week') }}</div>
					            	<span class="card-description">{{ $t('t_visits_last_week_desc') }}</span>
					          	</div>
					        </v-card-title>
					        <v-card-text>
							  	<div class="chartDiv" id="visitsChart" ref="visitsChart"></div>
							  </v-card-text>
						</v-card>
					</v-flex>

					<!-- Continents -->
					<v-flex xs12>
						<v-card flat class="m-card pa-3">
							<v-card-title primary-title>
					          	<div> 
					            	<div class="title">{{ $t('t_continents') }}</div>
					            	<span class="card-description">{{ $t('t_continents_stats_desc') }}</span>
					          	</div>
					        </v-card-title>
					        <v-card-text>
							  	<div class="chartDiv" id="continentsChart" ref="continentsChart"></div>
							  </v-card-text>
						</v-card>
					</v-flex>

					<!-- More details -->
					<v-flex xs12>
						<div class="m-card">
							<v-toolbar flat>
								<v-toolbar-title>{{ $t('t_more_stats') }}</v-toolbar-title>
							</v-toolbar>
							<v-data-table
								:headers="headers"
								:items="statistics"
								item-key="id"
			      				hide-default-footer
								disable-pagination
								:no-data-text="$t('t_table_no_data_available')"
								:mobile-breakpoint="1"
								>
								<!-- Country -->
								<template v-slot:item.country="{ item }">
									<v-list two-line class="transparent">
										<v-list-item>
											<v-list-item-avatar>
												<img :src="$homeApi('public/images/flags/large/' + item.countryCode + '.png')">
											</v-list-item-avatar>
											<v-list-item-content class="table-wrap-title">
												<v-list-item-title class="table-wrap-title" v-html="item.countryName"></v-list-item-title>
												<v-list-item-subtitle class="pb-1 font-weight-regular d-block caption">{{ item.state }} - {{ item.city }} - <b>{{ item.continent }}</b></v-list-item-subtitle>
											</v-list-item-content>
										</v-list-item>
									</v-list>
								</template>

								<!-- Referrer -->
								<template v-slot:item.referrer="{ item }">
									<a :href="item.referrer" target="_blank" class="table-wrap-title">{{ item.referrer }}</a>
									<small class="pb-1 font-weight-regular text-uppercase d-block">{{ item.referrerDomain }}</small>
								</template>

								<!-- Search Keyword -->
								<template v-slot:item.search_key="{ item }">
									{{ item.searchEngineKeyword ? item.searchEngineKeyword : $t('t_n_a') }}
								</template>

								<!-- Last activity -->
								<template v-slot:item.last_activity="{ item }">{{ $ago(item.last_visit) }}</template>

								<!-- Device -->
								<template v-slot:item.device="{ item }">
									<!-- Mobile -->
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon color="#7f8c8d" v-if="item.isPhone && !item.isTablet">
												<v-icon size="20px">mdi-cellphone</v-icon>
											</v-btn>
										</template>
										<span>{{ item.deviceName }}</span>
									</v-tooltip>
									<!-- Desktop -->
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon color="#7f8c8d" v-if="item.isDesktop">
												<v-icon size="20px">mdi-desktop-mac</v-icon>
											</v-btn>
										</template>
										<span>{{ $t('t_desktop') }}</span>
									</v-tooltip>
									<!-- Tablet -->
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon color="#7f8c8d" v-if="item.isTablet">
												<v-icon size="20px">mdi-tablet-android</v-icon>
											</v-btn>
										</template>
										<span>{{ item.deviceName }}</span>
									</v-tooltip>
									<!-- Robot -->
									<v-tooltip top>
										<template v-slot:activator="{ on }">
											<v-btn v-on="on" text icon color="#7f8c8d" v-if="item.isRobot">
												<v-icon size="20px">mdi-robot</v-icon>
											</v-btn>
										</template>
										<span>{{ item.robotName }}</span>
									</v-tooltip>
								</template>

								<!-- Platform -->
								<template v-slot:item.platform="{ item }">
									{{ item.platformName }} {{ item.platformVersion }}
								</template>

								<!-- Browser -->
								<template v-slot:item.browser="{ item }">
									{{ item.browserName }} {{ item.browserVersion }}
								</template>
							</v-data-table>
						</div>
						<div class="text-center pt-2">
					      	<pagination :data="statisticsData" @pagination-change-page="getMoreStatistics" :limit=8 :align="!$vuetify.rtl ? 'right' : 'left'">
								<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ $vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
								<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">{{ !$vuetify.rtl ? 'chevron_right' : 'chevron_left' }}</i></span>
							</pagination>
					    </div>
					</v-flex>

				</v-layout>
			</v-container>
		</v-container>

	</v-app>
</template>

<script>
	export default {
		layout: 'default/moderator',

		middleware: 'moderator',

		async asyncData({ app, $axios, params, redirect }) {
			// Check if allowed
			if (!app.$mogate.allow(app.$auth.user, 'stats', 'deals')) {
				redirect('/moderator')
			}

			let response = await $axios.post('moderator/deals/options/statistics', { id: params.id })
			return {
				listing:    response.data.deal,
				views:      response.data.views,
				visits:     response.data.countries,
				continents: response.data.continents,
				dates:      response.data.dates
			}
		},

		data: function() {
			return {
				statistics: [],
				statisticsData: {},
				views: null,
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_deal_statistics'),
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
			headers() {
				return [
		          	{ text: this.$t('t_location'), value: 'country', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_referrer'), value: 'referrer', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_search_keyword'), value: 'search_key', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_last_activity'), value: 'last_activity', sortable: false, align: 'center'},
		          	{ text: this.$t('t_device'), value: 'device', sortable: false, align: 'center'},
		          	{ text: this.$t('t_platform'), value: 'platform', sortable: false, align: 'center'},
		          	{ text: this.$t('t_browser'), value: 'browser', sortable: false, align: 'center'}
		        ]
			},

			poster: function() {
				if (this.listing.photosNumber == 0) {
            
		            // get default image
		            return this.$homeApi('storage/app/uploads/default/classifieds/no-image.png');

		        }else{

		            // get first image
		            return this.$homeApi('storage/app/uploads/classifieds/' + this.listing.unique_id + '/preview_0.jpg');

		        }
			},

			avatar: function() {
		   		if (this.listing.user.avatar === null) {
		   			return this.$homeApi('storage/app/uploads/default/avatar/noavatar.png')
		   		}else{
		   			return this.$homeApi('storage/app/' + this.listing.user.avatar)
		   		}
		   	},
		},

		methods: {
			map: function() {
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
				polygonSeries.data = this.visits
			},

			setContinents: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				var chart = am4core.create("continentsChart", am4charts.PieChart3D);
				chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

				chart.data = this.continents

				chart.innerRadius = am4core.percent(40);
				chart.depth = 30;

				chart.legend = new am4charts.Legend();
				chart.legend.position = "right";

				var series = chart.series.push(new am4charts.PieSeries3D());
				series.dataFields.value = "value";
				series.dataFields.depthValue = "value";
				series.dataFields.category = "continent";
				series.slices.template.cornerRadius = 5;
				series.colors.step = 3;
			},

			setVisits: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				// Create chart instance
				var chart = am4core.create("visitsChart", am4charts.XYChart);

				// Add data
			    let data = new Array()
				for (const item in this.dates) {
			        let obj = {
			        	date: item,
			        	value: this.dates[item]
			        }
			        data.push(obj)
			    }
				chart.data = data;

				// Create axes
				var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
				dateAxis.renderer.minGridDistance = 40;
				chart.cursor = new am4charts.XYCursor();

				// Set date label formatting
				dateAxis.dateFormats.setKey("day", "dt");
				dateAxis.periodChangeDateFormats.setKey("day", "dt");

				// Create value axis
				var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

				// Create series
				var series = chart.series.push(new am4charts.ColumnSeries());
				series.dataFields.valueY = "value";
				series.dataFields.dateX = "date";
				series.name = "Sales";
			},

			getMoreStatistics(page = 1) {
				this.loading = true
				this.$axios
				.get('moderator/deals/options/statistics/more/' +  this.listing.unique_id  + '?page=' + page)
				.then(response => {
					this.statistics     = response.data.data
					this.statisticsData = response.data
				})
				this.loading    = false
			},
		},

		mounted() {
			this.map()
			this.setContinents()
			this.getMoreStatistics()
			this.setVisits()
		}
	}
</script>

<style scoped>
	.chartDiv {
  		width: 100%;
 	 	height: 30vh;
	}
	.mapDiv {
		width: 100%;
 	 	height: 40vh;
	}
</style>