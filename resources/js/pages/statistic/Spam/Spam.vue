<template>
    <div>
        <div class="row">
            <div class="time-filter">
                <!--<month-range-->
                <!--v-model="timeFilter"-->
                <!--v-validate="'withinAYear'"-->
                <!--:name="'monthRange'"-->
                <!--:label="$t('label.time')"-->
                <!--:error="errors.first('monthRange')"-->
                <!--&gt;</month-range>-->
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
                <portlet title="Báo cáo số lượng cuộc gọi nghi ngờ spam">
                    <another-highcharts
                        :point-width="20"
                        :series="series"
                        :chart-type="'line'"
                        :plot-options="linePlotOption1"
                        :has-legend="true"
                        :compare-series="false"
                    ></another-highcharts>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
// import Portlet from "../../components/common/Portlet";
import axios from "axios";
import moment from "moment";
import MonthRange from "~/components/elements/filter/MonthRange";

export default {
    name: "Spam",
    components: { MonthRange },
    middleware: "auth",
    data() {
        return {
            timeFilter: [
                moment()
                    .startOf("day")
                    .subtract(1, "days")
                    .format("YYYY-MM-DD"),
                moment()
                    .startOf("day")
                    .format("YYYY-MM-DD")
            ],
            series: [
                {
                    name: "Nhóm nghi ngờ Spam",
                    data: []
                }
            ],
            linePlotOption1: {
                line: {
                    dataLabels: {
                        enabled: true
                    }
                },
                enableMouseTracking: false
            }
        };
    },
    watch: {
        timeFilter(data) {
            this.$validator.validateAll().then(result => {
                console.log(data);
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
                    "statistic/suspect-spam-chat/get-data",
                    {
                        time_filter: this.timeFilter
                    }
                );
                var arr = data.data;

                let seriesData = Object.keys(arr).map(key => {
                    return [key, arr[key]];
                });

                this.series[0].data = seriesData;
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>

<style>
.time-filter {
    margin: auto;
    margin-bottom: 15px;
}
</style>
