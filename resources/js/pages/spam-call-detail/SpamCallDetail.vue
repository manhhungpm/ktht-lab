<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="m-portlet__body">
                    <div
                        id="m_accordion_5"
                        class="m-accordion m-accordion--default m-accordion--toggle-arrow"
                        role="tablist"
                    >
                        <div class="m-accordion__item m-accordion__item--brand">
                            <div
                                id="m_accordion_5_item_3_head"
                                class="m-accordion__item-head collapsed"
                                role="tab"
                                data-toggle="collapse"
                                href="#m_accordion_5_item_3_body"
                                aria-expanded="true"
                            >
                                <span class="m-accordion__item-title">
                                    {{ $t("label.search_information") }}</span
                                >
                                <span class="m-accordion__item-mode"></span>
                            </div>
                            <div
                                id="m_accordion_5_item_3_body"
                                class="m-accordion__item-body collapse"
                                role="tabpanel"
                                aria-labelledby="m_accordion_5_item_3_head"
                                data-parent="#m_accordion_5"
                            >
                                <div class="m-accordion__item-content">
                                    <spam-call-detail-filter
                                        @search="search"
                                    ></spam-call-detail-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <portlet title="Báo cáo chi tiết">
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <v-button
                                color="primary"
                                style-type="air"
                                class="m-btn m-btn--icon"
                                style="margin-bottom: 18px"
                                @click.native="lockSelected"
                            >
                                <span>
                                    <i class="la la-unlock"></i>
                                    <span>{{ $t("button.lock") }}</span>
                                </span>
                            </v-button>
                            <v-button
                                color="info"
                                style-type="air"
                                class="m-btn m-btn--icon"
                                style="margin-bottom: 18px"
                                @click.native="bypassSelected"
                            >
                                <span>
                                    <i class="la la-close"></i>
                                    <span>{{ $t("button.bypass") }}</span>
                                </span>
                            </v-button>
                            <!--<v-button-->
                            <!--color="success"-->
                            <!--style-type="air"-->
                            <!--class="m-btn m-btn&#45;&#45;icon"-->
                            <!--style="margin-bottom: 18px"-->
                            <!--@click.native="disableIsSelected"-->
                            <!--&gt;-->
                            <!--<span>{{ $t("button.select_all") }}</span>-->
                            <!--</v-button>-->
                        </div>
                        <div class="col-md-3 col-lg-4 table-addition-label">
                            <b>
                                <span>{{
                                    $t(
                                        "statistic_suspected_phone.table.spam_total"
                                    )
                                }}</span>
                                <span>
                                    {{ spamTotalFormatted }}
                                </span>
                            </b>
                        </div>
                        <div class="col-md-3 col-lg-4 table-addition-label">
                            <b>
                                <span>{{
                                    $t(
                                        "statistic_suspected_phone.table.fee_total"
                                    )
                                }}</span>
                                <span> {{ feeTotalFormatted }} VND </span>
                            </b>
                        </div>
                    </div>
                    <data-table
                        ref="table"
                        :columns="columns"
                        url="/spam-call-detail/listing"
                        :post-data="tableFilter"
                        :selectable="true"
                        :actions="actions"
                        :order-column-index="2"
                        :fixed-columns-left="3"
                        :fixed-columns-right="2"
                        @onSelect="reCalculate"
                    ></data-table>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {
    THREE_DAYS,
    SEVEN_DAYS,
    THIRTY_DAY,
    SUGGEST_LOCK,
    LOCKED,
    UNLOCKED,
    BYPASSED,
    SUCCESS
} from "~/constants/constant";
import {
    notifyNoRecord,
    notifyTryAgain,
    notifyUnLockSuccess,
    notifyLockSuccess,
    notifyBypassSuccess
} from "~/helpers/bootstrap-notify";
import bootbox from "bootbox";

import { generateTableAction, htmlEscapeEntities } from "~/helpers/tableHelper";
import SpamCallDetailFilter from "./partials/SpamCallDetailFilter";
import { formatNumber } from "~/helpers/formats";

