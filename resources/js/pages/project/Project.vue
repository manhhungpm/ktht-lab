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
                                <project-filter
                                    :is-required-to-export="
                                            isRequiredToExport
                                        "
                                    @search="search"
                                    @isExportFileSuccessfully="
                                            isExportFileSuccessfully
                                        "
                                ></project-filter>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <portlet :title="$t('project.title')">
                <v-button
                    slot="tool"
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="addProject"
                    v-if="roleAdmin === true || roleLeader === true"
                >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
                </v-button>
                <data-table
                    ref="table"
                    :columns="columns"
                    url="/project/listing"
                    :fixed-columns-left="2"
                    :fixed-columns-right="1"
                    :actions="actions"
                    :search-placeholder="$t('project.placeholder.search')"
                    :order-column-index="1"
                    :order-type="'desc'"
                    :post-data="tableFilter"
                >
                </data-table>
            </portlet>
            <project-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
            ></project-modal>
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
        notifyDisableSuccess
    } from "~/helpers/bootstrap-notify";
    import ProjectModal from "./partials/ProjectModal";
    import ProjectFilter from "./partials/ProjectFilter";

    export default {
        name: "Project",
        components: {ProjectFilter, ProjectModal},
        middleware: "auth",
        data() {
            return {
                tableFilter: {
                    user: null,
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
            columns() {
                let $this = this;
                return [
                    {
                        data: "name",
                        title: this.$t("project.name")
                    },
                    {
                        data: "description",
                        title: this.$t("project.description"),
                        orderable: false,
                        className: "wrap-text"
                    },
                    {
                        data: "user",
                        title: "Tài khoản thành viên",
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
                        data: "user",
                        title: this.$t("project.user"),
                        render(data) {
                            let html = "";
                            data.map(function (value) {
                                if (html === "") {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value.display_name) +
                                        "</li>";
                                } else {
                                    html +=
                                        "<li>" +
                                        htmlEscapeEntities(value.display_name) +
                                        "</li>";
                                }
                            });
                            return html;
                        }
                    },
                    {
                        data: "device_type",
                        title: this.$t("project.device_type"),
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
                        data: "status",
                        title: this.$t("common.status"),
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
                        data: "status",
                        title: this.$t("common.action"),
                        orderable: false,
                        className: "text-center",
                        responsivePriority: 1,
                        render(data) {
                            if ($this.roleAdmin) {
                                if (data == 1) {
                                    return generateTableAction(
                                        "disable",
                                        "handleDisable"
                                    );
                                } else {
                                    return (
                                        generateTableAction("edit", "handleEdit") +
                                        generateTableAction("active", "handleActive")
                                    );
                                }
                            } else {
                                return "Không có quyền thực hiện hành động"
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
                    }
                ];
            }
        },
        methods: {
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
            addProject() {
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
                        'Bạn chắc chắn muốn kích hoạt khoa "<span class="text-danger">' +
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
                    callback: async function (result) {
                        if (result) {
                            let res = await axios.post("/project/active", {
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
            async handleDisable(table, rowData) {
                let $this = this;

                bootbox.confirm({
                    title: this.$t("label.notification"),
                    message:
                        'Bạn chắc chắn muốn vô hiệu khoa "<span class="text-danger">' +
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
                    callback: async function (result) {
                        if (result) {
                            let res = await axios.post("/project/disable", {
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
