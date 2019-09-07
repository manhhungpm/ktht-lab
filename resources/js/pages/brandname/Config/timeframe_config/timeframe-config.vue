<template>
    <div>
        <portlet :title="$t('brandname.config.timeframe.list')">
            <data-table
                ref="table"
                url="/brandname/brandname-timeframe-config/listing"
                :columns="columns"
                :actions="actions"
                :order-column-index="9"
                :post-data="tableFilter"
                :fixed-columns-left="2"
                :fixed-columns-right="2"
                :order-type="'desc'"
                :searching="false"
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
        <timeframe-config-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></timeframe-config-modal>
        <timeframe-config-history-modal
            ref="historyModal"
        ></timeframe-config-history-modal>
    </div>
</template>

<script>
import { toNormalDate } from "~/helpers/formats";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import {
    MONDAY,
    TUESDAY,
    WEDNESDAY,
    THURSDAY,
    FRIDAY,
    SATURDAY,
    SUNDAY
} from "~/constants/constant";
import i18n from "~/plugins/i18n";
import bootbox from "bootbox";
import {
    notify,
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import axios from "axios";

import TimeframeConfigModal from "./partials/TimeframeConfigModal";
import TimeframeConfigHistoryModal from "./partials/TimeframeConfigHistoryModal";

export default {
    middleware: "auth",
    components: { TimeframeConfigHistoryModal, TimeframeConfigModal },
    head() {
        return {
            title: i18n.t("menu.brandname.configs.timeframe.title")
        };
    },
    meta: {
        title: "menu.brandname.configs.timeframe.title"
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
                    data: "brandname_sms_type.brandname_sms_group.name",
                    orderable: false,
                    title: this.$t("brandname.config.timeframe.table.sms_group")
                },
                {
                    data: "brandname_sms_type.name",
                    orderable: false,
                    title: this.$t("brandname.config.timeframe.table.sms_type")
                },
                {
                    data: null,
                    title: this.$t(
                        "brandname.config.timeframe.table.apply_time"
                    ),
                    render(data, type, row) {
                        return row["apply_from"] + " - " + row["apply_to"];
                    }
                },
                {
                    data: "week_day",
                    title: this.$t(
                        "brandname.config.timeframe.table.days_of_week"
                    ),
                    render(data) {
                        if (data) {
                            const arr = JSON.parse(data).day;
                            let str = "";
                            arr.forEach(e => {
                                let day;
                                switch (parseInt(e)) {
                                    case MONDAY:
                                        day = i18n.t("common.day.mon");
                                        break;
                                    case TUESDAY:
                                        day = i18n.t("common.day.tue");
                                        break;
                                    case WEDNESDAY:
                                        day = i18n.t("common.day.wed");
                                        break;
                                    case THURSDAY:
                                        day = i18n.t("common.day.thu");
                                        break;
                                    case FRIDAY:
                                        day = i18n.t("common.day.fri");
                                        break;
                                    case SATURDAY:
                                        day = i18n.t("common.day.sat");
                                        break;
                                    case SUNDAY:
                                        day = i18n.t("common.day.sun");
                                        break;
                                }
                                str += day + ", ";
                            });
                            if (arr.length > 0) {
                                str = str.slice(0, -2);
                            }
                            return str;
                        }
                    }
                },
                {
                    data: "time_frame",
                    title: this.$t(
                        "brandname.config.timeframe.table.time_frame"
                    ),
                    render(data) {
                        if (data) {
                            const arr = JSON.parse(data).time_frame;
                            let str = "";
                            arr.forEach(e => {
                                str += `- ${e.from} - ${e.to} <br/>`;
                            });

                            return str;
                        }
                    }
                },
                {
                    data: "who_created",
                    title: this.$t("common.who_created")
                },
                {
                    data: "when_created",
                    title: this.$t("common.when_created"),
                    render(data) {
                        if (data) {
                            return data;
                        }
                    }
                },
                {
                    data: "who_updated",
                    title: this.$t("common.who_updated")
                },
                {
                    data: "when_updated",
                    title: this.$t("common.when_updated"),
                    render(data) {
                        if (data) {
                            return data;
                        }
                    }
                },
                {
                    data: "ip",
                    title: "IP"
                },
                {
                    data: "active",
                    title: this.$t("brandname.config.timeframe.table.active"),
                    render(data) {
                        if (data != null) {
                            if (data == 1) {
                                return `<span class='text-success'>Kích hoạt</span>`;
                            } else {
                                return `<span class='text-danger'>Vô hiệu</span>`;
                            }
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: "active",
                    title: i18n.t("common.action"),
                    orderable: false,
                    className: "text-center",
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
    watch: {},
    mounted() {
        // this.handleSwitch();
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
                message: this.$t(
                    "brandname.config.timeframe.notification.active"
                ),
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
                            "/brandname/brandname-timeframe-config/active",
                            { id: rowData.id, sms_type_id: rowData.type_id }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyActiveSuccess();
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
                message: this.$t(
                    "brandname.config.timeframe.notification.disable"
                ),
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
                            "/brandname/brandname-timeframe-config/inactive",
                            { id: rowData.id }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDisableSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        showHistory(table, rowData) {
            let $this = this;

            $this.$refs.historyModal.show(rowData);
        }
    }
};
</script>
