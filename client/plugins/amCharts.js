import Vue from "vue";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import * as am4maps from "@amcharts/amcharts4/maps";
import am4geodata_worldLow from "@amcharts/amcharts4-geodata/worldLow";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";
import am4themes_material from "@amcharts/amcharts4/themes/material";
am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_material);

Vue.prototype.$am4core = () => {
  return {
    am4core,
    am4charts,
    am4maps,
    am4themes_animated,
    am4geodata_worldLow,
    am4themes_material
  }
}