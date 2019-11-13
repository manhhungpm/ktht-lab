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
                var seriesData = [0, 0, 0, 0];

                if (arr.length != 0) {
                    arr.forEach(function(e) {
                        switch (e.msisdn_type_id) {
                            case 1:
                                seriesData[0] += e.value;
                                break;
                            case 2:
                                seriesData[1] += e.value;
                                break;
                            case 3:
                                seriesData[2] += e.value;
                                break;
                            case 4:
                                seriesData[3] += e.value;
                                break;
                        }
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
                    this.columnCategories1.push("Kết quả");
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
                    this.columnCategories1.push("Kết quả");
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
                    arr.forEach(function(e) {
                        switch (e.call_duration_type_id) {
                            case 1:
                                switch (e.msisdn_type_id) {
                                    case 1:
                                        this.areaSeries1[0].data[0] += e.value;
                                        break;
                                    case 2:
                                        this.areaSeries1[0].data[1] += e.value;
                                        break;
                                    case 3:
                                        this.areaSeries1[0].data[2] += e.value;
                                        break;
                                    case 4:
                                        this.areaSeries1[0].data[3] += e.value;
                                        break;
                                }
                                break;
                            case 2:
                                switch (e.msisdn_type_id) {
                                    case 1:
                                        this.areaSeries1[1].data[0] += e.value;
                                        break;
                                    case 2:
                                        this.areaSeries1[1].data[1] += e.value;
                                        break;
                                    case 3:
                                        this.areaSeries1[1].data[2] += e.value;
                                        break;
                                    case 4:
                                        this.areaSeries1[1].data[3] += e.value;
                                        break;
                                }
                                break;
                            case 3:
                                switch (e.msisdn_type_id) {
                                    case 1:
                                        this.areaSeries1[2].data[0] += e.value;
                                        break;
                                    case 2:
                                        this.areaSeries1[2].data[1] += e.value;
                                        break;
                                    case 3:
                                        this.areaSeries1[2].data[2] += e.value;
                                        break;
                                    case 4:
                                        this.areaSeries1[2].data[3] += e.value;
                                        break;
                                }
                                break;
                        }
                    }, this);
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
