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
                                <detailed-report-filter
                                    @search="search"
                                ></detailed-report-filter>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <portlet :title="$t('brandname.report.detailed.title')">
                <v-button
                    slot="tool"
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="addRequest"
                >
                    <span>
                        <i class="la la-plus"></i>
                        <span>{{ $t("button.add_request") }}</span>
                    </span>
                </v-button>
                <data-table
                    ref="table"
                    url="/brandname/report-detail-request/listing"
                    :columns="columns"
                    :actions="actions"
                    :search-placeholder="$t('admin.users.placeholder.name')"
                    :post-data="tableFilter"
                    :fixed-columns-left="2"
                    :fixed-columns-right="2"
                    :searching="false"
                    :order-column-index="2"
                    :order-type="'desc'"
                />
            </portlet>
            <request-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></request-modal>
        </div>
    </div>
</template>

<script>
import * as ROLE from "~/constants/roles";
import axios from "axios";

import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import { formatDate } from "~/helpers/formats";

import { notifyTryAgain, notify } from "~/helpers/bootstrap-notify";
import bootbox from "bootbox";
import DetailedReportFilter from "./partials/DetailedReportFilter";
import RequestModal from "./partials/RequestModal";
import moment from "moment";
import { downloadFile } from "~/helpers/downloadFile";

export default {
    components: { RequestModal, DetailedReportFilter },
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
                from: null,
                to: null,
                status: null,
                created_at: null
            }
        };
    },
    computed: {
        actions() {
            return [
                {
                    type: "click",
                    name: "handleResend",
                    action: this.handleResend
                },
                {
                    type: "click",
                    name: "handleDownload",
                    action: this.handleDownload
                }
            ];
        },
        columns() {
            return [
                {
                    data: "user",
                    title: this.$t("datatable.column.who_created"),
                    render(data) {
                        if (data != null) {
                            return data.name;
                        }
                    },
                    orderable: false
                },
                {
                    data: "created_at",
                    title: this.$t("datatable.column.when_created"),
                    render(data) {
                        if (data != null) {
                            return data;
                        }
                    }
                },
                {
                    data: "params",
                    title: this.$t(
                        "brandname.report.detailed.datatable.column.phone"
                    ),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            var obj = [];
                            obj = JSON.parse(data).phone;
                            var html = "";
                            obj.forEach(function(value) {
                                if (html === "") {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value) +
                                        "</li>";
                                } else {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value) +
                                        "</li>";
                                }
                            });

                            return html;
                        }
                    }
                },
                {
                    data: "params",
                    title: this.$t(
                        "brandname.report.detailed.datatable.column.from"
                    ),
                    orderable: false,
                    render(data) {
                        if (data != null)
                            return moment(JSON.parse(data).from).format(
                                "YYYY-MM-DD hh:mm:ss"
                            );
                    }
                },
                {
                    data: "params",
                    title: this.$t(
                        "brandname.report.detailed.datatable.column.to"
                    ),
                    orderable: false,
                    render(data) {
                        if (data != null)
                            return moment(JSON.parse(data).to).format(
                                "YYYY-MM-DD hh:mm:ss"
                            );
                    }
                },
                {
                    data: "status",
                    title: this.$t("datatable.column.status"),
                    orderable: false,
                    render(data) {
                        switch (data) {
                            case 0:
                                return "<span class='text-warning'>Chưa xử lý</span>";
                            case 1:
                                return "<span class='text-dark'>Đang xử lý</span>";
                            case 2:
                                return "<span class='text-success'>Đã xử lý</span>";
                            case 3:
                                return "<span class='text-danger'>Lỗi</span>";
                            default:
                                break;
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
                        switch (data.status) {
                            case 0:
                                return htmlEscapeEntities("Chờ đợi");
                            case 1:
                                return htmlEscapeEntities("Chờ đợi");
                            case 3:
                                return htmlEscapeEntities("-");
                            case 2:
                                // return generateTableAction(
                                //     "download",
                                //     "handleDownload"
                                // );
                                return (
                                    '<a title="Download" href="' +
                                    "/brandname/report/detailed" +
                                    data.file_path +
                                    '"><span class="la la-download"></span></a>'
                                );
                            default:
                                break;
                        }
                    }
                }
            ];
        }
    },
    mounted() {},
    methods: {
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        addRequest() {
            this.$refs.addModal.show();
        },
        async handleResend(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message: "Bạn chắc chắn muốn gửi lại yêu cầu ?",
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
                            "/brandname/report-detail-request/resend",
                            {
                                params: rowData.params
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notify(
                                $this.$t("label.notification"),
                                $this.$t("notification.resend_success"),
                                "success"
                            );
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        async handleDownload(table, rowData) {
            console.log(rowData.file_path);
            // return rowData.file_path;
            return downloadFile(rowData.file_path);
        },
        async search(value) {
            this.tableFilter = value;
            await this.$nextTick();
            this.$refs.table.reload();
        }
    }
};
</script>
