<template>
    <div class="row">
        <div class="col-md-12">
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
                                <user-filter @search="search"></user-filter>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <the-portlet :title="$t('admin.users.title.datatable')">
                <v-button
                    slot="tool"
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="addUser"
                >
                    <span>
                        <i class="la la-plus"></i>
                        <span>{{ $t("button.add") }}</span>
                    </span>
                </v-button>
                <data-table
                    ref="table"
                    url="/admin/user/listing"
                    :columns="columns"
                    :actions="actions"
                    :search-placeholder="$t('admin.users.placeholder.name')"
                    :post-data="tableFilter"
                    :fixed-columns-left="3"
                    :fixed-columns-right="1"
                    :searching="false"
                    :order-column-index="6"
                    :order-type="'desc'"
                />
            </the-portlet>
            <user-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></user-modal>
            <renew-password-modal
                ref="renewPasswordModal"
                :on-action-success="updateItemSuccess"
            ></renew-password-modal>
        </div>
    </div>
</template>

<script>
import * as ROLE from "~/constants/roles";
import axios from "axios";
import ThePortlet from "~/components/common/Portlet";
import DataTable from "~/components/common/DataTable";
import UserModal from "../User/partials/UserModal";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import { formatDate } from "~/helpers/formats";
import {
    notifyTryAgain,
    notifyDisableSuccess,
    notifyActiveSuccess
} from "~/helpers/bootstrap-notify";
import bootbox from "bootbox";
import UserFilter from "./partials/UserFilter";
import RenewPasswordModal from "./partials/RenewPasswordModal";
import i18n from "~/plugins/i18n";

export default {
    components: {
        RenewPasswordModal,
        UserFilter,
        ThePortlet,
        DataTable,
        UserModal
    },
    middleware: "auth",
    head() {
        return {
            title: this.$t("admin.users.manage")
        };
    },
    meta: {
        title: "admin.users.manage",
        roles: [ROLE.ROLE_ADMIN, ROLE.ROLE_ROOT]
    },
    data() {
        return {
            tableFilter: {
                role: null,
                status: null,
                name: null
            },
            roleList: [],
            roleOptions: {
                placeholder: this.$t("admin.users.placeholder.select_role"),
                multiple: true,
                searchable: true,
                options: [],
                textField: "text",
                idField: "id"
            }
        };
    },
    computed: {
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
                },
                {
                    type: "click",
                    name: "handlePassword",
                    action: this.handlePassword
                }
            ];
        },
        columns() {
            return [
                {
                    data: "name",
                    title: this.$t("admin.users.datatable.column.name")
                },
                {
                    data: "display_name",
                    title: this.$t("admin.users.datatable.column.display_name"),
                    orderable: false
                },
                {
                    data: "active",
                    title: this.$t("admin.users.datatable.column.active"),
                    orderable: false,
                    render(data) {
                        if (data === 1) {
                            return '<span class="text-success">Hoạt động</span>';
                        }
                        return '<span class="text-danger">Vô hiệu</span>';
                    }
                },
                {
                    data: "user_role",
                    title: this.$t("admin.users.datatable.column.role"),
                    orderable: false,
                    render(data) {
                        let html = "";
                        data.map(function(value) {
                            if (html === "") {
                                html +=
                                    "<li>" +
                                    htmlEscapeEntities(value.role.name) +
                                    "</li>";
                            } else {
                                html +=
                                    "<li>" +
                                    htmlEscapeEntities(value.role.name) +
                                    "</li>";
                            }
                        });
                        return html;
                    }
                },
                {
                    data: "who_updated",
                    title: this.$t("datatable.column.who_updated"),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "when_updated",
                    title: this.$t("datatable.column.when_updated"),
                    render(data) {
                        if (data != null) {
                            return formatDate(data);
                        } else return "-";
                    }
                },
                {
                    data: "expired_at",
                    title: this.$t("admin.users.datatable.column.expired_at"),
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
                                generateTableAction("edit", "handleEdit") +
                                generateTableAction(
                                    "disable",
                                    "handleDisable"
                                ) +
                                generateTableAction(
                                    "",
                                    "handlePassword",
                                    "warning",
                                    "la-lock",
                                    i18n.t("button.renew_password")
                                )
                            );
                        } else
                            return (
                                generateTableAction("active", "handleActive") +
                                generateTableAction(
                                    "renewPassword",
                                    "handlePassword",
                                    "warning",
                                    "la-lock",
                                    i18n.t("button.renew_password")
                                )
                            );
                    }
                }
            ];
        },
        statusOptions() {
            return {
                placeholder: this.$t("admin.users.placeholder.select_status"),
                multiple: true,
                searchable: true,
                options: [
                    {
                        id: 1,
                        text: this.$t("label.active")
                    },
                    {
                        id: 0,
                        text: this.$t("label.disable")
                    }
                ],
                textField: "text",
                idField: "id"
            };
        }
    },
    watch: {
        "tableFilter.name"() {
            this.updateItemSuccess();
        }
    },
    mounted() {
        this.loadingData();
    },
    methods: {
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        handleEdit(table, rowData) {
            this.$refs.addModal.show(rowData);
        },
        handlePassword(table, rowData) {
            this.$refs.renewPasswordModal.show(rowData);
        },
        addUser() {
            this.$refs.addModal.show();
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
                        let res = await axios.post("/admin/user/disable", {
                            id: rowData.id
                        });
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
                        let res = await axios.post("/admin/user/active", {
                            id: rowData.id
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
        },
        async getRole() {
            try {
                const res = await axios.post("/admin/role/listing");
                const { data } = res;

                this.roleList = data.data;
                // const displayName = [
                //     "A2P",
                //     "Admin",
                //     "CC",
                //     "Csp",
                //     "Politic",
                //     "Roaming",
                //     "Root",
                //     "Sms2way",
                //     "User"
                // ];
                // this.roleList.forEach(function(value, index) {
                //     value.display_name = displayName[index];
                // });
            } catch (e) {
                console.log(e);
            }
        },
        async loadingData() {
            await this.getRole();

            for (var i = 0; i < this.roleList.length; i++) {
                this.roleOptions.options.push({
                    id: this.roleList[i].id,
                    // text: this.roleList[i].display_name,
                    text: this.roleList[i].name,
                    display_name: this.roleList[i].display_name
                });
            }
        },
        async search(value) {
            this.tableFilter = value;
            await this.$nextTick();
            this.$refs.table.reload();
        }
    }
};
</script>
