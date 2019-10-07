<template>
    <div>
        <div class="row">
            <div class="time-filter">
                <label style="margin-right: 30px">Chọn tháng</label>
                <el-date-picker
                    v-model="timeFilter"
                    type="month"
                    placeholder="Pick a month"
                    value-format="yyyy-MM-dd"
                    :default-time="defaultTime"
                >
                </el-date-picker>
            </div>
            <div class="col-12">
                <portlet
                    :title="
                        $t('statistic.time_call.type_duration_msisdn.title')
                    "
                >
                    <another-highcharts
                        id="column1"
                        :chart-type="'column'"
                        :series="columnSeries1"
                        :plot-options="columnPlotOptions1"
                        :tooltip-format="'{series.name}:<b> {point.y}</b>'"
                        :has-legend="true"
                        :point-width="60"
                        :margin-top="20"
                        :categories="columnCategories1"
                        :exporting="true"
                    ></another-highcharts>
                </portlet>
            </div>
            <div class="col-12">
                <portlet
                    :title="$t('statistic.time_call.type_duration_type.title')"
                    style="height: 615px; "
                >
                    <another-highcharts
                        :chart-type="'area'"
                        :series="areaSeries1"
                        :plot-options="areaPlotOption1"
                        :exporting="true"
                        :categories="areaCategories1"
                        :has-legend="true"
                        :tooltip-format="'{series.name}:<b> {point.y}</b>'"
                    ></another-highcharts>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "~/components/common/Portlet";
import AnotherHighcharts from "~/components/common/AnotherHighcharts";
import moment from "moment";
import axios from "axios";

export default {
    name: "TimeCall",
    components: { AnotherHighcharts, Portlet },
    middleware: "auth",
    data() {
        return {
            //Highchart 2
            columnSeries1: [{}],
            columnPlotOptions1: {
                column: {
                    dataLabels: {
                        enabled: false
                    },
                    minPointLength: 5
                }
            },
            columnCategories1: [],

            //Highchart 4
            areaSeries1: [{}],
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
                    this.getDataColumnHighchart();
                    this.getDataColumnArea();
                }
            });
        }
    },
    mounted() {
        this.getDataColumnHighchart();
        this.getDataColumnArea();
    },
    methods: {
        async getDataColumnHighchart() {
            try {
                const { data } = await axios.post(
                    "statistic/type-duration-msisdn/get-data",
                    { time_filter: this.timeFilter }
                );
                var arr = data.data;
                var seriesData = [];
                // console.log(arr);

                if (arr.length != 0) {
                    arr.forEach(function(e) {
                        seriesData.push(e.value);
                    });
                    this.columnSeries1 = [
                        {
                            name: "Cuộc gọi bình thường",
                            data: [seriesData[0]]
                        },
                        {
                            name: "Cuộc gọi nghi ngờ spam",
                            data: [seriesData[1]]
                        },
                        {
                            name: "Cuộc gọi nghề nghiệp đặc thù",
                            data: [seriesData[2]]
                        },
                        {
                            name: "Cuộc gọi từ tổng đài, telesale",
                            data: [seriesData[3]]
                        }
                    ];
                    this.columnCategories1.splice(0, 1);
                    this.columnCategories1.push(arr[0].month);
                } else {
                    this.columnSeries1 = [
                        {
                            name: "Cuộc gọi bình thường",
                            data: [0]
                        },
                        {
                            name: "Cuộc gọi nghi ngờ spam",
                            data: [0]
                        },
                        {
                            name: "Cuộc gọi nghề nghiệp đặc thù",
                            data: [0]
                        },
                        {
                            name: "Cuộc gọi từ tổng đài, telesale",
                            data: [0]
                        }
                    ];
                    this.columnCategories1.splice(0, 1);
                    this.columnCategories1.push("Không có");
                }
            } catch (e) {
                console.log(e);
            }
        },
        async getDataColumnArea() {
            try {
                const { data } = await axios.post(
                    "statistic/type-duration-type/get-data",
                    { time_filter: this.timeFilter }
                );
                var arr = data.data;

                if (arr.length != 0) {
                    this.areaSeries1 = [
                        {
                            name: "<10s",
                            data: [
                                arr[0].value,
                                arr[1].value,
                                arr[2].value,
                                arr[3].value
                            ]
                        },
                        {
                            name: ">60s",
                            data: [
                                arr[4].value,
                                arr[5].value,
                                arr[6].value,
                                arr[7].value
                            ]
                        },
                        {
                            name: "10s->60s",
                            data: [
                                arr[8].value,
                                arr[9].value,
                                arr[10].value,
                                arr[11].value
                            ]
                        }
                    ];
                } else {
                    this.areaSeries1 = [
                        {
                            name: "<10s",
                            data: [0, 0, 0, 0]
                        },
                        {
                            name: ">60s",
                            data: [0, 0, 0, 0]
                        },
                        {
                            name: "10s->60s",
                            data: [0, 0, 0, 0]
                        }
                    ];
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
