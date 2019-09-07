<template>
    <div class="row">
        <div class="col-md-12">
            <!--<the-portlet :title="$t('label.search_infomation')">-->
            <!--<div class="row">-->
            <!--<el-form-item-->
            <!--class="col-3"-->
            <!--:label="$t('warning.enter_content_title')"-->
            <!--&gt;-->
            <!--<el-input-->
            <!--v-model="tableFilter.content"-->
            <!--:placeholder="$t('warning.enter_content')"-->
            <!--&gt;</el-input>-->
            <!--</el-form-item>-->
            <!--<el-form-item-->
            <!--class="col-3"-->
            <!--:label="$t('warning.select_spam_application')"-->
            <!--&gt;-->
            <!--<el-select-->
            <!--v-model="tableFilter.spam_application"-->
            <!--multiple-->
            <!--:placeholder="-->
            <!--$t(-->
            <!--'warning.select_spam_application_placeholder'-->
            <!--)-->
            <!--"-->
            <!--filterable-->
            <!--:filter-method="filterOptions"-->
            <!--&gt;-->
            <!--<el-option-->
            <!--v-for="item in sourcesFilter"-->
            <!--:key="item.name"-->
            <!--:label="item.display_name"-->
            <!--:value="item.name"-->
            <!--&gt;-->
            <!--</el-option>-->
            <!--</el-select>-->
            <!--</el-form-item>-->

            <!--<el-form-item-->
            <!--class="col-6"-->
            <!--:label="$t('warning.when_created')"-->
            <!--&gt;-->
            <!--<el-date-picker-->
            <!--v-model="tableFilter.when_created"-->
            <!--class="date-range-picker-full-width"-->
            <!--type="daterange"-->
            <!--:range-separator="-->
            <!--$t('component.date_picker.range_separator')-->
            <!--"-->
            <!--:start-placeholder="-->
            <!--$t('component.date_picker.start_placeholder')-->
            <!--"-->
            <!--:end-placeholder="-->
            <!--$t('component.date_picker.end_placeholder')-->
            <!--"-->
            <!--value-format="dd-MM-yyyy"-->
            <!--&gt;-->
            <!--</el-date-picker>-->
            <!--</el-form-item>-->

            <!--<div class="col-md-12 d-flex justify-content-center">-->
            <!--<button-->
            <!--type="button"-->
            <!--class="btn btn-info mr-3"-->
            <!--@click="search"-->
            <!--&gt;-->
            <!--<i class="la la-search"></i>-->
            <!--{{ $t("button.search") }}-->
            <!--</button>-->
            <!--<el-button-->
            <!--type="button"-->
            <!--class="btn btn-success"-->
            <!--:loading="exportLoading"-->
            <!--@click="exportFile"-->
            <!--&gt;-->
            <!--<i class="la la-cloud-download"></i>-->
            <!--{{ $t("button.export") }}-->
            <!--</el-button>-->
            <!--</div>-->
            <!--</div>-->
            <!--</the-portlet>-->
        </div>
        <div class="col-md-12">
            <the-portlet :title="$t('warning.portlet_title')">
                <v-button
                    color="success"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-bottom: 18px"
                    @click.native="tagLabelIsSelected"
                >
                    <span>
                        <i class="la la-tag"></i>
                        <span>{{ $t("button.tag") }}</span>
                    </span>
                </v-button>

                <v-button
                    color="danger"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-bottom: 18px"
                    @click.native="ignoreLabelIsSelected"
                >
                    <span>
                        <i class="la la-times-circle-o"></i>
                        <span>{{ $t("button.ignore") }}</span>
                    </span>
                </v-button>

                <data-table
                    ref="table"
                    url="/spam/spam-patterns-warning/listing"
                    :columns="columns"
                    :selectable="true"
                    :actions="actions"
                    :order-column-index="8"
                    :search-placeholder="$t('warning.search_placeholder')"
                    :post-data="tableFilter"
                    :fixed-columns-left="2"
                >
                </data-table>
            </the-portlet>
        </div>
        <warning-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></warning-modal>
    </div>
</template>

