<template>
    <div>
        <div class="row">
            <div class="time-filter">
                <the-date-range
                    v-model="timeFilter"
                    :label="'Chọn ngày'"
                    :format="'dd/MM/yyyy'"
                    :value-format="'yyyy-MM-dd'"
                    :disabled-date="'greaterThanToday'"
                    :name-shortcut="['last_7_days', 'last_30_days']"
                    :shortcut="true"
                ></the-date-range>
            </div>
            <div class="col-12">
                <portlet
                    style="height: 550px; "
                    :title="$t('statistic.sum_call.type_number_msisdn.title')"
                >
                    <another-highcharts
                        :series="pieSeries1"
                        :plot-options="plotOptionPie1"
                        :tooltip-format="'<b>{point.percentage:.1f}%</b>'"
                        :chart-height="450"
                        :margin-top="50"
                        :exporting="true"
                    ></another-highcharts>
                </portlet>
            </div>
            <div class="col-6">
                <portlet
                    :title="$t('statistic.sum_call.type_percent_call.title')"
                    style="height: 550px; "
                >
                    <highchart-stacked-column
                        :categories="stackedCategories1"
                        :series="stackedSeries1"
                        :point-width="45"
                        :chart-type="'column'"
                        :has-legend="true"
                        :tooltip-format="
                            '{series.name}: <b>{point.percentage:.1f}%</b>'
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
                    :title="$t('statistic.sum_call.type_number_out_call.title')"
                >
                    <another-highcharts
                        :chart-type="'bar'"
                        :categories="barCategories1"
                        :series="barSeries1"
                        :plot-options="barPlotOption1"
                        :exporting="true"
                        :tooltip-format="'Số lượng:<b> {point.y}%</b>'"
                        :horizontal-margin="92"
                        :chart-height="432"
                    >
                    </another-highcharts>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "~/components/common/Portlet";
import AnotherHighcharts from "~/components/common/AnotherHighcharts";
import HighchartStackedColumn from "~/components/common/HighchartStackedColumn";
import moment from "moment";
import axios from "axios";
import TheDateRange from "../../../components/common/TheDateRange";

