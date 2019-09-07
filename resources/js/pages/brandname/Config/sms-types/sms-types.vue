<template
    ><div>
        <the-portlet :title="$t('brandname.sms_types.datatable.title')">
            <v-button
                slot="tool"
                color="primary"
                style-type="air"
                class="m-btn m-btn--icon"
                @click.native="addSmsTypes"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                ref="table"
                :columns="columns"
                url="/brandname/sms-types/listing"
                :fixed-columns-left="3"
                :fixed-columns-right="2"
                :actions="actions"
                :search-placeholder="
                    $t('brandname.sms_types.placeholder.search_placeholder')
                "
                :order-column-index="8"
                :order-type="'desc'"
            >
            </data-table
        ></the-portlet>
        <sms-types-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></sms-types-modal>
    </div>
</template>

<script>
import * as ROLE from "~/constants/roles";
import bootbox from "bootbox";
import axios from "axios";
import ThePortlet from "~/components/common/Portlet";
import { formatDate } from "~/helpers/formats";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import {
    notifyTryAgain,
    notifyDeleteSuccess,
    notifyActiveSuccess,
    notifyDisableSuccess,
    notify
} from "~/helpers/bootstrap-notify";
import SmsTypesModal from "./partials/SmsTypesModal";

export default {
    name: "SmsTypes",
    components: { SmsTypesModal, ThePortlet },
    middleware: "auth",
    head() {
        return {
            title: this.$t("brandname.sms_types.title")
        };
    },
    meta: {
        title: "brandname.sms_types.title",
        roles: [ROLE.ROLE_ADMIN, ROLE.ROLE_ROOT]
    },
    data() {
        return {};
    },
    computed: {
        columns() {
            return [
                {
                    data: "name",
                    title: this.$t("brandname.sms_types.datatable.column.name"),
                    className: "wrap-text"
                },
                {
                    data: "brandname_sms_group",
                    title: this.$t(
                        "brandname.sms_types.datatable.column.group"
                    ),
                    className: "wrap-text",
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return htmlEscapeEntities(data.name);
                        }
                        return "Không có";
                    }
                },
                {
                    data: "description",
                    title: this.$t(
                        "brandname.sms_types.datatable.column.description"
                    ),
                    className: "wrap-text",
                    orderable: false
                },
                {
                    data: "prefix",
                    title: this.$t(
                        "brandname.sms_types.datatable.column.prefix"
                    ),
                    className: "wrap-text",
                    orderable: false
                },
                {
                    data: "alias",
                    title: this.$t(
                        "brandname.sms_types.datatable.column.alias"
                    ),
                    className: "wrap-text",
                    orderable: false
                },
                {
                    data: "priority",
                    title: this.$t(
                        "brandname.sms_types.datatable.column.priority"
                    ),
                    orderable: false
                },
                {
                    data: "who_updated",
                    title: this.$t("datatable.column.who_updated"),
                    orderable: false
                },
                {
                    data: "when_updated",
                    title: this.$t("datatable.column.when_updated"),
                    render(data) {
                        if (data != null) return data;
                    }
                },
                {
                    data: "who_created",
                    title: this.$t("datatable.column.who_created"),
                    orderable: false
                },
                {
                    data: "when_created",
                    title: this.$t("datatable.column.when_created"),
                    orderable: false,
                    render(data) {
                        if (data != null) return data;
                    }
                },
                {
                    data: "active",
                    title: this.$t("datatable.column.status"),
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
                    title: this.$t("datatable.column.action"),
                    orderable: false,
                    class: "dt-center",
                    responsivePriority: 1,
                    render(data) {
                        if (data) {
                            return generateTableAction(
                                "disable",
                                "handleDisable"
                            );
                        } else {
                            return (
                                generateTableAction("edit", "handleEdit") +
                                generateTableAction("active", "handleActive")
                            );
                        }
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
                    name: "handleActive",
                    action: this.handleActive
                },
                {
                    type: "click",
                    name: "handleDisable",
                    action: this.handleDisable
                }
            ];
        }
    },
    methods: {
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        addSmsTypes() {
            this.$refs.modal.show();
        },
        handleEdit(table, rowData) {
            this.$refs.modal.show(rowData);
        },
        async handleDelete(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn xóa "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.name) +
                    '</span>"?',
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
                            "/brandname/sms-types/delete",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDeleteSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        async handleActive(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn kích hoạt "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.name) +
                    '</span>"?',
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
                            "/brandname/sms-types/active",
                            {
                                id: rowData.id
                            }
                        );
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
        },
        async handleDisable(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn vô hiệu "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.name) +
                    '</span>"?',
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
                            "/brandname/sms-types/disable",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDisableSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else if (data.code == 3) {
                            notify(
                                $this.$t("label.notification"),
                                $this.$t(
                                    "notification.no_disable_because_config_active"
                                )
                            );
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        }
    }
};
</script>

<style scoped></style>
