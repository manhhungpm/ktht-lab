<template>
    <div class="row">
        <div class="col-md-12">
            <the-portlet :title="$t('brandname.config.information')">
                <div class="row">
                    <div class="col-md-4"><h4>Đầu số Viettel quản lý</h4></div>
                    <div class="col-md-4">
                        <h5>Ngưỡng tin nhắn theo ngày</h5>
                        <div>
                            <input
                                v-model="dateVBr"
                                class="form-control"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Ngưỡng tin nhắn theo tháng</h5>
                        <div>
                            <input
                                v-model="monthVBr"
                                class="form-control"
                                disabled
                            />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"><h4>Đầu số đối tác quản lý</h4></div>
                    <div class="col-md-4">
                        <h5>Ngưỡng tin nhắn theo ngày</h5>
                        <div>
                            <input
                                v-model="dateOBr"
                                class="form-control"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Ngưỡng tin nhắn theo tháng</h5>
                        <div>
                            <input
                                v-model="monthOBr"
                                class="form-control"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </the-portlet>
        </div>
        <div class="col-md-12">
            <the-portlet :title="$t('brandname.config.title')">
                <v-button
                    color="success"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-bottom: 18px"
                    @click.native="addConfig"
                >
                    <span>
                        <i class="la la-plus"></i>
                        <span>{{ $t("button.add") }}</span>
                    </span>
                </v-button>
                <data-table
                    ref="table"
                    :columns="columns"
                    :actions="actions"
                    url="/brandname/config/listing"
                    :fixed-columns-left="3"
                    :fixed-columns-right="1"
                    :searching="false"
                    :order-column-index="1"
                    :order-type="'desc'"
                ></data-table>
            </the-portlet>
            <config-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></config-modal>
        </div></div
></template>

<script>
import * as ROLE from "~/constants/roles";
import axios from "axios";
import ThePortlet from "~/components/common/Portlet";
import ConfigModal from "../Config/partials/ConfigModal";
import DataTable from "~/components/common/DataTable";
import { V_BROADCAST } from "~/constants/constant";
import bootbox from "bootbox";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import {
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import { formatDate } from "~/helpers/formats";

export default {
    components: { ConfigModal, ThePortlet, DataTable },
    middleware: "auth",
    head() {
        return {
            title: this.$t("brandname.config.title")
        };
    },
    meta: {
        title: "brandname.config.title",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_CC]
    },
    data() {
        return {
            dateVBr: null,
            monthVBr: null,
            dateOBr: null,
            monthOBr: null
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "date_threshold",
                    className: "dt-right",
                    title: this.$t(
                        "brandname.config.datatable.column.date_threshold"
                    )
                },
                {
                    data: "month_threshold",
                    className: "dt-right",
                    title: this.$t(
                        "brandname.config.datatable.column.month_threshold"
                    )
                },
                {
                    data: "br_type",
                    title: this.$t("brandname.config.datatable.column.br_type"),
                    orderable: false,
                    render(data) {
                        if (data === V_BROADCAST)
                            return htmlEscapeEntities("Đầu số VIETTEL quản lý");
                        return htmlEscapeEntities("Đầu số đối tác quản lý");
                    }
                },
                {
                    data: "active",
                    title: this.$t("brandname.config.datatable.column.active"),
                    render(data) {
                        if (data === 1) return htmlEscapeEntities("Hoạt động");
                        return htmlEscapeEntities("Vô hiệu");
                    }
                },
                {
                    data: "who_created",
                    title: this.$t("datatable.column.who_created")
                },
                {
                    data: "when_created",
                    title: this.$t("datatable.column.when_created"),
                    render(data) {
                        if (data != null) return formatDate(data);
                    }
                },
                {
                    data: null,
                    title: this.$t("datatable.column.action"),
                    orderable: false,
                    className: "text-center tb-actions",
                    responsivePriority: 1,
                    render(data) {
                        if (data.active === 1) {
                            return generateTableAction(
                                "disable",
                                "handleDisable"
                            );
                        } else
                            return (
                                generateTableAction("edit", "handleEdit") +
                                generateTableAction("active", "handleActive")
                            );
                    }
                }
            ];
        },
        actions() {
            return [
                {
                    type: "click",
                    name: "handleEdit",
                    action: this.handleEdit
                },
                {
                    type: "click",
                    name: "handleDisable",
                    action: this.handleDisable
                },
                {
                    type: "click",
                    name: "handleActive",
                    action: this.handleActive
                }
            ];
        }
    },
    mounted() {
        this.updateInfomationConfig();
    },
    methods: {
        handleEdit(table, rowData) {
            this.$refs.addModal.show(rowData);
        },
        handleDisable(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message: "Bạn chắc chắn muốn vô hiệu cấu hình này ?",
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
                            "/brandname/config/disable",
                            {
                                id: rowData.id
                            }
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

            $this.updateInfomationConfig();
        },
        handleActive(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message: "Bạn chắc chắn muốn kích hoạt cấu hình này ?",
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
                        let res = await axios.post("/brandname/config/active", {
                            id: rowData.id,
                            br_type: rowData.br_type,
                            active: rowData.active
                        });
                        const { data } = res;

                        if (data.code == 0) {
                            notifyActiveSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });

            $this.updateInfomationConfig();
        },
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        addConfig() {
            this.$refs.addModal.show();
        },
        async getVBroadCast() {
            try {
                const res = await axios.post(
                    "/brandname/config/select-v-broadcast"
                );
                const { data } = res;
                if (data.length > 0) {
                    this.dateVBr = data[0].date_threshold;
                    this.monthVBr = data[0].month_threshold;
                } else {
                    this.dateVBr = 0;
                    this.monthVBr = 0;
                }
            } catch (e) {
                console.log(e);
            }
        },
        async getOtherBroadCast() {
            try {
                const res = await axios.post(
                    "/brandname/config/select-other-broadcast"
                );
                const { data } = res;
                if (data.length > 0) {
                    this.dateOBr = data[0].date_threshold;
                    this.monthOBr = data[0].month_threshold;
                } else {
                    this.dateOBr = 0;
                    this.monthOBr = 0;
                }
            } catch (e) {
                console.log(e);
            }
        },
        updateInfomationConfig() {
            this.getOtherBroadCast();
            this.getVBroadCast();
        }
    }
};
</script>

<style scoped></style>