export default {
    name: "SpamCallDetail",
    components: { SpamCallDetailFilter },
    middleware: "auth",
    data() {
        return {
            timeFilter: {},
            tableFilter: {
                duration_type_id: 1
            },
            spamTotal: 0,
            feeTotal: 0
        };
    },
    computed: {
        spamTotalFormatted() {
            return formatNumber(this.spamTotal);
        },
        feeTotalFormatted() {
            return formatNumber(this.feeTotal);
        },
        actions() {
            return [
                {
                    type: "click",
                    name: "lockNumber",
                    action: this.lockNumber
                },
                {
                    type: "click",
                    name: "unlockNumber",
                    action: this.unlockNumber
                },
                {
                    type: "click",
                    name: "bypassNumber",
                    action: this.bypassNumber
                }
            ];
        },
        columns() {
            const $this = this;
            return [
                {
                    data: "msisdn",
                    title: this.$t(
                        "statistic_suspected_phone.table.phone_number"
                    )
                },
                {
                    data: "carrier",
                    title: this.$t("statistic_suspected_phone.table.carrier"),
                    render(data) {
                        switch (data) {
                            case "viettel":
                                return "Viettel";
                            case "vinaphone":
                                return "Vinaphone";
                            case "mobifone":
                                return "Mobiphone";
                            case "vietnamobile":
                                return "Vietnamobile";
                            case "gmobile":
                                return "Gmobile";
                            case "other":
                                return $this.$t(
                                    "statistic_suspected_phone.carrier.other"
                                );
                        }
                    }
                },
                {
                    data: "duration_type.label",
                    title: this.$t(
                        "statistic_suspected_phone.table.time_range"
                    ),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            var day = "";
                            switch (parseInt(data)) {
                                case THREE_DAYS:
                                    day = "3 ngày";
                                    break;
                                case SEVEN_DAYS:
                                    day = "7 ngày";
                                    break;
                                case THIRTY_DAY:
                                    day = "30 ngày";
                                    break;
                            }
                            return day;
                        } else return "-";
                    }
                },
                {
                    data: "num_call_out",
                    title: this.$t(
                        "statistic_suspected_phone.table.calls_total"
                    )
                },

                {
                    data: "sum_duration_call_out",
                    title: this.$t(
                        "statistic_suspected_phone.table.duration_total"
                    )
                },
                {
                    data: "num_call_label_spam",
                    title: this.$t(
                        "statistic_suspected_phone.table.spam_labeled_calls"
                    )
                },
                {
                    data: "num_call_label_not_spam",
                    title: this.$t(
                        "statistic_suspected_phone.table.not_spam_labeled_calls"
                    )
                },
                {
                    data: "num_call_not_label",
                    title: this.$t(
                        "statistic_suspected_phone.table.unlabeled_calls"
                    )
                },
                {
                    data: "label",
                    title: this.$t("statistic_suspected_phone.table.status"),
                    orderable: false,
                    render(data) {
                        if (data) {
                            if (data.status == SUGGEST_LOCK) {
                                return `<span class="text-warning">${$this.$t(
                                    "statistic_suspected_phone.status.suggest"
                                )}</span>`;
                            }
                            if (data.status == LOCKED) {
                                return `<span class="text-danger">${$this.$t(
                                    "statistic_suspected_phone.status.locked"
                                )}</span>`;
                            }
                            if (data.status == UNLOCKED) {
                                return `<span class="text-success">${$this.$t(
                                    "statistic_suspected_phone.status.unlocked"
                                )}</span>`;
                            }
                            if (data.status == BYPASSED) {
                                return `<span class="text-info">${$this.$t(
                                    "statistic_suspected_phone.status.bypassed"
                                )}</span>`;
                            }
                        } else {
                            return `<span class="text-warning">${$this.$t(
                                "statistic_suspected_phone.status.suggest"
                            )}</span>`;
                        }
                    }
                },
                {
                    data: "label",
                    title: this.$t("datatable.column.action"),
                    className: "tb-actions",
                    orderable: false,
                    render(data) {
                        if (data) {
                            if (data.status == SUGGEST_LOCK) {
                                return (
                                    generateTableAction(
                                        "lock-labeled-number",
                                        "lockNumber",
                                        "primary",
                                        "la-unlock",
                                        $this.$t(
                                            "statistic_suspected_phone.action.lock"
                                        )
                                    ) +
                                    generateTableAction(
                                        "bypass-labeled-number",
                                        "bypassNumber",
                                        "danger",
                                        "la-close",
                                        $this.$t(
                                            "statistic_suspected_phone.action.bypass"
                                        )
                                    )
                                );
                            } else if (data.status == LOCKED) {
                                return generateTableAction(
                                    "unlock-number",
                                    "unlockNumber",
                                    "success",
                                    "la-unlock-alt",
                                    $this.$t(
                                        "statistic_suspected_phone.action.unlock"
                                    )
                                );
                            } else if (data.status == UNLOCKED) {
                                return generateTableAction(
                                    "lock-labeled-number",
                                    "lockNumber",
                                    "primary",
                                    "la-unlock",
                                    $this.$t(
                                        "statistic_suspected_phone.action.lock"
                                    )
                                );
                            } else if (data.status == BYPASSED) {
                                return "-";
                            }
                        } else {
                            return (
                                generateTableAction(
                                    "lock-new-number",
                                    "lockNumber",
                                    "primary",
                                    "la-unlock",
                                    $this.$t(
                                        "statistic_suspected_phone.action.lock"
                                    )
                                ) +
                                generateTableAction(
                                    "bypass-number",
                                    "bypassNumber",
                                    "danger",
                                    "la-close",
                                    $this.$t(
                                        "statistic_suspected_phone.action.bypass"
                                    )
                                )
                            );
                        }
                    }
                }
            ];
        }
    },
    watch: {
        timeFilter(data) {
            this.filterTime(data);
        }
    },
    methods: {
        async search(value) {
            this.tableFilter = value;
            this.spamTotal = 0;
            this.feeTotal = 0;
            await this.$nextTick();
            this.$refs.table.reload();
        },
        async loadData() {
            this.$refs.table.reload();
        },
        lockNumber(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message: this.$t("statistic_suspected_phone.msg.confirm_lock", {
                    number: htmlEscapeEntities(rowData.msisdn)
                }),
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.accept")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        let res = await axios.post("/spam-call-detail/label", {
                            phone: rowData.msisdn,
                            status: LOCKED
                        });
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notifyLockSuccess();
                            $this.$refs.table.reload();
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },

        lockSelected() {
            let $this = this;
            const selectedMsisdn = this.$refs.table
                .getSelectedRows()
                .map(obj => {
                    return obj.msisdn;
                });
            if (selectedMsisdn.length > 0) {
                let numbersText = "";
                if (selectedMsisdn.length <= 5) {
                    numbersText = selectedMsisdn.reduce((html, obj) => {
                        return (html +=
                            '"' + htmlEscapeEntities(obj) + '",<br/>');
                    }, "<br/>");
                } else {
                    numbersText = selectedMsisdn.reduce((html, obj) => {
                        return (html +=
                            '"' + htmlEscapeEntities(obj) + '",<br/>');
                    }, "<br/>");
                    numbersText += `và ${selectedMsisdn.length -
                        5} số khác... <br/>`;
                }
                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message: this.$t(
                        "statistic_suspected_phone.msg.confirm_lock",
                        {
                            number: numbersText
                        }
                    ),
                    buttons: {
                        cancel: {
                            label: this.$t("button.cancel")
                        },
                        confirm: {
                            label: this.$t("button.accept")
                        }
                    },
                    callback: async function(result) {
                        if (result) {
                            let res = await axios.post(
                                "/spam-call-detail/label-multiple",
                                {
                                    phone: selectedMsisdn,
                                    status: LOCKED
                                }
                            );
                            const { data } = res;

                            if (data.code == SUCCESS) {
                                notifyLockSuccess();
                                $this.$refs.table.reload();
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            } else {
                notifyNoRecord();
            }
        },

        bypassSelected() {
            let $this = this;
            const selectedMsisdn = this.$refs.table
                .getSelectedRows()
                .map(obj => {
                    return obj.msisdn;
                });
            if (selectedMsisdn.length > 0) {
                let numbersText = "";
                if (selectedMsisdn.length <= 5) {
                    numbersText = selectedMsisdn.reduce((html, obj) => {
                        return (html +=
                            '"' + htmlEscapeEntities(obj) + '",<br/>');
                    }, "<br/>");
                } else {
                    numbersText = selectedMsisdn.reduce((html, obj) => {
                        return (html +=
                            '"' + htmlEscapeEntities(obj) + '",<br/>');
                    }, "<br/>");
                    numbersText += `và ${selectedMsisdn.length -
                        5} số khác... <br/>`;
                }
                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message: this.$t(
                        "statistic_suspected_phone.msg.confirm_bypass",
                        {
                            number: numbersText
                        }
                    ),
                    buttons: {
                        cancel: {
                            label: this.$t("button.cancel")
                        },
                        confirm: {
                            label: this.$t("button.accept")
                        }
                    },
                    callback: async function(result) {
                        if (result) {
                            let res = await axios.post(
                                "/spam-call-detail/label-multiple",
                                {
                                    phone: selectedMsisdn,
                                    status: BYPASSED
                                }
                            );
                            const { data } = res;

                            if (data.code == SUCCESS) {
                                notifyBypassSuccess();
                                $this.$refs.table.reload();
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            } else {
                notifyNoRecord();
            }
        },

        unlockNumber(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message: this.$t(
                    "statistic_suspected_phone.msg.confirm_unlock",
                    { number: htmlEscapeEntities(rowData.msisdn) }
                ),
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.accept")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        let res = await axios.post("/spam-call-detail/label", {
                            phone: rowData.msisdn,
                            status: UNLOCKED
                        });
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notifyUnLockSuccess();
                            $this.$refs.table.reload();
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },

        bypassNumber(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message: this.$t(
                    "statistic_suspected_phone.msg.confirm_bypass",
                    {
                        number: htmlEscapeEntities(rowData.msisdn)
                    }
                ),
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.accept")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        let res = await axios.post("/spam-call-detail/label", {
                            phone: rowData.msisdn,
                            status: BYPASSED
                        });
                        const { data } = res;

                        if (data.code == SUCCESS) {
                            notifyBypassSuccess();
                            $this.$refs.table.reload();
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },

        reCalculate() {
            const selected = this.$refs.table.getSelectedRows();
            let spam = 0;
            let fee = 0;
            selected.forEach(e => {
                spam += e.num_call_label_spam;
                fee += e.sum_duration_call_out * 1000;
            });
            this.spamTotal = spam;
            this.feeTotal = fee;
        }
    }
};
</script>

<style scoped></style>
