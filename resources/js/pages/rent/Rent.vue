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
                            class="m-accordion__item-body collapse show"
                            role="tabpanel"
                            aria-labelledby="m_accordion_5_item_3_head"
                            data-parent="#m_accordion_5"
                        >
                            <div class="m-accordion__item-content">
                                <rent-filter
                                    :is-required-to-export="
                                            isRequiredToExport
                                        "
                                    @search="search"
                                    @isExportFileSuccessfully="
                                            isExportFileSuccessfully
                                        "
                                ></rent-filter>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <portlet :title="$t('rent.title')">
                <v-button
                    slot="tool"
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="addRent"
                >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
                </v-button>
                <data-table
                    ref="table"
                    :columns="columns"
                    url="/rent/listing"
                    :fixed-columns-left="2"
                    :fixed-columns-right="3"
                    :actions="actions"
                    :search-placeholder="$t('rent.placeholder.search')"
                    :order-column-index="2"
                    :order-type="'desc'"
                    :post-data="tableFilter"
                >
                </data-table>
            </portlet>
            <rent-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></rent-modal>
        </div>
    </div>
</template>

<script>
    import bootbox from "bootbox";
    import axios from "axios";
    import {
        generateTableAction,
        htmlEscapeEntities,
        reloadIntelligently
    } from "~/helpers/tableHelper";
    import {
        notifyTryAgain,
        notifyActiveSuccess,
        notifyDisableSuccess,
        notifyGiveBackSuccess
    } from "~/helpers/bootstrap-notify";
    import RentModal from "./partials/RentModal";
    import RentFilter from "./partials/RentFilter";

    export default {
        name: "Rent",
        components: {RentFilter, RentModal},
        middleware: "auth",
        data() {
            return {
                tableFilter: {
                    start_date: null,
                    due_date: null,
                    device_type: null,
                    status: null
                },
                exportLoading: false,
                isRequiredToExport: false
            };
        },
        computed: {
            role() {
                return this.$hasRole("admin");
            },
            roleReview() {
                return this.$hasRole("review");
            },
            columns() {
                let $this = this;
                return [
                    {
                        data: "user.display_name",
                        title: this.$t("rent.user"),
                    },
                    {
                        data: "start_date",
                        title: this.$t("rent.start_date"),
                    },
                    {
                        data: "due_date",
                        title: this.$t("rent.due_date"),
                    },
                    {
                        data: "device_type",
                        title: this.$t("rent.device_type"),
                        render(data) {
                            let html = "";
                            data.map(function (value) {
                                if (html === "") {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value.name) +
                                        "</li>";
                                } else {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value.name) +
                                        "</li>";
                                }
                            });
                            return html;
                        }
                    },
                    {
                        data: "device_type",
                        title: "Số lượng",
                        render(data) {
                            let html = "";
                            data.map(function (value) {
                                if (html === "") {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value.pivot.amount) +
                                        "</li>";
                                } else {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value.pivot.amount) +
                                        "</li>";
                                }
                            });
                            return html;
                        }
                    },
                    {
                        data: "description",
                        title: this.$t("common.description"),
                        orderable: false,
                        className: "wrap-text"
                    },
                    {
                        data: "status",
                        title: this.$t("common.status"),
                        render(data) {
                            if (data != null) {
                                if (data == 1) {
                                    return `<span class='text-danger'>Đang mượn</span>`;
                                } else if (data == 0) {
                                    return `<span class='text-success'>Đã trả</span>`;
                                } else if (data == 2) {
                                    return `<span class='text-warning'>Chờ duyệt</span>`;
                                }
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: "status",
                        title: this.$t("common.action"),
                        orderable: false,
                        className: "text-center",
                        responsivePriority: 1,
                        render(data) {
                            if ($this.role) {
                                if (data == 0) {
                                    return "Không có hành động";
                                } else if (data == 2) {
                                    return "Chờ phê duyệt";
                                } else {
                                    return (
                                        generateTableAction("edit", "handleEdit") +
                                        generateTableAction("pay", "handleActive", "success", "la-angellist", "Trả đồ")
                                    );
                                }
                            } else if ($this.roleReview) {
                                if (data == 0) {
                                    return "Không có hành động";
                                } else if (data == 2) {
                                    return generateTableAction("approved", "handleApproved", "warning", "la-check", "Phê duyệt mượn đồ")
                                } else {
                                    return "Không có quyền thực hiện hành động này"
                                }
                            } else {
                                return "Không có quyền thực hiện hành động này"
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
                    },
                    {
                        type: "click",
                        name: "handleApproved",
                        action: this.handleApproved
                    },
                ];
            }
        },
        methods: {
            handleApproved(table, rowData) {
                let $this = this;

                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message:
                        'Phê duyệt đơn mượn ?',
                    buttons: {
                        cancel: {
                            label: this.$t("button.cancel")
                        },
                        confirm: {
                            label: this.$t("button.accept")
                        }
                    },
                    callback: async function (result) {
                        if (result) {
                            let res = await axios.post("/rent/approved", {
                                id: rowData.id
                            });
                            const {data} = res;

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
            },
            updateItemSuccess() {
                this.$refs.table.reload();
            },
            addRent() {
                this.$refs.addModal.show();
            },
            handleEdit(table, rowData) {
                this.$refs.addModal.show(rowData);
            },
            async handleActive(table, rowData) {
                let $this = this;

                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message:
                        'Chuyển trạng thái trả thiết bị ?',
                    buttons: {
                        cancel: {
                            label: this.$t("button.cancel")
                        },
                        confirm: {
                            label: this.$t("button.accept")
                        }
                    },
                    callback: async function (result) {
                        if (result) {
                            let res = await axios.post("/rent/active", {
                                id: rowData.id
                            });
                            const {data} = res;

                            if (data.code == 0) {
                                notifyGiveBackSuccess();
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
                        'Chuyển trạng thái trả thiết bị?',
                    buttons: {
                        cancel: {
                            label: this.$t("button.cancel")
                        },
                        confirm: {
                            label: this.$t("button.accept")
                        }
                    },
                    callback: async function (result) {
                        if (result) {
                            let res = await axios.post("/rent/disable", {
                                id: rowData.id
                            });
                            const {data} = res;

                            if (data.code == 0) {
                                notifyDisableSuccess();
                                reloadIntelligently($this.$refs.table);
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>
