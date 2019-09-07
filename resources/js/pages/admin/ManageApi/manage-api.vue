<template>
    <div>
        <the-portlet :title="$t('admin.api.account_api.portlet.title')">
            <v-button
                slot="tool"
                color="primary"
                style-type="air"
                class="m-btn m-btn--icon"
                @click.native="addAccountApi"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                ref="tableAccountApi"
                :columns="columnsAccountApi"
                url="/admin/manage-api/listing-account-api"
                :actions="actions"
                :fixed-columns-left="2"
                :fixed-columns-right="1"
                :search-placeholder="
                    $t('admin.api.account_api.placeholder.search_placeholder')
                "
                :order-column-index="6"
                :order-type="'desc'"
            ></data-table>
        </the-portlet>
        <the-portlet :title="$t('admin.api.portlet.title')">
            <v-button
                slot="tool"
                color="primary"
                style-type="air"
                class="m-btn m-btn--icon"
                @click.native="addApi"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                ref="tableApi"
                url="/admin/manage-api/listing-api"
                :actions="actions"
                :columns="columnsApi"
                :fixed-columns-left="2"
                :fixed-columns-right="1"
                :search-placeholder="
                    $t('admin.api.placeholder.search_placeholder')
                "
                :order-column-index="6"
                :order-type="'desc'"
            ></data-table>
        </the-portlet>
        <manage-api-modal
            ref="apiModal"
            :on-action-success="updateItemSuccessApi"
        ></manage-api-modal>
        <manage-account-api-modal
            ref="accountApiModal"
            :on-action-success="updateItemSuccess"
        ></manage-account-api-modal>
    </div>
</template>

<script>
import * as ROLE from "~/constants/roles";

import ThePortlet from "~/components/common/Portlet";
import DataTable from "~/components/common/DataTable";
import { formatDate } from "~/helpers/formats";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import bootbox from "bootbox";
import axios from "axios";
import {
    notifyTryAgain,
    notifyDisableSuccess,
    notifyActiveSuccess
} from "~/helpers/bootstrap-notify";
import { notify } from "~/helpers/bootstrap-notify";
import ManageApiModal from "./partials/ManageApiModal";
import ManageAccountApiModal from "./partials/ManageAccountApiModal";

