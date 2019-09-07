<template>
    <div>
        <labeled-filter @search="search"></labeled-filter>

        <portlet :title="$t('spam.spam_patterns.labeled.portlet_title')">
            <data-table
                v-if="showDataTable"
                ref="table"
                url="/spam/spam-patterns-labeled/listing"
                :columns="columns"
                :selectable="true"
                :actions="actions"
                :order-column-index="2"
                :search-placeholder="
                    $t('spam.spam_patterns.labeled.search_placeholder')
                "
                :post-data="tableFilter"
                :fixed-columns-left="2"
                :fixed-columns-right="1"
            >
            </data-table>

            <v-button
                slot="tool"
                color="accent"
                style-type="air"
                class="m-btn m-btn--icon m--margin-right-10 btn btn-success"
                @click.native="tagLabelIsSelected"
            >
                <i class="la la-tag"></i> {{ $t("button.tag") }}
            </v-button>

            <v-button
                slot="tool"
                color="accent"
                style-type="air"
                class="m-btn m-btn--icon m--margin-right-10 btn btn-danger"
                @click.native="ignoreLabelIsSelected"
            >
                <i class="la la-times-circle-o"></i>
                {{ $t("button.ignore") }}
            </v-button>

            <v-button
                slot="tool"
                color="accent"
                style-type="air"
                class="m-btn m-btn--icon m--margin-right-10 btn btn-primary"
                @click.native="addLabel"
            >
                <i class="la la-plus"></i> {{ $t("button.add") }}
            </v-button>
        </portlet>
        <labeled-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></labeled-modal>
    </div>
</template>

<style scoped></style>

<script>
// import UserChosen from "~/components/elements/chosen/UserChosen";
import { toNormalDate } from "~/helpers/formats";
import { deleteVietnameseAccent } from "~/helpers/deleteVietnameseAccent";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import { downloadFile } from "~/helpers/downloadFile";
import i18n from "~/plugins/i18n";
import bootbox from "bootbox";
import {
    notify,
    notifyIgnoreSuccess,
    notifyTryAgain
} from "~/helpers/bootstrap-notify";
import axios from "axios";

import LabeledModal from "./partials/LabeledTagModal.vue";
import LabeledFilter from "./partials/LabeledFilter";

let source = [];
let spamLabels = [];

