<template>
    <modal
        ref="modal"
        class="modal-xxl"
        :title="$t('brandname.config.timeframe.show_history')"
        :on-shown="onModalShown"
        :on-hidden="onModalHidden"
    >
        <data-table
            v-if="modalShown"
            ref="table"
            url="/brandname/brandname-timeframe-config-history/listing"
            :columns="columns"
            :order-column-index="8"
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

export default {
    name: "TimeframeConfigHistoryModal",
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
                    orderable: false,
                    render(data, type, row) {
                        return row["apply_from"] + " - " + row["apply_to"];
                    }
                },
                {
                    data: "week_day",
                    title: this.$t(
                        "brandname.config.timeframe.table.days_of_week"
                    ),
                    orderable: false,
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
                    orderable: false,
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
                    data: "ip",
                    title: "IP",
                    orderable: false
                },
                {
                    data: "who_updated",
                    title: this.$t("common.who_updated"),
                    orderable: false
                },
                {
                    data: "when_updated",
                    title: this.$t("common.when_updated"),
                    render(data) {
                        if (data) {
                            return data;
                        }
                    },
                    orderable: false
                },
                {
                    data: "active",
                    title: this.$t("brandname.config.timeframe.table.active"),
                    orderable: false,
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
                }
            ];
        }
    },
    mounted() {},
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
