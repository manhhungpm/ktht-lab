<template>
    <div>
        <div class="row">
            <div class="time-filter">
                <month-range
                    v-model="timeFilter"
                    v-validate="'withinAYear'"
                    :name="'monthRange'"
                    :label="$t('label.time')"
                    :error="errors.first('monthRange')"
                ></month-range>
            </div>
            <div class="col-12">
                <portlet title="Báo cáo tổng thời lượng cuộc gọi thuê bao">
                    <another-highcharts
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
                    title="Báo cáo tổng các cuộc gọi có thời lượng <10s, 10s->60s, >60s trong tháng"
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
        <user-chosen :multiple="true"></user-chosen>
    </div>
</template>

<script>
import Portlet from "../../components/common/Portlet";
import AnotherHighcharts from "../../components/common/AnotherHighcharts";
import moment from "moment";
import axios from "axios";
import MonthRange from "../../components/elements/filter/MonthRange";
import UserChosen from "../../components/elements/chosens/UserChosen";

export default {
    name: "TimeCall",
    components: {UserChosen, MonthRange, AnotherHighcharts, Portlet },
    middleware: "auth",
    data() {
        return {
            //Highchart 2
            // columnSeries1: [
            //     { name: "Cuộc gọi bình thường", data: [50] },
            //     { name: "Cuộc gọi nghi ngờ spam", data: [89] },
            //     { name: "Cuộc gọi nghề nghiệp đặc thù", data: [32] },
            //     { name: "Cuộc gọi từ tổng đài,telesale", data: [66] }
            // ],
            // columnPlotOptions1: {
            //     column: {
            //         dataLabels: {
            //             enabled: false
            //         },
            //         minPointLength: 5
            //     }
            // },
            // columnCategories1: ["Tháng 1"],
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
                    minPointLength: 5
                }
            },
            columnCategories1: ["Tháng 1"],

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

            //Filter
            timeFilter: [
                moment()
                    .startOf("month")
                    .subtract(12, "month")
                    .format("YYYY-MM-DD"),
                moment()
                    .startOf("month")
                    .subtract(1, "month")
                    .format("YYYY-MM-DD")
            ]
        };
    },
    watch: {
        timeFilter() {
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.getData();
                }
            });
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        async getData() {
            try {
                const { data } = await axios.post(
                    "statistic/type-duration-msisdn/get-data",
                    {
                        from: this.timeFilter[0],
                        to: this.timeFilter[1]
                    }
                );
                // let seriesData = Object.keys(data.data).map(key => {
                //     return [key, data.data[key]];
                // });
                //
                // this.series[0].data = seriesData;

                console.log(data.data);
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
