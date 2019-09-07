<template
    ><modal
        ref="modal"
        class="modal-xxl"
        :title="$t('brandname.config.duplicate.show_history')"
        :on-shown="onModalShown"
        :on-hidden="onModalHidden"
    >
        <data-table
            v-if="modalShown"
            ref="table"
            url="/brandname/brandname-duplicate-config-history/listing"
            :columns="columns"
            :order-column-index="9"
            :fixed-columns-left="1"
            :fixed-columns-right="3"
            :post-data="{ config_id: configId }"
            :order-type="'desc'"
            :searching="false"
        >
        </data-table>
    </modal>
</template>

<script>
import i18n from "~/plugins/i18n";
import { htmlEscapeEntities } from "~/helpers/tableHelper";
export default {
    name: "DuplicateConfigHistoryModal",
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
                    orderable: false,
                    render(data, type, row) {
                        return row["apply_from"] + " - " + row["apply_to"];
                    }
                },
                {
                    data: "ip",
                    orderable: false,
                    title: i18n.t("brandname.config.duplicate.ip")
                },
                {
                    data: "who_updated",
                    orderable: false,
                    title: i18n.t("common.who_updated")
                },
                {
                    data: "when_updated",
                    orderable: false,
                    title: i18n.t("common.when_updated")
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

<style scoped></style>
