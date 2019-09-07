<template>
    <div>
        <portlet :title="$t('brandname.config.segment_warning.title')">
            <data-table
                ref="table"
                url="/brandname/brandname-segment-warning-config/listing"
                :columns="columns"
                :actions="actions"
                :order-column-index="14"
                :order-type="'desc'"
                :search-placeholder="
                    $t('spam.spam_patterns.labeled.search_placeholder')
                "
                :searching="false"
                :post-data="tableFilter"
                :fixed-columns-left="2"
                :fixed-columns-right="2"
            >
            </data-table>

            <v-button
                slot="tool"
                color="accent"
                style-type="air"
                class="m-btn--custom m-btn--icon m--margin-right-10 btn btn-primary"
                @click.native="add"
            >
                <i class="la la-plus"></i> {{ $t("button.add") }}
            </v-button>
        </portlet>
        <segment-warning-config-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></segment-warning-config-modal>
        <segment-warning-config-history-modal
            ref="historyModal"
        ></segment-warning-config-history-modal>
    </div>
</template>

<style scoped></style>

<script>
import { formatNumber } from "~/helpers/formats";
import { generateTableAction, htmlEscapeEntities } from "~/helpers/tableHelper";
import i18n from "~/plugins/i18n";
import bootbox from "bootbox";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import axios from "axios";
import { DAI_TRA, TIEM_NANG, ALL, SUCCESS } from "~/constants/code";

import SegmentWarningConfigModal from "./partials/segment-warning-config-modal";
import SegmentWarningConfigHistoryModal from "./partials/SegmentWarningConfigHistoryModal";

export default {
    middleware: "auth",
    components: { SegmentWarningConfigHistoryModal, SegmentWarningConfigModal },
    head() {
        return {
            title: i18n.t("menu.brandname.configs.segment_warning_config.title")
        };
    },
    meta: {
        title: "menu.brandname.configs.segment_warning_config.title"
    },
    data: () => {
        return {
            tableFilter: {},
            isExporting: false,
            sources: [],
            sourcesFilter: [],
            spamLabels: [],
            spamLabelsFilter: []
        };
    },
    computed: {
        actions() {
            return [
                {
                    type: "click",
                    name: "edit",
                    action: this.edit
                },
                {
                    type: "click",
                    name: "add",
                    action: this.add
                },
                {
                    type: "click",
                    name: "active",
                    action: this.active
                },
                {
                    type: "click",
                    name: "inactive",
                    action: this.inactive
                },
                {
                    type: "click",
                    name: "showHistory",
                    action: this.showHistory
                }
            ];
        },
        columns() {
            return [
                {
                    data: "name",
                    title: i18n.t("brandname.config.segment.segment"),
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
                    data: "who_created",
                    title: i18n.t("common.who_created")
                },
                {
                    data: "when_created",
                    title: i18n.t("common.when_created")
                },
                {
                    data: "who_updated",
                    title: i18n.t("common.who_updated")
                },
                {
                    data: "when_updated",
                    title: i18n.t("common.when_updated")
                },
                {
                    data: "ip",
                    title: i18n.t("brandname.config.segment_warning.ip")
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
                },
                {
                    data: "active",
                    title: i18n.t("common.action"),
                    orderable: false,
                    className: "text-center tb-actions",
                    responsivePriority: 1,
                    render(data) {
                        if (data) {
                            return (
                                generateTableAction("disable", "inactive") +
                                generateTableAction(
                                    "showHistory",
                                    "showHistory"
                                )
                            );
                        } else {
                            return (
                                generateTableAction("edit", "edit") +
                                generateTableAction("active", "active") +
                                generateTableAction(
                                    "showHistory",
                                    "showHistory"
                                )
                            );
                        }
                    }
                }
            ];
        }
    },
    methods: {
        edit(table, rowData) {
            this.$refs.modal.show(rowData);
        },
        add() {
            this.$refs.modal.show();
        },
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        async search(value) {
            this.tableFilter = value;
            await this.$nextTick();
            this.updateItemSuccess();
        },
        active(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message: `${this.$t(
                    "brandname.config.segment_warning.alert_active_confirm"
                )} <span class="text-danger"></span>${this.$t(
                    "brandname.config.segment_warning.alert_active_suffixes_confirm"
                )}`,
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.ok")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        let res = await axios.post(
                            "/brandname/brandname-segment-warning-config/active",
                            { id: rowData.id, sms_type_id: rowData.type_id }
                        );
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notify(
                                i18n.t("label.notification"),
                                i18n.t(
                                    "brandname.config.segment_warning.alert_active_successfully"
                                ),
                                "success",
                                "icon la la-check"
                            );
                            $this.$refs.table.reload();
                        } else if (data.code == 2) {
                            notify(
                                $this.$t("label.notification"),
                                $this.$t(
                                    "notification.no_active_because_sms_type_disable"
                                )
                            );
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        inactive(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message: `${this.$t(
                    "brandname.config.segment_warning.alert_inactive_confirm"
                )} <span class="text-danger"></span>${this.$t(
                    "brandname.config.segment_warning.alert_inactive_suffixes_confirm"
                )}`,
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.ok")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        let res = await axios.post(
                            "/brandname/brandname-segment-warning-config/inactive",
                            { id: rowData.id }
                        );
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notify(
                                i18n.t("label.notification"),
                                i18n.t(
                                    "brandname.config.segment_warning.alert_inactive_successfully"
                                ),
                                "success",
                                "icon la la-check"
                            );
                            $this.$refs.table.reload();
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        showHistory(table, rowData) {
            this.$refs.historyModal.show(rowData);
        }
    }
};
</script>