<script>
import { MessageBox } from "element-ui";
import $ from "jquery";
import ThePortlet from "~/components/common/Portlet";
import { toNormalDate, formatNumber } from "~/helpers/formats";
import { deleteVietnameseAccent } from "~/helpers/deleteVietnameseAccent";
import { downloadFile } from "~/helpers/downloadFile";
import bootbox from "bootbox";
import axios from "axios";
import WarningModal from "../warning/partials/WarningModal";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import {
    notify,
    notifyTryAgain,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";

export default {
    components: { ThePortlet, WarningModal },
    head() {
        return {
            title: this.$t("menu.spam.spam_patterns_warning")
        };
    },
    meta: {
        title: "menu.spam.spam_patterns_warning"
        // roles:['1','1']
    },
    data: () => {
        return {
            tableFilter: {
                content: null,
                spam_application: null,
                when_created: null
            },
            exportLoading: false,
            sources: [],
            sourcesFilter: []
        };
    },
    computed: {
        actions() {
            return [
                {
                    type: "click",
                    name: "TagLabel",
                    action: this.showDetail
                },
                {
                    type: "click",
                    name: "IgnoreLabel",
                    action: this.IgnoreLabel
                }
            ];
        },
        columns() {
            return [
                {
                    data: "content",
                    title: this.$t("warning.content")
                    // className: 'wrap-text'
                },
                {
                    data: "count_content",
                    title: this.$t("warning.count_content"),
                    className: "text-right",
                    render(data) {
                        return formatNumber(data);
                    }
                },
                {
                    data: "count_spam",
                    title: this.$t("warning.count_spam"),
                    className: "text-right",
                    render(data) {
                        return formatNumber(data);
                    }
                },
                {
                    data: "count_calling",
                    title: this.$t("warning.count_calling"),
                    className: "text-right",
                    render(data) {
                        return formatNumber(data);
                    }
                },
                {
                    data: "count_called",
                    title: this.$t("warning.count_called"),
                    className: "text-right",
                    render(data) {
                        return formatNumber(data);
                    }
                },
                {
                    data: "spam_application.display_name",
                    title: this.$t("warning.spam_application"),
                    orderable: false,
                    render(data) {
                        if (data) {
                            return data;
                        }
                    }
                },
                {
                    data: "when_created",
                    title: this.$t("warning.when_created"),
                    render(data) {
                        if (data) {
                            return toNormalDate(data);
                        }
                    }
                },
                {
                    data: null,
                    title: this.$t("warning.action"),
                    orderable: false,
                    className: "text-center tb-actions",
                    responsivePriority: 1,
                    render() {
                        return (
                            generateTableAction("tag", "TagLabel") +
                            generateTableAction("ignore", "IgnoreLabel")
                        );
                    }
                }
            ];
        }
    },
    watch: {
        "tableFilter.content"() {
            this.updateItemSuccess();
        },
        "tableFilter.spam_application"() {
            this.updateItemSuccess();
        },
        "tableFilter.when_created"() {
            this.updateItemSuccess();
        }
    },
    mounted() {
        this.getAllSource();
    },
    methods: {
        showDetail(table, rowData) {
            this.$refs.modal.show([rowData]);
        },
        tagLabelIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const rowDatas = Array.from(rows, e => {
                return {
                    id: e.id,
                    content: e.content
                };
            });

            if (rowDatas.length > 0) {
                this.$refs.modal.show(rowDatas);
            } else {
                this.$bootstrapNotify({
                    title: this.$t("label.notification"),
                    message: this.$t("warning.must_select_at_least_one_record"),
                    type: "danger"
                });
            }
            $(".el-button--button").trigger("blur");
        },
        ignoreLabelIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const ids = Array.from(rows, e => {
                return {
                    id: e.id
                };
            });
            if (ids.length > 0) {
                MessageBox.confirm(
                    this.$t("warning.ignore_confirm"),
                    this.$t("label.notification"),
                    {
                        confirmButtonText: this.$t("button.confirm"),
                        cancelButtonText: this.$t("button.cancel"),
                        type: "warning"
                    }
                )
                    .then(async () => {
                        const res = await axios.post(
                            "/spam/spam-patterns-warning/ignore",
                            {
                                ids: ids
                            }
                        );
                        const { data } = res;

                        if (data.code === 0) {
                            this.$bootstrapNotify({
                                title: this.$t("label.notification"),
                                message: this.$t(
                                    "warning.notification_ignore_success"
                                ),
                                type: "success"
                            });
                            this.updateItemSuccess();
                        } else {
                            this.$bootstrapNotify({
                                title: this.$t("label.notification"),
                                message: this.$t(
                                    "warning.notification_ignore_error"
                                ),
                                type: "danger"
                            });
                        }
                    })
                    .catch(() => {});
            } else {
                this.$bootstrapNotify({
                    title: this.$t("label.notification"),
                    message: this.$t("warning.must_select_at_least_one_record"),
                    type: "danger"
                });
            }
            $(".el-button--button").trigger("blur");
        },
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        async search() {
            await this.$nextTick();
            this.updateItemSuccess();
        },
        IgnoreLabel(table, rowData) {
            // MessageBox.confirm(
            //     this.$t("warning.ignore_confirm"),
            //     this.$t("label.notification"),
            //     {
            //         confirmButtonText: this.$t("button.confirm"),
            //         cancelButtonText: this.$t("button.cancel"),
            //         type: "warning"
            //     }
            // )
            //     .then(async () => {
            //         const res = await axios.post(
            //             "/spam/spam-patterns-warning/ignore",
            //             {
            //                 ids: [rowData.id]
            //             }
            //         );
            //         const { data } = res;
            //
            //         if (data.code === 0) {
            //             this.$bootstrapNotify({
            //                 title: this.$t("label.notification"),
            //                 message: this.$t(
            //                     "warning.notification_ignore_success"
            //                 ),
            //                 type: "success"
            //             });
            //             this.updateItemSuccess();
            //         } else {
            //             this.$bootstrapNotify({
            //                 title: this.$t("label.notification"),
            //                 message: this.$t(
            //                     "warning.notification_ignore_error"
            //                 ),
            //                 type: "danger"
            //             });
            //         }
            //     })
            //     .catch(() => {});

            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn bỏ qua "<span class="text-danger">' +
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
                        let res = await axios.post(
                            "/spam/spam-patterns-warning/ignore",
                            {
                                ids: [rowData.id]
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notify(
                                this.$t("label.notification"),
                                this.$t("warning.notification_ignore_success")
                            );
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
        },
        async exportFile() {
            this.exportLoading = true;
            const searchParams = this.tableFilter;

            const { data } = await axios.post(
                "/spam/spam-patterns-warning/listing",
                searchParams
            );

            if (data.recordsTotal) {
                try {
                    const res = await axios.post(
                        "/spam/spam-patterns-warning/export",
                        searchParams,
                        {
                            responseType: "blob"
                        }
                    );
                    downloadFile(res);
                    this.exportLoading = false;
                } catch (e) {
                    this.$bootstrapNotify({
                        title: this.$t("label.notification"),
                        message: this.$t(
                            "spam.spam_patterns.noti.export_error"
                        ),
                        type: "danger"
                    });
                    this.exportLoading = false;
                }
            } else {
                this.$bootstrapNotify({
                    title: this.$t("label.notification"),
                    message: this.$t("spam.spam_patterns.noti.no_record"),
                    type: "danger"
                });
                this.exportLoading = false;
            }
        },
        async getAllSource() {
            try {
                const SourceReponse = await axios.post(
                    "/spam/spam-patterns-warning/list-all-source"
                );
                this.sources = SourceReponse.data.results;
                this.sourcesFilter = SourceReponse.data.results;
            } catch (e) {
                console.log(e);
            }
        },
        filterOptions(inputValue) {
            this.sourcesFilter = this.sources.filter(option => {
                return (
                    deleteVietnameseAccent(
                        option.display_name.toLocaleLowerCase()
                    ).indexOf(
                        deleteVietnameseAccent(inputValue.toLocaleLowerCase())
                    ) > -1
                );
            });
            return true;
        }
    }
};
</script>