export default {
    name: "SumCall",
    components: {
        TheDateRange,
        HighchartStackedColumn,
        AnotherHighcharts,
        Portlet
    },
    middleware: "auth",
    data() {
        return {
            //Highchart 1
            pieSeries1: [
                {
                    data: [{}]
                }
            ],
            plotOptionPie1: {
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        style: {
                            fontSize: "13px"
                        }
                    },
                    innerSize: "30%",
                    showInLegend: true
                },
                series: {
                    animation: {
                        duration: 2000
                    }
                }
            },

            //Highchart 3
            stackedSeries1: [{}],
            stackedCategories1: [
                "Cuộc gọi bình thường",
                "Cuộc gọi nghi ngờ spam",
                "Cuộc gọi nghề nghiệp đặc thù",
                "Cuộc gọi từ tổng đài,telesale"
            ],
            stackedPlotOptions1: {
                column: {
                    stacking: "percent"
                }
            },

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
            barCategories1: [],

            //Filter
            timeFilter: [
                moment()
                    .startOf("day")
                    .subtract(1, "days")
                    .format("YYYY-MM-DD"),
                moment()
                    .startOf("day")
                    .format("YYYY-MM-DD")
            ]
        };
    },
    watch: {
        timeFilter() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.getDataBarHighchart();
                    this.getDataPieHighchart();
                    this.getDataStackedHighchart();
                }
            });
        }
    },
    mounted() {
        this.getDataBarHighchart();
        this.getDataPieHighchart();
        this.getDataStackedHighchart();
    },
    methods: {
        setPercent(res) {
            var max = res[0];
            var series = [];

            res.forEach(function(value) {
                series.push([parseInt(((value / max) * 100).toFixed(1))]);
            });

            return series;
        },
        async getDataPieHighchart() {
            try {
                const { data } = await axios.post(
                    "statistic/type-number-msisdn/get-data",
                    { time_filter: this.timeFilter }
                );
                var arr = data.data;
                var series1 = 0,
                    series2 = 0,
                    series3 = 0,
                    series4 = 0;
                if (arr.length != 0) {
                    arr.forEach(function(e) {
                        switch (e.msisdn_type_id) {
                            case 1:
                                series1 += e.value;
                                break;
                            case 2:
                                series2 += e.value;
                                break;
                            case 3:
                                series3 += e.value;
                                break;
                            case 4:
                                series4 += e.value;
                                break;
                        }
                    });
                    this.pieSeries1 = [
                        {
                            data: [
                                {
                                    name:
                                        "Số lượng thuê bao nhóm cuộc gọi bình thường",
                                    y: series1,
                                    sliced: true,
                                    selected: true
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm nghi ngờ spam",
                                    y: series2
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm nghề nghiệp đặc thù",
                                    y: series3
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm tổng đài, telesale",
                                    y: series4
                                }
                            ]
                        }
                    ];
                } else {
                    this.pieSeries1 = [
                        {
                            data: [
                                {
                                    name:
                                        "Số lượng thuê bao nhóm cuộc gọi bình thường",
                                    y: 0,
                                    sliced: true,
                                    selected: true
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm nghi ngờ spam",
                                    y: 0
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm nghề nghiệp đặc thù",
                                    y: 0
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm tổng đài, telesale",
                                    y: 0
                                }
                            ]
                        }
                    ];
                }
            } catch (e) {
                console.log(e);
            }
        },
        async getDataStackedHighchart() {
            try {
                const { data } = await axios.post(
                    "statistic/type-percent-call/get-data",
                    { time_filter: this.timeFilter }
                );
                var arr = data.data;
                var oneWay = [0, 0, 0, 0];
                var twoWay = [0, 0, 0, 0];
                if (arr.length != 0) {
                    arr.forEach(function(e) {
                        switch (e.msisdn_type_id) {
                            case 1:
                                oneWay[0] += e.value_one_way;
                                twoWay[0] += e.value_two_way;
                                break;
                            case 2:
                                oneWay[1] += e.value_one_way;
                                twoWay[1] += e.value_two_way;
                                break;
                            case 3:
                                oneWay[2] += e.value_one_way;
                                twoWay[2] += e.value_two_way;
                                break;
                            case 4:
                                oneWay[3] += e.value_one_way;
                                twoWay[3] += e.value_two_way;
                                break;
                        }
                    });
                } else {
                    arr.forEach(function() {
                        oneWay.push(0, 0, 0, 0);
                        twoWay.push(0, 0, 0, 0);
                    });
                }
                this.stackedSeries1 = [
                    {
                        name: "Số lượng cuộc gọi 1 chiều",
                        data: oneWay
                    },
                    {
                        name: "Số lượng cuộc gọi 2 chiều",
                        data: twoWay
                    }
                ];
            } catch (e) {
                console.log(e);
            }
        },
        async getDataBarHighchart() {
            try {
                const { data } = await axios.post(
                    "statistic/type-number-out-call/get-data",
                    { time_filter: this.timeFilter }
                );
                var arr = data.data;
                var nameMsisdn = [];
                var valueMsisdn = [];

                if (arr.length != 0) {
                    var result = [
                        {
                            name: "Cuộc gọi bình thường",
                            value: 0
                        },
                        {
                            name: "Cuộc gọi nghi ngờ spam",
                            value: 0
                        },
                        {
                            name: "Cuộc gọi nghề nghiệp đặc thù",
                            value: 0
                        },
                        {
                            name: "Cuộc gọi từ tổng đài, telesale",
                            value: 0
                        }
                    ];
                    arr.forEach(function(e) {
                        switch (e.msisdn_type_id) {
                            case 1:
                                result[0].value += e.value;
                                break;
                            case 2:
                                result[1].value += e.value;
                                break;
                            case 3:
                                result[2].value += e.value;
                                break;
                            case 4:
                                result[3].value += e.value;
                                break;
                        }
                    });
                    //sắp xếp object từ lớn đến bé
                    result
                        .sort(function(a, b) {
                            return a.value - b.value;
                        })
                        .reverse();
                    result.forEach(function(e) {
                        nameMsisdn.push(e.name);
                        valueMsisdn.push(e.value);
                    });
                    this.barCategories1 = nameMsisdn;
                    this.barSeries1[0].data = this.setPercent(valueMsisdn);
                } else {
                    this.barSeries1[0].data = [0, 0, 0, 0];
                }
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>

<style scoped>
.time-filter {
    margin: auto;
    margin-bottom: 15px;
}
</style>