export default {
    components: {
        ManageAccountApiModal,
        ManageApiModal,
        ThePortlet,
        DataTable
    },
    middleware: "auth",
    head() {
        return {
            title: this.$t("admin.api.manage")
        };
    },
    meta: {
        title: "admin.api.manage",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN]
    },
    computed: {
        columnsAccountApi() {
            return [
                {
                    data: "name",
                    title: this.$t(
                        "admin.api.account_api.datatable.column.name"
                    )
                },
                {
                    data: "secret_key",
                    title: this.$t(
                        "admin.api.account_api.datatable.column.secret_key"
                    ),
                    orderable: false
                },
                {
                    data: "api_account_apis",
                    title: this.$t(
                        "admin.api.account_api.datatable.column.api_account_apis"
                    ),
                    orderable: false,
                    render(data) {
                        let html = "";
                        data.map(function(value) {
                            if (html === "") {
                                html +=
                                    "<li>" +
                                    htmlEscapeEntities(value.api_account.name) +
                                    "</li>";
                            } else {
                                html +=
                                    "<li>" +
                                    htmlEscapeEntities(value.api_account.name) +
                                    "</li>";
                            }
                        });
                        return html;
                    }
                },
                {
                    data: "active",
                    title: this.$t(
                        "admin.api.account_api.datatable.column.active"
                    ),
                    orderable: false,
                    render(data) {
                        if (data === 1) {
                            return htmlEscapeEntities("Hoạt động");
                        }
                        return htmlEscapeEntities("Vô hiệu");
                    }
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
                        if (data != null) {
                            return formatDate(data);
                        }
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
                            return (
                                generateTableAction(
                                    "edit",
                                    "handleEditAccountApi"
                                ) +
                                generateTableAction(
                                    "disable",
                                    "handleDisableAccountApi"
                                ) +
                                generateTableAction(
                                    "refreshKey",
                                    "handleRefreshKey"
                                )
                            );
                        } else
                            return generateTableAction(
                                "active",
                                "handleActiveAccountApi"
                            );
                    }
                }
            ];
        },
        columnsApi() {
            return [
                {
                    data: "name",
                    title: this.$t("admin.api.datatable.column.name")
                },
                {
                    data: "display_name",
                    title: this.$t("admin.api.datatable.column.display_name"),
                    orderable: false
                },
                {
                    data: "description",
                    title: this.$t("admin.api.datatable.column.description"),
                    orderable: false
                },
                {
                    data: "active",
                    title: this.$t("admin.api.datatable.column.active"),
                    orderable: false,
                    render(data) {
                        if (data === 1) {
                            return htmlEscapeEntities("Hoạt động");
                        }
                        return htmlEscapeEntities("Vô hiệu");
                    }
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
                        if (data != null) {
                            return formatDate(data);
                        }
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
                            return (
                                generateTableAction("edit", "handleEditApi") +
                                generateTableAction(
                                    "disable",
                                    "handleDisableApi"
                                )
                            );
                        } else
                            return generateTableAction(
                                "active",
                                "handleActiveApi"
                            );
                    }
                }
            ];
        },
        actions() {
            return [
                {
                    type: "click",
                    name: "handleEditApi",
                    action: this.handleEditApi
                },
                {
                    type: "click",
                    name: "handleDisableApi",
                    action: this.handleDisableApi
                },
                {
                    type: "click",
                    name: "handleActiveApi",
                    action: this.handleActiveApi
                },
                {
                    type: "click",
                    name: "handleEditAccountApi",
                    action: this.handleEditAccountApi
                },
                {
                    type: "click",
                    name: "handleDisableAccountApi",
                    action: this.handleDisableAccountApi
                },
                {
                    type: "click",
                    name: "handleActiveAccountApi",
                    action: this.handleActiveAccountApi
                },
                {
                    type: "click",
                    name: "handleRefreshKey",
                    action: this.handleRefreshKey
                }
            ];
        }
    },
    methods: {
        updateItemSuccess() {
            this.$refs.tableAccountApi.reload();
        },
        updateItemSuccessApi() {
            this.$refs.tableApi.reload();
        },
        handleEditApi(table, rowData) {
            this.$refs.apiModal.show(rowData);
        },
        handleDisableApi(table, rowData) {
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
                            "/admin/manage-api/disable-api",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDisableSuccess();
                            reloadIntelligently($this.$refs.tableApi);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        handleActiveApi(table, rowData) {
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
                            "/admin/manage-api/active-api",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyActiveSuccess();
                            reloadIntelligently($this.$refs.tableApi);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        addApi() {
            this.$refs.apiModal.show();
        },
        handleEditAccountApi(table, rowData) {
            this.$refs.accountApiModal.show(rowData);
        },
        handleDisableAccountApi(table, rowData) {
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
                            "/admin/manage-api/disable-account-api",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDisableSuccess;
                            reloadIntelligently($this.$refs.tableAccountApi);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        handleActiveAccountApi(table, rowData) {
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
                            "/admin/manage-api/active-account-api",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyActiveSuccess();
                            reloadIntelligently($this.$refs.tableAccountApi);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        addAccountApi() {
            this.$refs.accountApiModal.show();
        },
        handleRefreshKey(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn thay đổi khóa bí mật của "<span class="text-danger">' +
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
                            "/admin/manage-api/reset-key",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notify(
                                $this.$t("label.notification"),
                                $this.$t(
                                    "admin.api.account_api.notification.reset_success"
                                ),
                                "success"
                            );
                            reloadIntelligently($this.$refs.tableAccountApi);
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
