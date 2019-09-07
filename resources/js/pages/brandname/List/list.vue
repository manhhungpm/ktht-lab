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
                                <list-filter
                                    :is-required-to-export="isRequiredToExport"
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
        <div class="col-md-12">
            <the-portlet :title="$t('brandname.list.title')">
                <v-button
                    slot="tool"
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="addBrandName"
                >
                    <span>
                        <i class="la la-plus"></i>
                        <span>{{ $t("button.add") }}</span>
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
                    :selectable="true"
                    :columns="columns"
                    :actions="actions"
                    url="/brandname/list/listing"
                    :fixed-columns-left="3"
                    :fixed-columns-right="1"
                    :post-data="tableFilter"
                    :searching="false"
                    :order-column-index="7"
                    :order-type="'desc'"
                ></data-table>
            </the-portlet>
            <list-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></list-modal>
            <action-modal
                ref="actionModal"
                :on-action-success="updateItemSuccess"
            ></action-modal>
        </div>
    </div>
</template>

<script>
import * as ROLE from "~/constants/roles";

import ThePortlet from "~/components/common/Portlet";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import { formatDate } from "~/helpers/formats";
import { OTHER_BROADCAST, V_BROADCAST } from "~/constants/constant";
import ListModal from "../List/partials/ListModal";
import ActionModal from "../List/partials/ActionModal";
import bootbox from "bootbox";
import axios from "axios";
import FormControl from "~/components/common/FormControl";
import {
    notify,
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import i18n from "~/plugins/i18n";
import ListFilter from "./partials/ListFilter";
import moment from "moment";

export default {
    components: { ListFilter, ListModal, ThePortlet, ActionModal },
    middleware: "auth",
    head() {
        return {
            title: this.$t("brandname.list.title")
        };
    },
    meta: {
        title: "brandname.list.title",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_CC]
    },
    data() {
        return {
            tableFilter: {
                brandname: null,
                br_type: null,
                status: null
            },
            exportLoading: false,
            statusOptions: {
                placeholder: i18n.t("brandname.list.placeholder.active"),
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
                ]
            },
            brTypeOptions: {
                placeholder: i18n.t("brandname.list.placeholder.br_type"),
                multiple: true,
                searchable: true,
                options: [
                    {
                        id: V_BROADCAST,
                        text: "Đầu số Viettel quản lý"
                    },
                    {
                        id: OTHER_BROADCAST,
                        text: "Đầu số đối tác quản lý"
                    }
                ]
            },
            isRequiredToExport: false
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "brandname",
                    title: this.$t("brandname.list.datatable.column.brandname")
                },
                {
                    data: "br_type",
                    title: this.$t("brandname.list.datatable.column.br_type"),
                    render(data) {
                        if (data === V_BROADCAST)
                            return htmlEscapeEntities("Đầu số VIETTEL quản lý");
                        return htmlEscapeEntities("Đầu số đối tác quản lý");
                    }
                },
                {
                    data: "active",
                    title: this.$t("brandname.list.datatable.column.active"),
                    render(data) {
                        if (data === 1) return htmlEscapeEntities("Hoạt động");
                        return htmlEscapeEntities("Vô hiệu");
                    },
                    orderable: false
                },
                {
                    data: "reason",
                    title: this.$t("brandname.list.datatable.column.reason")
                },
                {
                    data: "who_created",
                    title: this.$t("datatable.column.who_created")
                },
                {
                    data: "when_created",
                    title: this.$t("datatable.column.when_created"),
                    render(data) {
                        if (data != null)
                            return moment(data).format("YYYY-MM-DD hh:mm:ss");
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
    watch: {
        "tableFilter.brandname"() {
            this.updateItemSuccess();
        }
    },
    mounted() {},
    methods: {
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        handleDisable(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn vô hiệu "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.brandname) +
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
                        let res = await axios.post("/brandname/list/disable", {
                            ids: [rowData.id]
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
        handleActive(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn kích hoạt "<span class="text-danger">' +
                    htmlEscapeEntities(rowData.brandname) +
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
                        let res = await axios.post("/brandname/list/active", {
                            ids: [rowData.id]
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
        handleEdit(table, rowData) {
            this.$refs.addModal.show(rowData);
        },
        activeIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const ids = Array.from(rows, e => {
                return {
                    id: e.id
                };
            });

            const brandnames = Array.from(rows, e => {
                return {
                    brandname: e.brandname
                };
            });

            if (ids.length > 0) {
                this.$refs.actionModal.show(ids, brandnames, true);
            } else {
                notify(
                    this.$t("label.notification"),
                    this.$t(
                        "brandname.list.notification.must_select_at_least_one_record"
                    )
                );
            }
        },
        disableIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const ids = Array.from(rows, e => {
                return {
                    id: e.id
                };
            });
            const brandnames = Array.from(rows, e => {
                return {
                    brandname: e.brandname
                };
            });
            if (ids.length > 0) {
                this.$refs.actionModal.show(ids, brandnames, false);
            } else {
                notify(
                    this.$t("label.notification"),
                    this.$t(
                        "brandname.list.notification.must_select_at_least_one_record"
                    )
                );
            }
        },
        addBrandName() {
            this.$refs.addModal.show();
        },
        // search() {
        //     this.$nextTick();
        //     this.$refs.table.reload();
        // },
        // async exportFile() {
        //     try {
        //         this.exportLoading = true;
        //         const searchParams = this.tableFilter;
        //         const { data } = await axios.post(
        //             "/brandname/list/listing",
        //             searchParams
        //         );
        //
        //         if (data.recordsTotal) {
        //             try {
        //                 const res = await axios.post(
        //                     "/brandname/list/export",
        //                     searchParams,
        //                     {
        //                         responseType: "blob"
        //                     }
        //                 );
        //                 downloadFile(res);
        //                 this.exportLoading = false;
        //             } catch (e) {
        //                 notify(
        //                     this.$t("brandname.list.notification.export_error")
        //                 );
        //                 this.exportLoading = false;
        //             }
        //         } else {
        //             notifyTryAgain();
        //             this.exportLoading = false;
        //         }
        //     } catch (e) {
        //         const { status } = e.response;
        //
        //         if (status != 403) {
        //             notifyTryAgain();
        //         }
        //     }
        // },
        //Filter
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
