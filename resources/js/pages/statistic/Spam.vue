<template>
    <div>
        <div class="row">
            <div class="col-12">
                <portlet title="Báo cáo số lượng cuộc gọi nghi ngờ spam">
                    <another-highcharts
                        :point-width="20"
                        :series="series"
                        :chart-type="'line'"
                        :plot-options="linePlotOption1"
                        :has-legend="true"
                        :compare-series="true"
                    ></another-highcharts>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
// import Portlet from "../../components/common/Portlet";
import axios from "axios";

export default {
    name: "Spam",
    middleware: "auth",
    data() {
        return {
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
    mounted() {
        this.getData();
    },
    methods: {
        async getData() {
            try {
                const { data } = await axios.post(
                    "statistic/suspect-spam-chat/get-data"
                );
                let seriesData = Object.keys(data.data).map(key => {
                    return [key, data.data[key]];
                });

                this.series[0].data = seriesData;
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>

<style scoped></style>
