<template>
    <div>
        <portlet :title="$t('menu.brandname.configs.segment_config.title')">
            <data-table
                ref="table"
                url="/brandname/brandname-segment-config/listing"
                :columns="columns"
                :actions="actions"
                :order-column-index="10"
                :search-placeholder="
                    $t('spam.spam_patterns.labeled.search_placeholder')
                "
                :searching="false"
                :fixed-columns-left="2"
                :fixed-columns-right="2"
                :order-type="'desc'"
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
        <segment-config-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></segment-config-modal>
        <segment-config-history-modal
            ref="historyModal"
        ></segment-config-history-modal>
    </div>
</template>

<style scoped></style>

<script>
import { formatNumber } from "~/helpers/formats";
import { generateTableAction } from "~/helpers/tableHelper";
import { SUCCESS } from "~/constants/code";
import i18n from "~/plugins/i18n";
import bootbox from "bootbox";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import axios from "axios";
import moment from "moment";

import SegmentConfigModal from "./partials/segment-config-modal";

import { DAI_TRA, TIEM_NANG, ALL } from "~/constants/code";
import SegmentConfigHistoryModal from "./partials/SegmentConfigHistoryModal";

export default {
    middleware: "auth",
    components: { SegmentConfigHistoryModal, SegmentConfigModal },
    head() {
        return {
            title: i18n.t("menu.brandname.configs.segment_config.title")
        };
    },
    meta: {
        title: "menu.brandname.configs.segment_config.title"
    },
    data: () => {
        return {
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
                    className: "wrap-text",
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
                    data: "per_day",
                    title: i18n.t("brandname.config.segment.per_day"),
                    orderable: false,
                    render: data => {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "per_month",
                    title: i18n.t("brandname.config.segment.per_month"),
                    orderable: false,
                    render: data => {
                        if (data) {
                            return formatNumber(data);
                        }
                    }
                },
                {
                    data: "brandname_sms_type.name",
                    title: i18n.t("brandname.config.segment.sms_type"),
                    orderable: false
                },
                {
                    data: "brandname_sms_type.brandname_sms_group.name",
                    title: i18n.t("brandname.config.segment.sms_group"),
                    orderable: false
                },
                {
                    data: "apply_to",
                    title: i18n.t("brandname.config.segment.apply_time"),
                    orderable: false,
                    render(data, type, row) {
                        return row["apply_from"] + " - " + row["apply_to"];
                    }
                },
                {
                    data: "who_created",
                    title: i18n.t("common.who_created")
                },
                {
                    data: "when_created",
                    title: i18n.t("common.when_created"),
                    render(data) {
                        if (data) {
                            return moment(data, "YYYY-MM-DD HH:mm:ss").format(
                                "YYYY/MM/DD HH:mm:ss"
                            );
                        }
                    }
                },
                {
                    data: "who_updated",
                    title: i18n.t("common.who_updated")
                },
                {
                    data: "when_updated",
                    title: i18n.t("common.when_updated"),
                    render(data) {
                        if (data) {
                            return moment(data, "YYYY-MM-DD HH:mm:ss").format(
                                "YYYY/MM/DD HH:mm:ss"
                            );
                        }
                    }
                },
                {
                    data: "ip",
                    title: i18n.t("brandname.config.segment.ip")
                },
                {
                    data: "active",
                    title: i18n.t("brandname.config.segment.status"),
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
        active(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message: `${this.$t(
                    "brandname.config.segment.alert_active_confirm"
                )} <span class="text-danger"></span>${this.$t(
                    "brandname.config.segment.alert_active_suffixes_confirm"
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
                            "/brandname/brandname-segment-config/active",
                            { id: rowData.id, sms_type_id: rowData.type_id }
                        );
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notify(
                                i18n.t("label.notification"),
                                i18n.t(
                                    "brandname.config.segment.alert_active_successfully"
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
                    "brandname.config.segment.alert_inactive_confirm"
                )} <span class="text-danger"></span>${this.$t(
                    "brandname.config.segment.alert_inactive_suffixes_confirm"
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
                            "/brandname/brandname-segment-config/inactive",
                            { id: rowData.id }
                        );
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notify(
                                i18n.t("label.notification"),
                                i18n.t(
                                    "brandname.config.segment.alert_inactive_successfully"
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
