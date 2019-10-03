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
                                    <black-white-list-filter
                                        :is-required-to-export="
                                            isRequiredToExport
                                        "
                                        @search="search"
                                        @isExportFileSuccessfully="
                                            isExportFileSuccessfully
                                        "
                                    ></black-white-list-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <portlet :title="$t('blackwhite.list.title')">
                    <v-button
                        slot="tool"
                        color="primary"
                        style-type="air"
                        class="m-btn m-btn--icon"
                        style="margin-right: 5px"
                        @click.native="addAlias"
                    >
                        <span>
                            <i class="la la-plus"></i>
                            <span>{{ $t("button.add") }}</span>
                        </span>
                    </v-button>
                    <v-button
                        slot="tool"
                        color="success"
                        style-type="air"
                        class="m-btn m-btn--icon"
                        @click.native="importAlias"
                    >
                        <span>
                            <i class="la la-cloud-upload"></i>
                            <span>{{ $t("button.import") }}</span>
                        </span>
                    </v-button>
                    <v-button
                        color="success"
                        style-type="air"
                        class="m-btn m-btn--icon"
                        style="margin-bottom: 18px"
                        @click.native="activeIsSelected"
                    >
                        <span>
                            <i class="la la-check-circle"></i>
                            <span>{{ $t("button.active") }}</span>
                        </span>
                    </v-button>
                    <v-button
                        color="danger"
                        style-type="air"
                        class="m-btn m-btn--icon"
                        style="margin-bottom: 18px"
                        @click.native="disableIsSelected"
                    >
                        <span>
                            <i class="la la-ban"></i>
                            <span>{{ $t("button.disable") }}</span>
                        </span>
                    </v-button>
                    <data-table
                        ref="table"
                        :columns="columns"
                        :actions="actions"
                        url="/blackwhite/list/listing"
                        :order-column-index="11"
                        :order-type="'desc'"
                        :fixed-columns-left="3"
                        :fixed-columns-right="2"
                        :post-data="tableFilter"
                        :selectable="true"
                        :search-placeholder="
                            $t('blackwhite.list.search_placeholder')
                        "
                    ></data-table
                ></portlet>
            </div>
            <black-white-list-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></black-white-list-modal>
            <black-white-list-import-modal
                ref="importModal"
                :on-action-success="updateItemSuccess"
            ></black-white-list-import-modal>
            <action-modal
                ref="actionModal"
                :on-action-success="updateItemSuccess"
            ></action-modal>
        </div>
    </div>
</template>

<script>
import Portlet from "~/components/common/Portlet";
import DataTable from "~/components/common/DataTable";
import BlackWhiteListModal from "./partials/BlackWhiteListModal";
import { generateTableAction, htmlEscapeEntities } from "~/helpers/tableHelper";
import bootbox from "bootbox";
import axios from "axios";
import {
    notify,
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import BlackWhiteListFilter from "./partials/BlackWhiteListFilter";
import BlackWhiteListImportModal from "./partials/BlackWhiteListImportModal";
import ActionModal from "./partials/ActionModal";

export default {
    name: "BlackWhiteList",
    middleware: "auth",
    components: {
        ActionModal,
        BlackWhiteListImportModal,
        BlackWhiteListFilter,
        BlackWhiteListModal,
        DataTable,
        Portlet
    },
    data() {
        return {
            tableFilter: {
                provider: null,
                manager: null,
                type: null,
                who_created: null,
                who_updated: null,
                created_at: null,
                updated_at: null
            },
            exportLoading: false,
            isRequiredToExport: false
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "alias",
                    title: this.$t("blackwhite.list.alias")
                },
                {
                    data: "type",
                    title: this.$t("blackwhite.list.type"),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            if (data == 1) {
                                return "White list";
                            } else {
                                return "Black list";
                            }
                        } else return "-";
                    }
                },
                {
                    data: "provider",
                    title: this.$t("blackwhite.list.provider"),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "manager.name",
                    title: this.$t("blackwhite.list.manager"),
                    orderable: false
                },
                {
                    data: "active",
                    title: this.$t("datatable.column.status"),
                    render(data) {
                        if (data != null) {
                            if (data == 1) {
                                return `<span class='text-success'>Hoạt động</span>`;
                            } else {
                                return `<span class='text-danger'>Vô hiệu</span>`;
                            }
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: "description",
                    title: this.$t("datatable.column.description"),
                    orderable: false
                },
                {
                    data: "who_created",
                    title: this.$t("datatable.column.who_created"),
                    orderable: false
                },
                {
                    data: "created_at",
                    title: this.$t("datatable.column.when_created")
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
                    data: "updated_at",
                    title: this.$t("datatable.column.when_updated"),
                    render(data) {
                        if (data != null) return data;
                    }
                },
                {
                    data: "file",
                    title: this.$t("blackwhite.list.file"),
                    className: "tb-actions",
                    width: "150px",
                    render(data) {
                        if (data == null || data == "[]") {
                            return "-";
                        } else {
                            let files = JSON.parse(data);
                            let filenameList = "";
                            for (let i = 0; i < files.length; i++) {
                                filenameList =
                                    filenameList +
                                    `<a href="/storage/${files[i].path}" target="_blank">${files[i].name}</a>`;
                            }
                            return filenameList;
                        }
                    }
                },
                {
                    data: null,
                    title: this.$t("datatable.column.action"),
                    orderable: false,
                    className: "text-center tb-actions",
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
                    name: "handleDisable",
                    action: this.handleDisable
                },
                {
                    type: "click",
                    name: "handleEdit",
                    action: this.handleEdit
                },
                {
                    type: "click",
                    name: "handleActive",
                    action: this.handleActive
                }
            ];
        }
    },
    methods: {
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        handleEdit(table, rowData) {
            this.$refs.addModal.show(rowData);
        },
        addAlias() {
            this.$refs.addModal.show();
        },
        importAlias() {
            this.$refs.importModal.show();
        },
        async activeIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const ids = this.$refs.table.getSelectedRowsIds();

            const alias = Array.from(rows, e => {
                return {
                    alias: e.alias
                };
            });

            if (ids.length > 0) {
                this.$refs.actionModal.show(ids, alias, true);
            } else {
                notify(
                    this.$t("label.notification"),
                    this.$t("notification.must_select_at_least_one_record")
                );
            }
        },
        async disableIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const ids = this.$refs.table.getSelectedRowsIds();
            const alias = Array.from(rows, e => {
                return {
                    alias: e.alias
                };
            });

            if (ids.length > 0) {
                this.$refs.actionModal.show(ids, alias, false);
            } else {
                notify(
                    this.$t("label.notification"),
                    this.$t("notification.must_select_at_least_one_record")
                );
            }
        },
        handleDisable(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn vô hiệu đấu số: "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.alias) +
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
                        let res = await axios.post("/blackwhite/list/disable", {
                            ids: [rowData.id]
                        });
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDisableSuccess();
                            $this.$refs.table.reload();
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        handleActive(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn kích hoạt đầu số: "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.alias) +
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
                        let res = await axios.post("/blackwhite/list/active", {
                            ids: [rowData.id]
                        });
                        const { data } = res;

                        if (data.code == 0) {
                            notifyActiveSuccess();
                            $this.$refs.table.reload();
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        async search(value) {
            this.tableFilter = value;
            await this.$nextTick();
            this.$refs.table.reload();
        },
        async exportFile() {
            this.exportLoading = true;
            this.isRequiredToExport = true;
        },
        isExportFileSuccessfully() {
            this.exportLoading = false;
            this.isRequiredToExport = false;
        }
    }
};
</script>

<style></style>
