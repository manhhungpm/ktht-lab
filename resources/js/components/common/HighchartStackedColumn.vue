<template>
    <div ref="chart" class="highcharts"></div>
</template>

<script>
import Highcharts from "highcharts";
import cloneDeep from "lodash";
import exporting from "highcharts/modules/exporting";
import exportLocal from "highcharts/modules/offline-exporting";

var series;
exporting(Highcharts);
exportLocal(Highcharts);

export default {
    name: "HighchartStackedColumn",
    props: {
        title: {
            type: String,
            default: ""
        },
        categories: {
            type: Array,
            default: () => undefined
        },
        colors: {
            type: Array,
            default: () => [
                "#5867dd",
                "#f4516c",
                "#34bfa3",
                "#ffb822",
                "#2f7ed8",
                "#0d233a",
                "#8bbc21",
                "#36a3f7",
                "#910000",
                "#1aadce",
                "#492970",
                "#f28f43",
                "#716aca",
                "#77a1e5",
                "#c42525",
                "#a6c96a"
            ]
        },
        series: {
            type: Array,
            default: () => []
        },
        chartType: {
            type: String,
            default: "pie"
        },
        chartHeight: {
            type: [String, Number],
            default: "500"
        },
        tooltipFormat: {
            type: String,
            default: ""
        },
        pointWidth: {
            type: Number,
            default: undefined
        },
        plotOptions: {
            type: Object,
            default: () => {}
        },
        seriesClicked: {
            type: Function,
            default: () => {}
        },
        hasLegend: {
            type: Boolean,
            default: false
        },
        exporting: {
            type: Boolean,
            default: false
        },
        horizontalMargin: {
            type: Number,
            default: 75
        },
        marginTop: {
            type: Number,
            default: 50
        },
        scrollMinWidth: {
            type: Number,
            default: undefined
        },
        compareSeries: {
            type: Boolean,
            default: false
        },
        //
        dataX: {
            type: Array,
            default: () => undefined
        },
        inverted: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            chart: null
        };
    },
    watch: {
        series: {
            handler(newVal, oldVal) {
                if (newVal.length != oldVal.length) {
                    this.chart.destroy();
                    this.initChart();
                } else {
                    let series = _.cloneDeep(newVal);
                    this.chart.update({
                        series: series
                    });
                }
            },
            deep: true
        },
        categories: {
            handler(val) {
                this.chart.update({
                    xAxis: {
                        categories: val
                    }
                });
            },
            deep: true
        }
    },
    mounted() {
        this.initChart();
    },
    methods: {
        initChart() {
            let $this = this;
            let series = _.cloneDeep(this.series);

            Highcharts.setOptions({
                lang: { numericSymbols: ["k", "M", "B", "T", "P", "E"] }
            });

            // fix loi font khi export pdf
            Highcharts.wrap(
                Highcharts.Chart.prototype,
                "exportChartLocal",
                function(proceed, options) {
                    if (options && options.type === "application/pdf") {
                        this.exportChart(options);
                    } else {
                        proceed.call(this, options);
                    }
                }
            );
            //

            let options = {
                credits: {
                    enabled: false
                },
                chart: {
                    backgroundColor: "transparent",
                    marginTop: this.marginTop,
                    marginBottom: this.hasLegend ? 200 : 75,
                    marginLeft: this.horizontalMargin,
                    marginRight: this.horizontalMargin,
                    type: this.chartType,
                    height: this.chartHeight,
                    inverted: this.inverted
                },
                lang: {
                    drillUpText: "Trở lại",
                    downloadCSV: "Download định dạng CSV",
                    downloadJPEG: "Download định dạng JPEG",
                    downloadPDF: "Download định dạng PDF",
                    downloadPNG: "Download định dạng PNG",
                    downloadSVG: "Download định dạng SVG",
                    printChart: "In biểu đồ",
                    numericSymbols: ["k", "M", "B", "T", "P", "E"]
                },
                colors: this.colors,
                title: {
                    text: ""
                },
                subtitle: {
                    text: ""
                },
                tooltip: {
                    pointFormat: this.tooltipFormat
                },
                plotOptions: {
                    column: {
                        stacking: "normal",
                        dataLabels: {
                            enabled: false,
                            color:
                                (Highcharts.theme &&
                                    Highcharts.theme.dataLabelsColor) ||
                                "white"
                        }
                    },
                    series: {
                        pointWidth: this.pointWidth
                    }
                },
                xAxis: {
                    labels: {
                        style: {
                            fontSize: "13px",
                            fontFamily: "Verdana, sans-serif"
                        }
                    },
                    categories: this.categories
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: this.title
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: "bold",
                            color:
                                (Highcharts.theme &&
                                    Highcharts.theme.textColor) ||
                                "gray"
                        }
                    }
                },
                legend: {
                    // enabled: this.hasLegend,
                    align: "center",
                    x: 5,
                    verticalAlign: "bottom",
                    y: -70,
                    floating: false,
                    backgroundColor:
                        (Highcharts.theme && Highcharts.theme.background2) ||
                        "white",
                    borderColor: "#CCC",
                    borderWidth: 0,
                    shadow: false
                },
                series: series,
                exporting: {
                    enabled: this.exporting,
                    sourceWidth: 1500,
                    sourceHeight: 500,
                    scale: 1,
                    chartOptions: {
                        chart: {
                            backgroundColor: "#ffffff"
                        },
                        legend: {
                            itemStyle: {
                                fontFamily: "Roboto"
                            }
                        }
                    },
                    fallbackToExportServer: false
                }
            };

            Object.assign(options.plotOptions, this.plotOptions);

            this.chart = Highcharts.chart(this.$refs.chart, options);
        },
        exportChart(filename) {
            this.chart.exportChartLocal({
                filename: filename,
                type: "image/png",
                sourceWidth: 1500,
                sourceHeight: 500,
                scale: 1,
                chartOptions: {
                    title: {
                        text: "ABC"
                    }
                }
            });
        }
    }
};
</script>

<style scoped></style>
