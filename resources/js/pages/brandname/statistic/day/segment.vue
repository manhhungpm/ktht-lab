<template>
    <div>
        <div class="row">
            <div class="col-12">
                <time-range-filter
                    :title="$t('label.search_information')"
                    :default-filter="defaultTime"
                    :exportable="true"
                    :export-url="'/brandname/report-day/segment/export'"
                    :rules-validate="'required|afterWithinAWeek'"
                    :name-shortcut="['last_7_days']"
                    :disabled-date="'greaterThanToday'"
                    @search="filterTime"
                >
                </time-range-filter>
            </div>
        </div>
        <portlet :title="$t('brandname.report.report')">
            <data-table
                ref="table"
                :server-side="true"
                url="/brandname/report-day/segment/listing"
                :order-type="'desc'"
                :columns="columns"
                :post-data="tableFilter"
                :fixed-columns-left="3"
                :fixed-columns-right="1"
                :searching="false"
            >
            </data-table
        ></portlet>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import { formatNumber } from "~/helpers/formats";
import Portlet from "~/components/common/Portlet";
import DataTable from "~/components/common/DataTable";
export default {
    name: "Segment",
    components: { DataTable, Portlet },
    middleware: "auth",
    head() {
        return {
            title: this.$t("menu.brandname.report.day.segment")
        };
    },
    meta: {
        title: "menu.dashboard"
    },
    data: () => {
        return {
            defaultTime: {
                time: [
                    moment()
                        .startOf("day")
                        .subtract(1, "days")
                        .format("YYYY-MM-DD HH:mm:ss"),
                    moment()
                        .startOf("day")
                        .format("YYYY-MM-DD HH:mm:ss")
                ]
            },
            tableFilter: {
                from: moment()
                    .startOf("day")
                    .subtract(1, "days")
                    .format("YYYY-MM-DD HH:mm:ss"),
                to: moment()
                    .startOf("day")
                    .format("YYYY-MM-DD HH:mm:ss")
            }
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "date",
                    title: this.$t("brandname.report.day.segment.table.date"),
                    render(data) {
                        if (data != null) {
                            return moment(data).format("YYYY-MM-DD");
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: "type",
                    title: this.$t("brandname.report.day.segment.table.type"),
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
                    title: this.$t("brandname.report.day.segment.table.amount"),
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
                    title: this.$t("brandname.report.day.segment.table.vip"),
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
                        "brandname.report.day.segment.table.potential"
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
                    title: this.$t("brandname.report.day.segment.table.normal"),
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
                    title: this.$t("brandname.report.day.segment.table.total"),
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

<style scoped></style>
