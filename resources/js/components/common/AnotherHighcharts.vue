<template>
    <div ref="chart" class="highcharts"></div>
</template>

<script>
import Highcharts from "highcharts";
import cloneDeep from "lodash";
import loadDrillDown from "highcharts/modules/drilldown";
import exporting from "highcharts/modules/exporting";
import exportLocal from "highcharts/modules/offline-exporting";

var series;
loadDrillDown(Highcharts);
exporting(Highcharts);
exportLocal(Highcharts);

export default {
    name: "AnotherHighcharts",
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
                "#34bfa3",
                "#f4516c",
                "#5867dd",
                "#ffb822",
                "#97CAFA",
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
                "#a6c96a",
                "#2f7ed8"
            ]
        },
        series: {
            type: Array,
            default: () => []
        },
        drilldown: {
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
        exportTitle: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            chart: null
        };
    },
    watch: {
        exportTitle(val) {
            this.chart.update({
                exporting: {
                    chartOptions: {
                        title: {
                            text: val
                        }
                    }
                }
            });
        },
        series: {
            handler(newVal, oldVal) {
                // console.log(newVal);
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
        },
        drilldown: {
            handler(val) {
                this.chart.options.drilldown.series = val;
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
                lang: {
                    numericSymbols: ["k", "M", "B", "T", "P", "E"],
                    thousandsSep: ",",
                    decimalPoint: "."
                }
            });

            let options = {
                credits: {
                    enabled: false
                },
                chart: {
                    backgroundColor: "transparent",
                    marginTop: this.marginTop,
                    marginBottom: this.hasLegend ? 100 : 75,
                    marginLeft: this.horizontalMargin,
                    marginRight: this.horizontalMargin,
                    type: this.chartType,
                    height: this.chartHeight
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
                    series: {
                        cursor: "pointer",
                        pointWidth: this.pointWidth,
                        events: {
                            click: this.seriesClicked,
                            legendItemClick: function() {
                                if ($this.compareSeries) {
                                    if (this.visible) {
                                        this.setData([], false);
                                    } else {
                                        this.setData(
                                            [$this.series[this.index].data[0]],
                                            false
                                        );
                                    }
                                }
                            }
                        },
                        borderWidth: 0
                    }
                },
                xAxis: {
                    type: "category",
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
                    softMax: 100,
                    minRange: 1,
                    title: {
                        text: ""
                    }
                },
                legend: {
                    enabled: this.hasLegend
                },
                series: series,
                drilldown: {
                    series: this.drilldown,
                    allowPointDrilldown: false,
                    drillUpButton: {
                        position: {
                            x: -15,
                            y: -45
                        }
                    }
                },
                exporting: {
                    enabled: this.exporting,
                    sourceWidth: 1500,
                    sourceHeight: 500,
                    scale: 1,
                    chartOptions: {
                        title: {
                            text: this.exportTitle
                        }
                    }
                }
            };

            Object.assign(options.plotOptions, this.plotOptions);

            this.chart = Highcharts.chart(this.$refs.chart, options);
        },
        exportChart(filename, title = null) {
            this.chart.exportChartLocal({
                filename: filename,
                type: "image/png",
                sourceWidth: 1500,
                sourceHeight: 500,
                // scale: 1,
                chartOptions: {
                    title: {
                        text: this.exportTitle
                    }
                }
            });
        }
    }
};
</script>
