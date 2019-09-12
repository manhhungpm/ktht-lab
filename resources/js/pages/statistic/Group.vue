<template>
    <div>
        <div class="row">
            <div class="col-6">
                <portlet
                    style="height: 550px; "
                    title="Báo cáo số lượng thuê bao"
                >
                    <another-highcharts
                        :series="pieSeries1"
                        :plot-options="plotOptionPie1"
                        :tooltip-format="'<b>{point.y}%</b>'"
                        :chart-height="450"
                        :margin-top="50"
                        :exporting="true"
                    ></another-highcharts>
                </portlet>
            </div>
            <div class="col-6">
                <portlet title="Báo cáo tổng thời lượng cuộc gọi thuê bao">
                    <another-highcharts
                        :chart-type="'column'"
                        :series="columnSeries1"
                        :plot-options="columnPlotOptions1"
                        :tooltip-format="'Số lượng:<b> {point.y}</b>'"
                        :has-legend="true"
                        :point-width="60"
                        :margin-top="20"
                        :categories="columnCategories1"
                        :exporting="true"
                    ></another-highcharts>
                </portlet>
            </div>
            <div class="col-6">
                <portlet
                    title="Báo cáo tổng các cuộc gọi 1 chiều, 2 chiều trong tháng"
                    style="height: 550px; "
                >
                    <highchart-stacked-column
                        :categories="stackedCategories1"
                        :series="stackedSeries1"
                        :point-width="45"
                        :chart-type="'column'"
                        :has-legend="true"
                        :tooltip-format="
                            'Số lượng:<b> {point.percentage:.1f}%</b>'
                        "
                        :exporting="true"
                        :inverted="true"
                        :plot-options="stackedPlotOptions1"
                        :horizontal-margin="92"
                    ></highchart-stacked-column>
                </portlet>
            </div>
            <div class="col-6">
                <portlet
                    title="Báo cáo tổng các cuộc gọi có thời lượng <10s, 10s->60s, >60s trong tháng"
                    style="height: 600px; "
                >
                    <another-highcharts
                        :chart-type="'area'"
                        :series="areaSeries1"
                        :plot-options="areaPlotOption1"
                        :exporting="true"
                        :categories="areaCategories1"
                        :has-legend="true"
                        :tooltip-format="'Số lượng:<b> {point.y}</b>'"
                    ></another-highcharts>
                </portlet>
            </div>
            <div class="col-6">
                <portlet title="Báo cáo tổng số lượng cuộc gọi ra của thuê bao">
                    <another-highcharts
                        :chart-type="'bar'"
                        :categories="barCategories1"
                        :series="barSeries1"
                        :plot-options="barPlotOption1"
                        :exporting="true"
                        :tooltip-format="'Số lượng:<b> {point.y}%</b>'"
                        :horizontal-margin="92"
                    >
                    </another-highcharts
                ></portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "../../components/common/Portlet";
import AnotherHighcharts from "../../components/common/AnotherHighcharts";
import HighchartStackedColumn from "../../components/common/HighchartStackedColumn";
export default {
    name: "Highchart",
    components: { HighchartStackedColumn, AnotherHighcharts, Portlet },
    middleware: "auth",
    data() {
        return {
            //Highchart 1
            pieSeries1: [
                {
                    data: [
                        {
                            name: "Cuộc gọi bình thường",
                            y: 61.41,
                            sliced: true,
                            selected: true
                        },
                        {
                            name: "Cuộc gọi nghi ngờ spam",
                            y: 11.84
                        },
                        {
                            name: "Cuộc gọi nghề nghiệp đặc thù",
                            y: 10.85
                        },
                        {
                            name: "Cuộc gọi từ tổng đài,telesale",
                            y: 4.67
                        }
                    ]
                }
            ],
            plotOptionPie1: {
                column: {
                    dataLabels: {
                        enabled: false
                    },
                    minPointLength: 3
                },
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        style: {
                            fontSize: "12.5px"
                        }
                    },
                    innerSize: "30%"
                },
                series: {
                    animation: {
                        duration: 2000
                    }
                }
            },

            //Highchart 2
            columnSeries1: [
                { name: "Cuộc gọi bình thường", data: [50] },
                { name: "Cuộc gọi nghi ngờ spam", data: [89] },
                { name: "Cuộc gọi nghề nghiệp đặc thù", data: [32] },
                { name: "Cuộc gọi từ tổng đài,telesale", data: [66] }
            ],
            columnPlotOptions1: {
                column: {
                    dataLabels: {
                        enabled: false
                    },
                    minPointLength: 1
                }
            },
            columnCategories1: ["Tháng 1"],

            //Highchart 3
            stackedCategories1: [
                "Cuộc gọi bình thường",
                "Cuộc gọi nghi ngờ spam",
                "Cuộc gọi nghề nghiệp đặc thù",
                "Cuộc gọi từ tổng đài,telesale"
            ],
            stackedSeries1: [
                {
                    name: "Số lượng cuộc gọi 1 chiều",
                    data: [5, 3, 4, 2]
                },
                {
                    name: "Số lượng cuộc gọi 2 chiều",
                    data: [2, 2, 3, 1]
                }
            ],
            stackedPlotOptions1: {
                column: {
                    stacking: "percent"
                }
            },

            //Highchart 4
            areaSeries1: [
                {
                    name: "<10s",
                    data: [50, 31, 64, 14]
                },
                {
                    name: ">60s",
                    data: [91, 20, 33, 55]
                },
                {
                    name: "10s->60s",
                    data: [18, 9, 13, 68]
                }
            ],
            areaPlotOption1: {
                area: {
                    fillOpacity: 0.5
                }
            },
            areaCategories1: [
                "Cuộc gọi bình thường",
                "Cuộc gọi nghi ngờ spam",
                "Cuộc gọi nghề nghiệp đặc thù",
                "Cuộc gọi từ tổng đài,telesale"
            ],

            //Highchart 5
            barSeries1: [{ data: [] }],
            barPlotOption1: {
                series: {
                    stacking: "normal"
                },
                column: {
                    stacking: "percent"
                }
            },
            barCategories1: [
                "Cuộc gọi bình thường",
                "Cuộc gọi nghi ngờ spam",
                "Cuộc gọi nghề nghiệp đặc thù",
                "Cuộc gọi từ tổng đài,telesale"
            ]
        };
    },
    mounted() {
        this.getChartData5();
    },
    methods: {
        getChartData5() {
            var response = [26, 56, 89, 12];
            var arr = response.sort().reverse(); //sx mang tu lon den be
            var max = arr[0];
            var series = [];

            arr.forEach(function(value) {
                series.push([parseInt(((value / max) * 100).toFixed(1))]);
            });

            this.barSeries1[0].data = series;
        }
    }
};
</script>

<style scoped></style>
