<template>
	<v-app id="inspire">

        <!-- Loading -->
		<v-overlay v-model="loading" opacity="0.8">
			<v-progress-circular indeterminate size="80" width="4" color="white">
				{{ $t('t_loading') }}
			</v-progress-circular>
		</v-overlay>

		<v-content>
      		<v-container grid-list-xl fluid>
      			<v-layout wrap fill-height>

      				<!-- Account sidebar -->
          			<v-sidebar></v-sidebar>

          			<!-- Account page -->
					<v-flex xs9>
                        <v-container grid-list-xl fluid class="pa-0">
                            <v-layout wrap>

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

                            </v-layout>
                        </v-container>
					</v-flex>

				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
	import sidebar from '~/pages/main/account/layout/account'

	export default {
		middleware: 'auth',

		layout: 'default/main',

		components: {
			'v-sidebar': sidebar
		},

		async asyncData({ $axios, params }) {
			let response = await $axios.post('account/deals/options/statistics', { id: params.id })
			return {
                listing: response.data.deal,
				dates:      response.data.dates,
				seo: {
					title: response.data.seo.title,
	  				separator: response.data.seo.separator,
	  				description: response.data.seo.description,
	  				favicon: response.data.seo.favicon
				}
			}
		},

		data: function() {
			return {
				loading: false
			}
		},

		head() {
			return {
				title: this.$t('t_deal_statistics'),
		    	titleTemplate: `%s ${this.seo.separator} ${this.seo.title}`,
		    	meta: [
			      	{ name: 'description', content: this.seo.description },
			      	{ name: 'robots', content: 'noindex, nofollow' }
			    ],
			    link: [
      				{ rel: 'icon', type: 'image/x-icon', href: this.seo.favicon }
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
				polygonSeries.data = this.visits
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
			}
		},

		mounted() {
			this.setMap()
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