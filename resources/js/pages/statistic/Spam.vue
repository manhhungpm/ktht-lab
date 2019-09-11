<template>
    <div>
        <div class="row">
            <div class="col-12">
                <portlet title="Báo cáo số lượng cuộc gọi nghi ngờ spam">
                    <another-highcharts
                        :point-width="20"
                        :series="lineSeries1"
                        :chart-type="'line'"
                        :plot-options="linePlotOption1"
                        :has-legend="true"
                        :compare-series="true"
                        :categories="lineCategories1"
                    ></another-highcharts>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "../../components/common/Portlet";
import AnotherHighcharts from "../../components/common/AnotherHighcharts";
export default {
    name: "Spam",
    components: { AnotherHighcharts, Portlet },
    middleware: "auth",
    data() {
        return {
            lineSeries1: [
                {
                    name: "Nhóm nghi ngờ Spam",
                    data: [
                        43934,
                        52503,
                        57177,
                        69658,
                        97031,
                        119931,
                        137133,
                        154175,
                        97031,
                        119931,
                        137133,
                        154175
                    ]
                }
            ],
            linePlotOption1: {
                line: {
                    dataLabels: {
                        enabled: true
                    }
                },
                enableMouseTracking: false
            },
            lineCategories1: [
                "Tháng 1",
                "Tháng 2",
                "Tháng 3",
                "Tháng 4",
                "Tháng 5",
                "Tháng 6",
                "Tháng 7",
                "Tháng 8",
                "Tháng 9",
                "Tháng 10",
                "Tháng 11",
                "Tháng 12"
            ]
        };
    }
};
</script>

<style scoped></style>
