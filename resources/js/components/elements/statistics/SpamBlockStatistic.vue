<template>
    <div class="row">
        <div class="col-md-12">
            <portlet :title="$t('dashboard.label.spam_blocked.title')">
                <data-table
                    ref="table"
                    :server-side="true"
                    url="/dashboard/listing"
                    :order-type="'desc'"
                    :columns="columns"
                    :fixed-columns-left="0"
                    :fixed-columns-right="0"
                    search-placeholder="Nhập từ khóa..."
                >
                    <thead>
                        <tr>
                            <th rowspan="3" class="text-center span-cell">
                                {{ $t("component.data_table.index") }}
                            </th>
                            <th class="text-center span-cell" rowspan="3">
                                {{ $t("dashboard.table.head.time") }}
                            </th>
                            <th
                                class="text-center span-cell"
                                colspan="2"
                                rowspan="2"
                            >
                                {{ $t("dashboard.table.head.total") }}
                            </th>
                            <th class="text-center span-cell" colspan="12">
                                {{ $t("dashboard.table.head.total_sub") }}
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center span-cell" colspan="2">
                                Viettel
                            </th>
                            <th class="text-center span-cell" colspan="2">
                                Vinaphone
                            </th>
                            <th class="text-center span-cell" colspan="2">
                                Mobiphone
                            </th>
                            <th class="text-center span-cell" colspan="2">
                                Vietnamoblie
                            </th>
                            <th class="text-center span-cell" colspan="2">
                                GMobile
                            </th>
                            <th class="text-center span-cell" colspan="2">
                                {{ $t("dashboard.table.head.others") }}
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.msg_count") }}
                            </th>
                            <th class="text-center span-cell">
                                {{ $t("dashboard.table.head.sub_count") }}
                            </th>
                        </tr>
                    </thead>
                </data-table>
            </portlet>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import { formatNumber } from "~/helpers/formats";

export default {
    name: "SpamBlockStatistic",
    data() {
        return {};
    },
    computed: {
        columns() {
            return [
                {
                    data: "data_time",
                    orderable: true,
                    render(data) {
                        return "W1 " + moment(data).format("MM/YYYY");
                    }
                },
                {
                    data: "no_msg",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_viettel",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub_viettel",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_vina",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub_vina",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_mobi",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub_mobi",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_vietnammobile",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub_vietnammobile",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_gmobile",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub_gmobile",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_other",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "no_sub_other",
                    orderable: false,
                    class: "text-right",
                    render(data) {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                }
            ];
        }
    },
    methods: {}
};
</script>