export default {
    middleware: "auth",
    components: { LabeledModal, LabeledFilter },
    head() {
        return {
            title: i18n.t("menu.spam.spam_patterns_labeled")
        };
    },
    meta: {
        title: "menu.spam.spam_patterns_labeled"
    },
    data: () => {
        return {
            tableFilter: {},
            isExporting: false,
            sources: [],
            sourcesFilter: [],
            spamLabels: [],
            spamLabelsFilter: [],

            showDataTable: false
        };
    },
    computed: {
        actions() {
            return [
                {
                    type: "click",
                    name: "TagLabel",
                    action: this.tagLabel
                },
                {
                    type: "click",
                    name: "addLabel",
                    action: this.addLabel
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
                    title: i18n.t("spam.spam_patterns.labeled.content"),
                    className: "wrap-text"
                },
                {
                    data: "application_name",
                    title: i18n.t(
                        "spam.spam_patterns.labeled.spam_application"
                    ),
                    orderable: false,
                    render: data => {
                        if (data) {
                            return this.findSource(data);
                        }
                    }
                },
                {
                    data: "label_name",
                    title: i18n.t("spam.spam_patterns.labeled.spam_label"),
                    orderable: false,
                    render: data => {
                        if (data) {
                            return this.findLabel(data);
                        }
                    }
                },
                {
                    data: "who_updated",
                    title: i18n.t("common.who_updated")
                },
                {
                    data: "when_updated",
                    title: i18n.t("common.when_updated"),
                    render(data) {
                        if (data) {
                            return toNormalDate(data);
                        }
                    }
                },
                {
                    data: null,
                    title: i18n.t("spam.spam_patterns.labeled.action"),
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
    watch: {},
    async beforeCreate() {
        try {
            const sourcesResponse = await axios.post(
                "/spam/spam-patterns-labeled/list-all-source"
            );

            this.sources = sourcesResponse.data.results;
            this.sourcesFilter = sourcesResponse.data.results;
            source = sourcesResponse.data.results;
        } catch (e) {
            console.log(e);
        }

        try {
            const spamLabelResponse = await axios.post(
                "/spam/spam-patterns-labeled/list-all"
            );
            const optionList = [];

            spamLabelResponse.data.results.groupLabel.forEach(element => {
                let optionListchil = null;
                optionListchil = [];

                optionListchil.label = element.display_name;
                optionListchil.options = [];
                spamLabelResponse.data.results.finalResult.forEach(e => {
                    if (e.parent === element.name) {
                        optionListchil.options.push(e);
                    }
                });
                optionListchil.options.unshift(element);
                optionList.push(optionListchil);
            });
            this.spamLabels = optionList;
            this.spamLabelsFilter = optionList;
            spamLabels = optionList;
        } catch (e) {
            console.log(e);
        }

        this.showDataTable = true;
    },
    mounted() {},
    methods: {
        tagLabel(table, rowData) {
            this.$refs.modal.show([rowData]);
        },
        addLabel() {
            this.$refs.modal.show();
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
                notify(
                    i18n.t("label.notification"),
                    i18n.t(
                        "spam.spam_patterns.labeled.must_select_at_least_one_record"
                    ),
                    "warning",
                    "icon la la-exclamation-triangle"
                );
            }
        },
        ignoreLabelIsSelected() {
            const rows = this.$refs.table.getSelectedRows();
            const ids = Array.from(rows, e => {
                return {
                    id: e.id
                };
            });
            if (ids.length > 0) {
                // MessageBox.confirm(
                //     i18n.t("spam.spam_patterns.labeled.ignore_confirm"),
                //     i18n.t("label.notification"),
                //     {
                //         confirmButtonText: i18n.t("button.confirm"),
                //         cancelButtonText: i18n.t("button.cancel"),
                //         type: "warning"
                //     }
                // )
                //     .then(async () => {
                //         const res = await this.$axios.post(
                //             "/spam/spam-patterns-labeled/ignore",
                //             {
                //                 ids: ids
                //             }
                //         );
                //         const { data } = res;
                //
                //         if (data.code === 0) {
                //             this.$bootstrapNotify({
                //                 title: i18n.t("label.notification"),
                //                 message: i18n.t(
                //                     "spam.spam_patterns.labeled.notification_ignore_success"
                //                 ),
                //                 type: "success"
                //             });
                //             this.updateItemSuccess();
                //         } else {
                //             this.$bootstrapNotify({
                //                 title: i18n.t("label.notification"),
                //                 message: i18n.t(
                //                     "spam.spam_patterns.labeled.notification_ignore_error"
                //                 ),
                //                 type: "danger"
                //             });
                //         }
                //     })
                //     .catch(() => {});
                let $this = this;

                bootbox.confirm({
                    title: this.$t("alert.notice"),
                    message: `${this.$t("alert.ignore.ignore_confirm")}? <br/>`,
                    buttons: {
                        cancel: {
                            label: this.$t("button.cancel")
                        },
                        confirm: {
                            label: this.$t("button.ok")
                        }
                    },
                    callback: async function(result) {
                        if (result) {
                            let res = await axios.post(
                                "/spam/spam-patterns-labeled/ignore",
                                { ids: ids }
                            );
                            const { data } = res;

                            if (data.code == 0) {
                                notifyIgnoreSuccess();
                                reloadIntelligently($this.$refs.table);
                            } else {
                                notifyTryAgain();
                            }
                        }
                    }
                });
            } else {
                notify(
                    i18n.t("label.notification"),
                    i18n.t(
                        "spam.spam_patterns.labeled.must_select_at_least_one_record"
                    ),
                    "warning",
                    "icon la la-exclamation-triangle"
                );
            }
        },
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        async search(value) {
            this.tableFilter = value;
            await this.$nextTick();
            this.updateItemSuccess();
        },
        IgnoreLabel(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("alert.notice"),
                message: `${this.$t(
                    "alert.ignore.ignore_confirm"
                )}? <br/> <span class="text-danger">"${htmlEscapeEntities(
                    rowData.content
                )}"</span>`,
                buttons: {
                    cancel: {
                        label: this.$t("button.cancel")
                    },
                    confirm: {
                        label: this.$t("button.ok")
                    }
                },
                callback: async function(result) {
                    if (result) {
                        let res = await axios.post(
                            "/spam/spam-patterns-labeled/ignore",
                            { ids: [rowData.id] }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyIgnoreSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notifyTryAgain();
                        }
                    }
                }
            });
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
        },
        filterLabelOptions(inputValue) {
            let filterResult = null;
            filterResult = [];

            this.spamLabels.forEach(e => {
                let filterResultChild = null;
                filterResultChild = [];
                filterResultChild.label = e.label;

                filterResultChild.options = e.options.filter(option => {
                    return (
                        deleteVietnameseAccent(
                            option.display_name.toLocaleLowerCase()
                        ).indexOf(
                            deleteVietnameseAccent(
                                inputValue.toLocaleLowerCase()
                            )
                        ) > -1
                    );
                });
                if (filterResultChild.options.length > 0) {
                    filterResult.push(filterResultChild);
                }
            });

            this.spamLabelsFilter = filterResult;
            return true;
        },
        findSource(nameSource) {
            let displayNameSource = null;
            displayNameSource = "";
            source.forEach(e => {
                if (nameSource === e.name) {
                    displayNameSource = e.display_name;
                }
            });
            return displayNameSource;
        },
        findLabel(nameLabel) {
            let displayNameLabel = null;
            displayNameLabel = "";
            spamLabels.forEach(e => {
                e.options.forEach(elementChild => {
                    if (nameLabel === elementChild.name) {
                        displayNameLabel = elementChild.display_name;
                    }
                });
            });
            if (nameLabel == "ignore") {
                return "Bỏ qua";
            }
            return displayNameLabel;
        }
    }
};
</script>
