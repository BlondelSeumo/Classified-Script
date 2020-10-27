<template>
	<v-app id="inspire">

		<v-dialog v-model="loading" persistent elevation-0 width="100" content-class="elevation-0">
			<v-card flat>
				<v-card-text class="pa-4 text-center">
                    <v-progress-circular
                        :size="45"
						width="2"
                        color="light-blue"
                        indeterminate
                    ></v-progress-circular>
				</v-card-text>
			</v-card>
		</v-dialog>

		<v-container>

			<v-container grid-list-xl fluid>
				<v-layout wrap>

					<!-- Deal -->
					<v-flex xs12>
						<v-card class="m-card mb-4" flat>
							<v-layout>
								<v-flex xs3 class="py-0">
									<v-img :src="poster" height="200px" cover></v-img>
								</v-flex>
								<v-flex xs9>
									<v-card-title primary-title class="py-2 px-4">
										<div>
											<nuxt-link :to="`/listing/${listing.unique_slug}`" target="_blank" class="headline black--text">{{ listing.title }}</nuxt-link>
											<div class="pt-2 caption" v-html="listing.description"></div>
										</div>
									</v-card-title>
									<v-card-actions>
										<v-list-item class="grow">
											<v-list-item-avatar color="grey darken-3">
												<v-img
													class="elevation-0"
													:src="avatar"
													></v-img>
											</v-list-item-avatar>
											<v-list-item-content>
												<v-list-item-title>{{ listing.user.username }}</v-list-item-title>
											</v-list-item-content>
											<v-layout
												align-center
												justify-end
												class="ma-0"
												>
												<v-icon class="mr-2" size="20px">mdi-eye</v-icon>
												<span class="subheading mr-2">{{ views | numeralFormat }}</span>
											</v-layout>
										</v-list-item>
									</v-card-actions>
								</v-flex>
							</v-layout>
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

					<!-- Devices -->
					<v-flex xs12>
						<v-card flat class="m-card pa-3">
							<v-card-title primary-title>
					          	<div> 
					            	<div class="title">{{ $t('t_devices') }}</div>
					            	<span class="card-description">{{ $t('t_devices_stats_desc') }}</span>
					          	</div>
					        </v-card-title>
					        <v-card-text>
							  	<div class="chartDiv" id="devicesChart" ref="devicesChart"></div>
							  </v-card-text>
						</v-card>
					</v-flex>

					<!-- Platforms -->
					<v-flex xs12>
						<v-card flat class="m-card pa-3">
							<v-card-title primary-title>
					          	<div> 
					            	<div class="title">{{ $t('t_platforms') }}</div>
					            	<span class="card-description">{{ $t('t_platforms_stats_desc') }}</span>
					          	</div>
					        </v-card-title>
					        <v-card-text>
							  	<div class="chartDiv" id="platformsChart" ref="platformsChart"></div>
							  </v-card-text>
						</v-card>
					</v-flex>

					<!-- Browsers -->
					<v-flex xs12>
						<v-card flat class="m-card pa-3">
							<v-card-title primary-title>
					          	<div> 
					            	<div class="title">{{ $t('t_browsers') }}</div>
					            	<span class="card-description">{{ $t('t_browsers_stats_desc') }}</span>
					          	</div>
					        </v-card-title>
					        <v-card-text>
							  	<div class="chartDiv" id="browsersChart" ref="browsersChart"></div>
							  </v-card-text>
						</v-card>
					</v-flex>

					<!-- More details -->
					<v-flex xs12>
						<div class="m-card">
							<v-toolbar color="white" flat>
								<v-toolbar-title>{{ $t('t_more_stats') }}</v-toolbar-title>
							</v-toolbar>
							<v-data-table
								:headers="headers"
								:items="statistics"
								item-key="id"
								disable-pagination
			      				hide-default-footer
								:mobile-breakpoint="1"
								:no-data-text="$t('t_table_no_data_available')"
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
								<template v-slot:item.keyword="{ item }">
									{{ item.searchEngineKeyword ? item.searchEngineKeyword : $t('t_n_a') }}
								</template>

								<!-- Last activity -->
								<template v-slot:item.activity="{ item }">{{ $ago(item.last_visit) }}</template>

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
					      	<pagination :data="statisticsData" @pagination-change-page="getMoreStatistics" :limit=8>
					      		<span slot="prev-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_left</i></span>
								<span slot="next-nav"><i aria-hidden="true" class="v-icon material-icons theme--light">chevron_right</i></span>
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
		layout: 'default/manager',

		middleware: 'auth',

		async asyncData({ $axios, params }) {
			let response = await $axios.post('manager/deals/options/statistics', { id: params.id })
			return {
				listing:    response.data.deal,
				views:      response.data.views,
				visits:     response.data.countries,
				continents: response.data.continents,
				devices:    response.data.devices,
				platforms:  response.data.platforms,
				browsers:   response.data.browsers,
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
			
			headers() {
				return [
		          	{ text: this.$t('t_location'), value: 'country', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_referrer'), value: 'referrer', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_search_keyword'), value: 'keyword', sortable: false, align: this.$vuetify.rtl ? 'right' : 'left'},
		          	{ text: this.$t('t_last_activity'), value: 'activity', sortable: false, align: 'center'},
		          	{ text: this.$t('t_device'), value: 'device', sortable: false, align: 'center'},
		          	{ text: this.$t('t_platform'), value: 'platform', sortable: false, align: 'center'},
					{ text: this.$t('t_browser'), value: 'browser', sortable: false, align: 'center'}
		        ]
			}
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

			setDevices: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				var chart = am4core.create("devicesChart", am4charts.PieChart3D);
				chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

				chart.data = this.devices

				chart.innerRadius = am4core.percent(40);
				chart.depth = 30;

				chart.legend = new am4charts.Legend();
				chart.legend.position = "right";

				var series = chart.series.push(new am4charts.PieSeries3D());
				series.dataFields.value = "value";
				series.dataFields.depthValue = "value";
				series.dataFields.category = "device";
				series.slices.template.cornerRadius = 5;
				series.colors.step = 3;
			},

			setPlatforms: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				var chart = am4core.create("platformsChart", am4charts.PieChart3D);
				chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

				chart.data = this.platforms

				chart.innerRadius = am4core.percent(40);
				chart.depth = 30;

				chart.legend = new am4charts.Legend();
				chart.legend.position = "right";

				var series = chart.series.push(new am4charts.PieSeries3D());
				series.dataFields.value = "value";
				series.dataFields.depthValue = "value";
				series.dataFields.category = "platform";
				series.slices.template.cornerRadius = 5;
				series.colors.step = 3;
			},

			setBrowsers: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				var chart = am4core.create("browsersChart", am4charts.PieChart3D);
				chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

				chart.data = this.browsers

				chart.innerRadius = am4core.percent(40);
				chart.depth = 30;

				chart.legend = new am4charts.Legend();
				chart.legend.position = "right";

				var series = chart.series.push(new am4charts.PieSeries3D());
				series.dataFields.value = "value";
				series.dataFields.depthValue = "value";
				series.dataFields.category = "browser";
				series.slices.template.cornerRadius = 5;
				series.colors.step = 3;
			},

			setVisits: function() {
				let {am4core, am4charts, am4maps, am4themes_animated, am4geodata_worldLow, am4themes_material} = this.$am4core();

				// Create chart instance
                var chart = am4core.create("visitsChart", am4charts.XYChart);
                
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
				.get('manager/deals/options/statistics/more/' +  this.listing.unique_id  + '?page=' + page)
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
			this.setDevices()
			this.setPlatforms()
			this.setBrowsers()
			this.getMoreStatistics()
			this.setVisits()
		}
	}
</script>

<style scoped>
	.chartDiv {
  		width: 100%;
 	 	height: 60vh;
	}
	.mapDiv {
		width: 100%;
 	 	height: 80vh;
	}
</style>