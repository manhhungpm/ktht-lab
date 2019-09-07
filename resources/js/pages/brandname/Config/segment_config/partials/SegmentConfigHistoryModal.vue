<template>
    <modal
        ref="modal"
        class="modal-xxl"
        :title="$t('brandname.config.segment.show_history')"
        :on-shown="onModalShown"
        :on-hidden="onModalHidden"
    >
        <data-table
            v-if="modalShown"
            ref="table"
            url="/brandname/brandname-segment-config-history/listing"
            :columns="columns"
            :order-column-index="9"
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
import { formatNumber } from "~/helpers/formats";
import { DAI_TRA, TIEM_NANG, ALL } from "~/constants/code";
import moment from "moment";

export default {
    name: "SegmentConfigHistoryModal",
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
                    className: "wrap-text",
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
                    data: "ip",
                    title: i18n.t("brandname.config.segment.ip"),
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
                    orderable: false,
                    render(data) {
                        if (data) {
                            return moment(data, "YYYY-MM-DD HH:mm:ss").format(
                                "YYYY/MM/DD HH:mm:ss"
                            );
                        }
                    }
                },
                {
                    data: "active",
                    title: i18n.t("brandname.config.segment.status"),
                    className: "text-center",
                    orderable: false,
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

<style scoped></style>
