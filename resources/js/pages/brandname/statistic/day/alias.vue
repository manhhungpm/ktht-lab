<template>
    <div>
        <div class="row">
            <div class="col-12">
                <time-range-filter
                    :title="$t('label.search_information')"
                    :default-filter="defaultTime"
                    :exportable="true"
                    :export-url="'/brandname/report-day/alias/export'"
                    :rules-validate="'required|afterWithinAWeek'"
                    :name-shortcut="['last_7_days']"
                    :disabled-date="'greaterThanToday'"
                    @search="filterTime"
                >
                </time-range-filter>
            </div>
        </div>
        <portlet :title="$t('dashboard.label.spam_blocked.title')">
            <data-table
                ref="table"
                :server-side="true"
                url="/brandname/report-day/alias/listing"
                :order-type="'desc'"
                :columns="columns"
                :post-data="tableFilter"
                :fixed-columns-left="4"
                :fixed-columns-right="2"
                :searching="false"
            >
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center span-cell">
                            {{ $t("component.data_table.index") }}
                        </th>
                        <th class="text-center span-cell" rowspan="2">
                            {{ $t("brandname.report.day.alias.table.date") }}
                        </th>
                        <th class="text-center span-cell" rowspan="2">
                            {{ $t("brandname.report.day.alias.table.type") }}
                        </th>
                        <th class="text-center span-cell" rowspan="2">
                            {{ $t("brandname.report.day.alias.table.alias") }}
                        </th>
                        <th class="text-center span-cell" colspan="15">
                            {{
                                $t("brandname.report.day.alias.table.msg_count")
                            }}
                        </th>
                        <th class="text-right span-cell" rowspan="2">
                            {{
                                $t("brandname.report.day.alias.table.msg_total")
                            }}
                        </th>
                        <th class="text-right span-cell" rowspan="2">
                            {{
                                $t("brandname.report.day.alias.table.sub_count")
                            }}
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right span-cell">
                            1
                        </th>
                        <th class="text-right span-cell">
                            2
                        </th>
                        <th class="text-right span-cell">
                            3
                        </th>
                        <th class="text-right span-cell">
                            4
                        </th>
                        <th class="text-right span-cell">
                            5
                        </th>
                        <th class="text-right span-cell">
                            6
                        </th>
                        <th class="text-right span-cell">
                            7
                        </th>
                        <th class="text-right span-cell">
                            8
                        </th>
                        <th class="text-right span-cell">
                            9
                        </th>
                        <th class="text-right span-cell">
                            10
                        </th>
                        <th class="text-right span-cell">
                            11-20
                        </th>
                        <th class="text-right span-cell">
                            21-30
                        </th>
                        <th class="text-right span-cell">
                            31-40
                        </th>
                        <th class="text-right span-cell">
                            41-50
                        </th>
                        <th class="text-right span-cell">
                            >50
                        </th>
                    </tr>
                </thead>
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
            title: this.$t("menu.brandname.report.day.alias")
        };
    },
    middleware: "auth",
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
                    data: "report_date",
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
                    orderable: false,
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
                    orderable: false,
                    data: "alias"
                },
                {
                    orderable: false,
                    data: "_1",
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
                    orderable: false,
                    data: "_2",
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
                    orderable: false,
                    data: "_3",
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
                    orderable: false,
                    data: "_4",
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
                    orderable: false,
                    data: "_5",
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
                    orderable: false,
                    data: "_6",
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
                    orderable: false,
                    data: "_7",
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
                    orderable: false,
                    data: "_8",
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
                    orderable: false,
                    data: "_9",
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
                    orderable: false,
                    data: "_10",
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
                    orderable: false,
                    data: "_11_20",
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
                    orderable: false,
                    data: "_21_30",
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
                    orderable: false,
                    data: "_31_40",
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
                    orderable: false,
                    data: "_41_50",
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
                    orderable: false,
                    data: "_bigger50",
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
                    orderable: false,
                    data: "msg_amount",
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
                    orderable: false,
                    className: "text-right",
                    data: "cus_amount",
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
    mounted() {
        // this.loadData();
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
