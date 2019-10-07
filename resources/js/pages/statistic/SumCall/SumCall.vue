<template>
    <div>
        <div class="row">
            <div class="time-filter">
                <label style="margin-right: 30px">Chọn tháng</label>
                <el-date-picker
                    v-model="timeFilter"
                    type="month"
                    placeholder="Chọn 1 tháng"
                    value-format="yyyy-MM-dd"
                    :default-time="defaultTime"
                >
                </el-date-picker>
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

export default {
    name: "SumCall",
    components: { HighchartStackedColumn, AnotherHighcharts, Portlet },
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
            defaultTime: moment()
                .startOf("month")
                .format("YYYY-MM"),
            timeFilter: null
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
        parseName(value) {
            var name;
            switch (value) {
                case 1:
                    name = "Cuộc gọi bình thường";
                    break;
                case 2:
                    name = "Cuộc gọi nghi ngờ spam";
                    break;
                case 3:
                    name = "Cuộc gọi nghề nghiệp đặc thù ";
                    break;
                case 4:
                    name = "Cuộc gọi từ tổng đài, telesale";
                    break;
            }
            return name;
        },
        async getDataPieHighchart() {
            try {
                const { data } = await axios.post(
                    "statistic/type-number-msisdn/get-data",
                    { time_filter: this.timeFilter }
                );
                var arr = data.data;

                if (arr.length != 0) {
                    this.pieSeries1 = [
                        {
                            data: [
                                {
                                    name:
                                        "Số lượng thuê bao nhóm cuộc gọi bình thường",
                                    y: arr[0].value,
                                    sliced: true,
                                    selected: true
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm nghi ngờ spam",
                                    y: arr[1].value
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm nghề nghiệp đặc thù",
                                    y: arr[2].value
                                },
                                {
                                    name:
                                        "Số lượng thuê bao nhóm tổng đài, telesale",
                                    y: arr[3].value
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
                var oneWay = [];
                var twoWay = [];
                if (arr.length != 0) {
                    arr.forEach(function(e) {
                        oneWay.push(e.value_one_way);
                        twoWay.push(e.value_two_way);
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
                    //sắp xếp object từ lớn đến bé
                    arr.sort(function(a, b) {
                        return a.value - b.value;
                    }).reverse();
                    console.log(arr);

                    arr.forEach(function(e) {
                        nameMsisdn.push(this.parseName(e.msisdn_type_id));
                        valueMsisdn.push(e.value);
                    }, this);

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
