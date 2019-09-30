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
                                    <list-filter
                                        :is-required-to-export="
                                            isRequiredToExport
                                        "
                                        @search="search"
                                        @isExportFileSuccessfully="
                                            isExportFileSuccessfully
                                        "
                                    ></list-filter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <portlet title="Danh sách Blacklist/Whitelist">
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
                    <data-table
                        ref="table"
                        :columns="columns"
                        :actions="actions"
                        url="/blackwhite/list/listing"
                        :order-column-index="10"
                        :order-type="'desc'"
                        :fixed-columns-left="3"
                        :fixed-columns-right="2"
                        :post-data="tableFilter"
                    ></data-table
                ></portlet>
            </div>
            <list-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></list-modal>
            <list-import-modal
                ref="importModal"
                :on-action-success="updateItemSuccess"
            ></list-import-modal>
        </div>
    </div>
</template>

<script>
import Portlet from "../../components/common/Portlet";
import DataTable from "../../components/common/DataTable";
import moment from "moment";
import ListModal from "./partials/ListModal";
import { generateTableAction, htmlEscapeEntities } from "~/helpers/tableHelper";
import bootbox from "bootbox";
import axios from "axios";
import {
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import ListFilter from "./partials/ListFilter";
import ListImportModal from "./partials/ListImportModal";

export default {
    name: "List",
    components: { ListImportModal, ListFilter, ListModal, DataTable, Portlet },
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
                    title: "Đầu số"
                },
                {
                    data: "type",
                    title: "Loại đầu số",
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
                    title: "Nhà mạng",
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "manager.name",
                    title: "Đơn vị quản lý",
                    orderable: false
                },
                {
                    data: "active",
                    title: "Trạng thái",
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
                    title: "Mô tả",
                    orderable: false
                },
                {
                    data: "who_created",
                    title: "Người tạo",
                    orderable: false
                },
                {
                    data: "created_at",
                    title: "Thời gian tạo"
                },
                {
                    data: "who_updated",
                    title: "Người cập nhật",
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "updated_at",
                    title: "Thời gian cập nhật",
                    render(data) {
                        if (data != null) return data;
                    }
                },
                {
                    data: "url",
                    title: "PYC",
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return (
                                '<a title="Download" href="' +
                                data +
                                '"><span class="la la-download"></span></a>'
                            );
                        } else return "-";
                    }
                },
                {
                    data: null,
                    title: "Hành động",
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
        // async filterTime(data) {
        //     this.tableFilter = data;
        //     await this.$nextTick();
        //     this.loadData();
        // },
        // async loadData() {
        //     this.$refs.table.reload();
        // },
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
                            id: [rowData.id]
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
                            id: [rowData.id]
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

<style scoped></style>
