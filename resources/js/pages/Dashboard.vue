<template>
    <div>
        <div class="m-portlet">
            <div class="m-portlet__body  m-portlet__body--no-padding">
                <div class="row m-row--no-padding m-row--col-separator-xl">
                    <div class="col-xl-4">
                        <!--begin:: Widgets/Stats2-1 -->
                        <div class="m-widget1">
                            <div class="m-widget1__item">
                                <div
                                    class="row m-row--no-padding align-items-center"
                                >
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            Doanh thu năm {{ currentTime.year }}
                                        </h3>
                                        <span class="m-widget1__desc"
                                            >Tổng doanh thu trong năm</span
                                        >
                                    </div>
                                    <div class="col m--align-right">
                                        <span
                                            class="m-widget1__number m--font-brand"
                                            >{{
                                                numberFormat(thisYearRevenue)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="m-widget1__item">
                                <div
                                    class="row m-row--no-padding align-items-center"
                                >
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            Doanh thu tháng
                                            {{ currentTime.lastMonth }}
                                        </h3>
                                        <span class="m-widget1__desc"
                                            >Tổng doanh thu</span
                                        >
                                    </div>
                                    <div class="col m--align-right">
                                        <span
                                            class="m-widget1__number m--font-info"
                                            >{{
                                                numberFormat(thisMonthRevenue)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="m-widget1__item">
                                <div
                                    class="row m-row--no-padding align-items-center"
                                >
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            So với tháng
                                            {{ currentTime.twoMonthAgo }}
                                        </h3>
                                        <span class="m-widget1__desc"
                                            >Biến động doanh thu so với tháng
                                            {{ currentTime.twoMonthAgo }}</span
                                        >
                                    </div>
                                    <div class="col m--align-right">
                                        <span
                                            v-if="
                                                thisMonthRevenue >
                                                    lastMonthRevenue
                                            "
                                            class="m-widget1__number m--font-success"
                                        >
                                            <i class="la la-arrow-up"></i>
                                            {{ lastMonthRevenueDifference }}
                                        </span>

                                        <span
                                            v-else
                                            class="m-widget1__number m--font-danger"
                                        >
                                            <i class="la la-arrow-down"></i>
                                            {{ lastMonthRevenueDifference }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Stats2-1 -->
                    </div>
                    <div class="col-xl-4">
                        <!--begin:: Widgets/Daily Sales-->
                        <div class="m-widget14">
                            <div class="m-widget14__header m--margin-bottom-30">
                                <h3 class="m-widget14__title">
                                    Doanh thu 12 tháng gần nhất
                                </h3>
                            </div>

                            <another-highcharts
                                :series="revenueSeries"
                                :chart-type="'column'"
                                :tooltip-format="'<b>{point.y} VND</b>'"
                                :chart-height="160"
                                :horizontal-margin="40"
                                :margin-top="20"
                                :plot-options="plotOptions"
                            />
                            <!--<another-highcharts-->
                            <!--:series="series_1"-->
                            <!--:chart-type="'column'"-->
                            <!--:tooltip-format="'Population in 2018: <b>{point.y:.1f} millions</b>'"-->

                            <!--style="height: 160px"-->

                            <!--/>-->
                        </div>

                        <!--end:: Widgets/Daily Sales-->
                    </div>
                    <div class="col-xl-4">
                        <div class="m-widget14">
                            <div class="m-widget14__header">
                                <h3 class="m-widget14__title">
                                    Top đối tác theo doanh thu tháng
                                    {{ currentTime.lastMonth }}
                                </h3>
                            </div>
                            <div
                                v-if="thisYearTopRevenue.length > 0"
                                class="m-widget6"
                            >
                                <div class="m-widget6__head">
                                    <div class="m-widget6__item">
                                        <span class="m-widget6__caption"
                                            >STT</span
                                        >
                                        <span class="m-widget6__caption"
                                            >Đối tác</span
                                        >
                                        <span
                                            class="m-widget6__caption m--align-right"
                                            >Doanh thu</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-if="thisYearTopRevenue.length > 0"
                                    class="m-widget6__body"
                                >
                                    <div
                                        v-for="(value,
                                        index) in thisYearTopRevenue"
                                        :key="index"
                                        class="m-widget6__item"
                                    >
                                        <span class="m-widget6__text">{{
                                            index + 1
                                        }}</span>
                                        <span class="m-widget6__text">{{
                                            value.partner_name
                                        }}</span>
                                        <span
                                            class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                                            >{{
                                                numberFormat(value.revenue)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div v-else class="m-widget6">
                                Hiện chưa có dữ liệu để thống kê!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-portlet">
            <div class="m-portlet__body  m-portlet__body--no-padding">
                <div class="row m-row--no-padding m-row--col-separator-xl">
                    <div class="col-xl-4">
                        <!--begin:: Widgets/Stats2-1 -->
                        <div class="m-widget1">
                            <div class="m-widget1__item">
                                <div
                                    class="row m-row--no-padding align-items-center"
                                >
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            Sản lượng năm {{ currentTime.year }}
                                        </h3>
                                        <span class="m-widget1__desc"
                                            >Tổng sản lượng trong năm</span
                                        >
                                    </div>
                                    <div class="col m--align-right">
                                        <span
                                            class="m-widget1__number m--font-brand"
                                            >{{
                                                numberFormat(thisYearQuantity)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="m-widget1__item">
                                <div
                                    class="row m-row--no-padding align-items-center"
                                >
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            Sản lượng tháng này
                                        </h3>
                                        <span class="m-widget1__desc"
                                            >Tổng sản lượng tháng này</span
                                        >
                                    </div>
                                    <div class="col m--align-right">
                                        <span
                                            class="m-widget1__number m--font-info"
                                            >{{
                                                numberFormat(thisMonthQuantity)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="m-widget1__item">
                                <div
                                    class="row m-row--no-padding align-items-center"
                                >
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            So với tháng trước
                                        </h3>
                                        <span class="m-widget1__desc"
                                            >Biến động sản lượng so với tháng
                                            trước</span
                                        >
                                    </div>
                                    <div class="col m--align-right">
                                        <span
                                            v-if="
                                                thisMonthQuantity >
                                                    lastMonthQuantity
                                            "
                                            class="m-widget1__number m--font-success"
                                        >
                                            <i class="la la-arrow-up"></i>
                                            {{ lastMonthDifference }}
                                        </span>

                                        <span
                                            v-else
                                            class="m-widget1__number m--font-danger"
                                        >
                                            <i class="la la-arrow-down"></i>
                                            {{ lastMonthDifference }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end:: Widgets/Stats2-1 -->
                    </div>
                    <div class="col-xl-4">
                        <!--begin:: Widgets/Daily Sales-->
                        <div class="m-widget14">
                            <div class="m-widget14__header m--margin-bottom-30">
                                <h3 class="m-widget14__title">
                                    Sản lượng 12 tháng gần nhất
                                </h3>
                            </div>

                            <!--<the-highcharts :series="series_1" style="height: 160px"/>-->
                            <another-highcharts
                                :series="quantitySeries"
                                :chart-type="'column'"
                                :tooltip-format="'<b>{point.y}</b>'"
                                :chart-height="160"
                                :horizontal-margin="40"
                                :margin-top="20"
                                :plot-options="plotOptions"
                            />
                        </div>

                        <!--end:: Widgets/Daily Sales-->
                    </div>
                    <div class="col-xl-4">
                        <!--begin:: Widgets/Profit Share-->
                        <div class="m-widget14">
                            <div class="m-widget14__header">
                                <h3 class="m-widget14__title">
                                    Top đối tác theo sản lượng tháng
                                    {{ currentTime.month }}
                                </h3>
                            </div>
                            <div
                                v-if="thisMonthTopPartners.length > 0"
                                class="m-widget6"
                            >
                                <div class="m-widget6__head">
                                    <div class="m-widget6__item">
                                        <span class="m-widget6__caption"
                                            >STT</span
                                        >
                                        <span class="m-widget6__caption"
                                            >Đối tác</span
                                        >
                                        <span
                                            class="m-widget6__caption m--align-right"
                                            >Sản lượng</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-if="thisMonthTopPartners.length > 0"
                                    class="m-widget6__body"
                                >
                                    <div
                                        v-for="(value,
                                        index) in thisMonthTopPartners"
                                        :key="index"
                                        class="m-widget6__item"
                                    >
                                        <span class="m-widget6__text">{{
                                            index + 1
                                        }}</span>
                                        <span class="m-widget6__text">{{
                                            value.partner_name
                                        }}</span>
                                        <span
                                            class="m-widget6__text m--align-right m--font-boldest m--font-brand"
                                            >{{
                                                numberFormat(value.success)
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                            <div v-else class="m-widget6">
                                Hiện chưa có dữ liệu để thống kê!
                            </div>
                        </div>

                        <!--end:: Widgets/Profit Share-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import axios from "axios";
import numeral from "numeral";
export default {
    layout: "default",
    middleware: "auth",
    metaInfo() {
        return { title: "Dashboard" };
    },
    data: () => ({
        thisYearQuantity: null,
        thisMonthQuantity: null,
        lastMonthQuantity: null,

        thisYearRevenue: null,
        thisMonthRevenue: null,
        lastMonthRevenue: null,
        thisMonthTopPartners: [],
        thisYearTopRevenue: [],
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: false
                },
                minPointLength: 3
            }
        },
        quantitySeries: [
            {
                name: "Sản lượng",
                color: "#34bfa3",
                data: []
            }
        ],
        revenueSeries: [
            {
                name: "Doanh thu",
                color: "#34bfa3",
                data: []
            }
        ]
    }),
    computed: {
        lastMonthDifference() {
            return numeral(
                Math.abs(this.thisMonthQuantity - this.lastMonthQuantity)
            ).format("0,0");
        },

        lastMonthRevenueDifference() {
            return numeral(
                Math.abs(this.thisMonthRevenue - this.lastMonthRevenue)
            ).format("0,0");
        },

        currentTime() {
            let today = moment();
            return {
                day: today.format("DD/MM/YYYY"),
                month: today.format("MM/YYYY"),
                year: today.format("YYYY"),
                lastMonth: today.subtract(1, "months").format("MM/YYYY"),
                twoMonthAgo: today.subtract(1, "months").format("MM/YYYY")
            };
        }
    },
    mounted() {},
    methods: {
        async getChartData() {
            let res = await axios.post(
                "/api/statistic/sender-id-quantity/last-12-months-chart"
            );
            if (res) {
                let series = [];
                res.data.data.forEach(e => {
                    series.push([e.date, parseInt(e.success)]);
                });
                this.quantitySeries[0].data = series;
            }

            let revenue = await axios.post(
                "/api/statistic/partner-revenue/last-12-months-chart"
            );
            if (revenue) {
                let series = [];
                revenue.data.data.forEach(e => {
                    series.push([e.date, parseInt(e.revenue)]);
                });
                this.revenueSeries[0].data = series;
            }
        },

        async getSenderIdStats() {
            let { data } = await axios.post(
                "/api/statistic/sender-id-quantity/this-year-stats"
            );
            if (data) {
                this.thisYearQuantity = parseInt(data.data.this_year);
                this.thisMonthQuantity = parseInt(data.data.this_month);
                this.lastMonthQuantity = parseInt(data.data.last_month);
            }
        },

        async getRevenueStats() {
            let { data } = await axios.post(
                "/api/statistic/partner-revenue/this-year-stats"
            );
            if (data) {
                this.thisYearRevenue = parseInt(data.data.this_year);
                this.thisMonthRevenue = parseInt(data.data.this_month);
                this.lastMonthRevenue = parseInt(data.data.last_month);
            }
        },

        async getPartnerStats() {
            let { data } = await axios.post(
                "/api/statistic/partner-quantity/this-month-stats"
            );
            if (data) {
                this.thisMonthTopPartners = data.data;
            }
        },

        async getRevenueTop() {
            let { data } = await axios.post(
                "/api/statistic/partner-revenue/this-year-top"
            );
            if (data) {
                this.thisYearTopRevenue = data.data;
            }
        },

        numberFormat(number) {
            return numeral(parseInt(number)).format("0,0");
        }
    }
};
</script>
