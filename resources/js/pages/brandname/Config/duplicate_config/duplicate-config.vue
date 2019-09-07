<template>
    <div>
        <portlet :title="$t('brandname.config.duplicate.title')">
            <data-table
                ref="table"
                url="/brandname/brandname-duplicate-config/listing"
                :columns="columns"
                :actions="actions"
                :order-column-index="10"
                :order-type="'desc'"
                :search-placeholder="
                    $t('spam.spam_patterns.labeled.search_placeholder')
                "
                :searching="false"
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
        <duplicate-config-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></duplicate-config-modal>
        <duplicate-config-history-modal
            ref="historyModal"
        ></duplicate-config-history-modal>
    </div>
</template>

<style scoped></style>

<script>
import {
    generateTableAction,
    reloadIntelligently,
    htmlEscapeEntities
} from "~/helpers/tableHelper";
import i18n from "~/plugins/i18n";
import bootbox from "bootbox";
import { notify, notifyTryAgain } from "~/helpers/bootstrap-notify";
import axios from "axios";
import { SUCCESS } from "~/constants/code";

import DuplicateConfigModal from "./partials/duplicate-config-modal";
import DuplicateConfigHistoryModal from "./partials/DuplicateConfigHistoryModal";

export default {
    middleware: "auth",
    components: { DuplicateConfigHistoryModal, DuplicateConfigModal },
    head() {
        return {
            title: i18n.t("menu.brandname.configs.duplicate_config.title")
        };
    },
    meta: {
        title: "menu.brandname.configs.duplicate_config.title"
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
                    data: "brandname_sms_type.brandname_sms_group.name",
                    title: i18n.t("brandname.config.duplicate.sms_group"),
                    orderable: false,
                    render(data) {
                        return htmlEscapeEntities(data);
                    }
                },
                {
                    data: "brandname_sms_type.name",
                    title: i18n.t("brandname.config.duplicate.sms_type"),
                    orderable: false,
                    render(data) {
                        return htmlEscapeEntities(data);
                    }
                },
                {
                    data: "high_warning_from",
                    title: i18n.t("brandname.config.duplicate.high_warning"),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return data + " - " + row["high_warning_to"];
                        }
                    }
                },
                {
                    data: "danger_warning_from",
                    title: i18n.t("brandname.config.duplicate.danger_warning"),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return data + " - " + row["danger_warning_to"];
                        }
                    }
                },
                {
                    data: "crisis_warning_from",
                    title: i18n.t("brandname.config.duplicate.crisis_warning"),
                    orderable: false,
                    render: (data, type, row) => {
                        if (data) {
                            return data + " - " + row["crisis_warning_to"];
                        }
                    }
                },
                {
                    data: "apply_to",
                    title: i18n.t("brandname.config.duplicate.apply_time"),
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
                    title: i18n.t("brandname.config.duplicate.ip")
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
        active(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message: `${this.$t(
                    "brandname.config.duplicate.alert_active_confirm"
                )} <span class="text-danger"></span>${this.$t(
                    "brandname.config.duplicate.alert_active_suffixes_confirm"
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
                            "/brandname/brandname-duplicate-config/active",
                            { id: rowData.id, sms_type_id: rowData.type_id }
                        );
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notify(
                                i18n.t("label.notification"),
                                i18n.t(
                                    "brandname.config.duplicate.alert_active_successfully"
                                ),
                                "success",
                                "icon la la-check"
                            );
                            reloadIntelligently($this.$refs.table);
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
                    "brandname.config.duplicate.alert_inactive_confirm"
                )} <span class="text-danger"></span>${this.$t(
                    "brandname.config.duplicate.alert_inactive_suffixes_confirm"
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
                            "/brandname/brandname-duplicate-config/inactive",
                            { id: rowData.id }
                        );
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notify(
                                i18n.t("label.notification"),
                                i18n.t(
                                    "brandname.config.duplicate.alert_inactive_successfully"
                                ),
                                "success",
                                "icon la la-check"
                            );
                            reloadIntelligently($this.$refs.table);
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
