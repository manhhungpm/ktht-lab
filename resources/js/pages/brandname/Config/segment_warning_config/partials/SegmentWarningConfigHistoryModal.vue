<template>
    <modal
        ref="modal"
        class="modal-xxl"
        :title="$t('brandname.config.segment_warning.show_history')"
        :on-shown="onModalShown"
        :on-hidden="onModalHidden"
    >
        <data-table
            v-if="modalShown"
            ref="table"
            url="/brandname/brandname-segment-warning-config-history/listing"
            :columns="columns"
            :order-column-index="13"
            :fixed-columns-left="1"
            :fixed-columns-right="3"
            :post-data="{ config_id: configId }"
            :order-type="'desc'"
            :searching="false"
        >
        </data-table> </modal
></template>

<script>
import i18n from "~/plugins/i18n";
import { DAI_TRA, TIEM_NANG, ALL, SUCCESS } from "~/constants/code";
import { generateTableAction, htmlEscapeEntities } from "~/helpers/tableHelper";
import { formatNumber } from "~/helpers/formats";

export default {
    name: "SegmentWarningConfigHistoryModal",
    data() {
        return {
            configId: null,
            modalShown: false
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "name",
                    title: i18n.t("brandname.config.segment.segment"),
                    orderable: false,
                    render(data) {
                        if (data == ALL) {
                            return "Tất cả";
                        } else if (data == DAI_TRA) {
                            return "Đại trà";
                        } else if (data == TIEM_NANG) {
                            return "Tiềm năng";
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: "brandname_sms_type.brandname_sms_group.name",
                    title: i18n.t("brandname.config.segment_warning.sms_group"),
                    orderable: false,
                    render(data) {
                        return htmlEscapeEntities(data);
                    }
                },
                {
                    data: "brandname_sms_type.name",
                    title: i18n.t("brandname.config.segment_warning.sms_type"),
                    orderable: false,
                    render(data) {
                        return htmlEscapeEntities(data);
                    }
                },
                {
                    data: "high_warning_from",
                    title: i18n.t(
                        "brandname.config.segment_warning.high_warning"
                    ),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return (
                                formatNumber(data) +
                                " - " +
                                formatNumber(row["high_warning_to"])
                            );
                        }
                    }
                },
                {
                    data: "danger_warning_from",
                    title: i18n.t(
                        "brandname.config.segment_warning.danger_warning"
                    ),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return (
                                formatNumber(data) +
                                " - " +
                                formatNumber(row["danger_warning_to"])
                            );
                        }
                    }
                },
                {
                    data: "crisis_warning_from",
                    title: i18n.t(
                        "brandname.config.segment_warning.crisis_warning"
                    ),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return (
                                formatNumber(data) +
                                " - " +
                                formatNumber(row["crisis_warning_to"])
                            );
                        }
                    }
                },
                {
                    data: "high_warning_from_m",
                    title: i18n.t(
                        "brandname.config.segment_warning.high_warning_month"
                    ),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return (
                                formatNumber(data) +
                                " - " +
                                formatNumber(row["high_warning_to_m"])
                            );
                        }
                    }
                },
                {
                    data: "danger_warning_from_m",
                    title: i18n.t(
                        "brandname.config.segment_warning.danger_warning_month"
                    ),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return (
                                formatNumber(data) +
                                " - " +
                                formatNumber(row["danger_warning_to_m"])
                            );
                        }
                    }
                },
                {
                    data: "crisis_warning_from_m",
                    title: i18n.t(
                        "brandname.config.segment_warning.crisis_warning_month"
                    ),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return (
                                formatNumber(data) +
                                " - " +
                                formatNumber(row["crisis_warning_to_m"])
                            );
                        }
                    }
                },
                {
                    data: "apply_to",
                    title: i18n.t(
                        "brandname.config.segment_warning.apply_time"
                    ),
                    render(data, type, row) {
                        return row["apply_from"] + " - " + row["apply_to"];
                    },
                    orderable: false
                },
                {
                    data: "ip",
                    title: i18n.t("brandname.config.segment_warning.ip"),
                    orderable: false
                },
                {
                    data: "who_updated",
                    title: i18n.t("common.who_updated"),
                    orderable: false
                },
                {
                    data: "when_updated",
                    title: i18n.t("common.when_updated"),
                    orderable: false
                },
                {
                    data: "active",
                    title: this.$t("common.status"),
                    orderable: false,
                    className: "text-center",
                    render(data) {
                        if (data) {
                            return (
                                "<span class='text-success'>" +
                                i18n.t("common.active_status") +
                                "</span>"
                            );
                        } else {
                            return (
                                "<span class='text-danger'>" +
                                i18n.t("common.inactive_status") +
                                "</span>"
                            );
                        }
                    }
                }
            ];
        }
    },
    methods: {
        async show(item = null) {
            if (item != null) {
                this.configId = item.id;
            }

            this.$refs.modal.show();
        },
        async onModalShown() {
            this.modalShown = true;
            await this.$nextTick();
            this.$refs.table.reload();
        },
        onModalHidden() {
            this.configId = null;
        }
    }
};
</script>
