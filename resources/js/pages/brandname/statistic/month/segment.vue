<template>
    <div>
        <div class="row">
            <div class="col-12">
                <date-filter-month
                    :default-time="defaultTime"
                    :label="$t('label.select_month')"
                    :placeholder="$t('label.select_month_placeholder')"
                    :export-url="'/brandname/report-month/segment/export'"
                    value-format="yyyy-MM"
                    :exportable="true"
                    type="month"
                    @search="filterTime"
                ></date-filter-month>
            </div>
        </div>
        <portlet :title="$t('dashboard.label.spam_blocked.title')">
            <data-table
                ref="table"
                :server-side="true"
                url="/brandname/report-month/segment/listing"
                :order-type="'desc'"
                :columns="columns"
                :post-data="tableFilter"
                :fixed-columns-left="0"
                :fixed-columns-right="0"
                :searching="false"
            >
            </data-table>
        </portlet>
    </div>
</template>

<style></style>

<script>
import moment from "moment";
import { formatNumber } from "~/helpers/formats";

export default {
    head() {
        return {
            title: this.$t("menu.brandname.report.month.alias")
        };
    },
    middleware: "auth",
    meta: {
        title: "menu.dashboard"
    },
    data: () => {
        return {
            date: null,
            defaultTime: moment()
                .startOf("month")
                .format("YYYY-MM"),
            tableFilter: {
                time: [
                    moment()
                        .startOf("month")
                        .format("YYYY-MM-DD 00:00:00"),
                    moment()
                        .endOf("month")
                        .format("YYYY-MM-DD 23:59:59")
                ]
            }
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "date",
                    title: this.$t("brandname.report.month.segment.table.date"),
                    render(data) {
                        if (data != null) {
                            return moment(data).format("YYYY-MM");
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: "type",
                    title: this.$t("brandname.report.month.segment.table.type"),
                    render(data) {
                        if (data != null) {
                            switch (String(data)) {
                                case "0":
                                    return "Khác";
                                case "1":
                                    return "QC Nội bộ";
                                case "2":
                                    return "Thông báo";
                                case "3":
                                    return "Khuyến cáo";
                                case "4":
                                    return "QC Brandname";
                                default:
                                    return "Khác";
                            }
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: "amount",
                    title: this.$t(
                        "brandname.report.month.segment.table.amount"
                    ),
                    className: "text-right",
                    render(data) {
                        if (data != null) {
                            return formatNumber(data);
                        } else {
                            return 0;
                        }
                    }
                },
                {
                    data: "vip",
                    title: this.$t("brandname.report.month.segment.table.vip"),
                    className: "text-right",
                    render(data) {
                        if (data != null) {
                            return formatNumber(data);
                        } else {
                            return 0;
                        }
                    }
                },
                {
                    data: "potential",
                    title: this.$t(
                        "brandname.report.month.segment.table.potential"
                    ),
                    className: "text-right",
                    render(data) {
                        if (data != null) {
                            return formatNumber(data);
                        } else {
                            return 0;
                        }
                    }
                },
                {
                    data: "normal",
                    title: this.$t(
                        "brandname.report.month.segment.table.normal"
                    ),
                    className: "text-right",
                    render(data) {
                        if (data != null) {
                            return formatNumber(data);
                        } else {
                            return 0;
                        }
                    }
                },
                {
                    data: "total",
                    title: this.$t(
                        "brandname.report.month.segment.table.total"
                    ),
                    className: "text-right",
                    render(data) {
                        if (data != null) {
                            return formatNumber(data);
                        } else {
                            return 0;
                        }
                    }
                }
            ];
        }
    },
    watch: {},
    mounted() {},
    methods: {
        async filterTime(data) {
            this.tableFilter = data;
            await this.$nextTick();
            this.loadData();
        },
        async loadData() {
            this.$refs.table.reload();
        },
        relayout() {
            this.$refs.table.relayout();
        }
    }
};
</script>
