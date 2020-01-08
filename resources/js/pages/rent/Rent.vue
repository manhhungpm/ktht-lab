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
                    :fixed-columns-left="1"
                    :fixed-columns-right="1"
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
        notifyBorrowSuccess,
        notifyApprovedSuccess,
        notifyDenySuccess,
        notifyPaySuccess
    } from "~/helpers/bootstrap-notify";
    import RentModal from "./partials/RentModal";
    import RentFilter from "./partials/RentFilter";
    import {APPROVED, BORROW, DENY, PAY, WAIT_APPROVED} from "../../constants/constant";
    import {toNormalDate} from "../../helpers/formats";

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
            roleAdmin() {
                return this.$hasRole("admin");
            },
            roleLeader() {
                return this.$hasRole("leader");
            },
            roleStocker() {
                return this.$hasRole("stocker");
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
                        render(data) {
                            return toNormalDate(data)
                        }
                    },
                    {
                        data: "due_date",
                        title: this.$t("rent.due_date"),
                        render(data) {
                            return toNormalDate(data)
                        }
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
                                if (data == BORROW) {
                                    return `<span class='text-warning'>Đang mượn</span>`;
                                } else if (data == PAY) {
                                    return `<span class='text-primary'>Đã trả</span>`;
                                } else if (data == WAIT_APPROVED) {
                                    return `<span class='text-accent'>Chờ duyệt</span>`;
                                } else if (data == DENY) {
                                    return `<span class='text-danger'>Từ chối duyệt</span>`;
                                } else if (data == APPROVED) {
                                    return `<span class='text-success'>Được phê duyệt</span>`;
                                }
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: "project.name",
                        title: "Dự án"
                    },
                    {
                        data: "priority",
                        title: "Độ ưu tiên",
                        render(data) {
                            switch (data) {
                                case 1:
                                    return "Cao";
                                case 2:
                                    return "Trung bình";
                                case 3:
                                    return "Thấp;"
                            }
                        }
                    },
                    {
                        data: "leader.name",
                        title: "Người phê duyệt"
                    },
                    {
                        data: "number_of_email",
                        title: "Số lần gửi mail cảnh báo",
                        className: "wrap-text"
                    },
                    {
                        data: "status",
                        title: this.$t("common.action"),
                        orderable: false,
                        className: "text-center",
                        responsivePriority: 1,
                        render(data, type, row) {
                            // console.log(row['leader']['name'])
                            if ($this.roleAdmin) { //Quyền admin
                                switch (data) {
                                    case PAY: //đã trả -> không có hành động nào khác
                                        return "Không có hành động sau khi đã trả thiết bị";
                                    case WAIT_APPROVED: //chờ duyệt -> có thể duyệt hoặc từ chối
                                        return (
                                            generateTableAction("deny", "handleDeny", "danger", "la-frown-o", "Từ chối") +
                                            generateTableAction("approved", "handleApproved", "success", "la-smile-o", "Phê duyệt")
                                        );
                                    case BORROW: //đang mượn -> có thể edit hoặc trả
                                        return (
                                            generateTableAction("edit", "handleEdit") +
                                            generateTableAction("pay", "handlePay", "success", "la-check", "Trả đồ") +
                                            generateTableAction("sendMail", "sendMail", "danger", "la-send-o", "Gửi mail")
                                        );
                                    case DENY: //từ chối
                                        return "Bị từ chối phê duyệt";
                                    case APPROVED: //đã được phê duyệt ->  cho mượn
                                        return (generateTableAction("edit", "handleEdit") +
                                            generateTableAction("borrow", "handleBorrow", "success", "la-cart-plus", "Cho mượn"))
                                }
                            } else if ($this.roleLeader) {
                                if (row['leader']['name'] == $this.$store.state.auth.user.info.name) {
                                    switch (data) {
                                        case PAY: //đã trả -> không có hành động nào khác
                                            return "Không có hành động sau khi đã trả thiết bị";
                                        case WAIT_APPROVED: //chờ duyệt -> có thể duyệt hoặc từ chối
                                            return (
                                                generateTableAction("deny", "handleDeny", "danger", "la-frown-o", "Từ chối") +
                                                generateTableAction("approved", "handleApproved", "success", "la-smile-o", "Phê duyệt")
                                            );
                                        case BORROW: //đang mượn -> có thể edit hoặc trả
                                            return "Không có quyền thực hiện hành động này vì bạn chỉ có quyền Trưởng nhóm"
                                        case DENY: //từ chối
                                            return "Bị từ chối phê duyệt";
                                        case APPROVED: //đã được phê duyệt ->  cho mượn
                                            return "Không có quyền thực hiện hành động này vì bạn chỉ có quyền Trưởng nhóm"
                                    }
                                } else {
                                    return "Không có quyền Từ chối hoặc Phê duyệt đơn này"
                                }
                            } else if ($this.roleStocker) {
                                switch (data) {
                                    case PAY: //đã trả -> không có hành động nào khác
                                        return "Không có hành động sau khi đã trả thiết bị";
                                    case WAIT_APPROVED: //chờ duyệt -> có thể duyệt hoặc từ chối
                                        return "Không có quyền thực hiện hành động này vì bạn chỉ có quyền Thủ kho"
                                    case BORROW: //đang mượn -> có thể edit hoặc trả
                                        return (
                                            generateTableAction("edit", "handleEdit") +
                                            generateTableAction("pay", "handlePay", "success", "la-check", "Trả đồ")
                                        );
                                    case DENY: //từ chối
                                        return "Bị từ chối phê duyệt";
                                    case APPROVED: //đã được phê duyệt ->  cho mượn
                                        return (generateTableAction("edit", "handleEdit") +
                                            generateTableAction("borrow", "handleBorrow", "success", "la-cart-plus", "Cho mượn"))
                                }
                            } else {
                                return "Không có quyền thực hiện hành động này vì bạn chỉ có quyền Người dùng"
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
                        name: "handleBorrow",
                        action: this.handleBorrow
                    },
                    {
                        type: "click",
                        name: "handlePay",
                        action: this.handlePay
                    },
                    {
                        type: "click",
                        name: "handleApproved",
                        action: this.handleApproved
                    },
                    {
                        type: "click",
                        name: "handleDeny",
                        action: this.handleDeny
                    },
                    {
                        type: "click",
                        name: "sendMail",
                        action: this.sendMail
                    }
                ];
            }
        },
        mounted() {
            // console.log(this.$store.state.auth.user.info.name);
        },
        methods: {
            sendMail(table, rowData) {
                let $this = this;
                console.log(rowData)
                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message:
                        'Bạn muốn gửi mail cảnh báo',
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
                            let res = await axios.post("/rent/send-email", {
                                data: rowData
                            });
                            const {data} = res;

                            if (data.code == 0) {
                                notifyDenySuccess();
                                reloadIntelligently($this.$refs.table);
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            },
            handleDeny(table, rowData) {
                let $this = this;

                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message:
                        'Từ chối phê duyệt đơn mượn ?',
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
                            let res = await axios.post("/rent/deny", {
                                id: rowData.id
                            });
                            const {data} = res;

                            if (data.code == 0) {
                                notifyDenySuccess();
                                reloadIntelligently($this.$refs.table);
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            },
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
                                notifyApprovedSuccess();
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
            async handlePay(table, rowData) {
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
                            let res = await axios.post("/rent/pay", {
                                id: rowData.id
                            });
                            const {data} = res;

                            if (data.code == 0) {
                                notifyPaySuccess();
                                reloadIntelligently($this.$refs.table);
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            },
            async handleBorrow(table, rowData) {
                let $this = this;

                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message:
                        'Chuyển trạng thái cho mượn thiết bị?',
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
                            let res = await axios.post("/rent/borrow", {
                                id: rowData.id
                            });
                            const {data} = res;

                            if (data.code == 0) {
                                notifyBorrowSuccess();
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
