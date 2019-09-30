<template>
    <div>
        <portlet :title="$t('admin.manager.title')">
            <v-button
                slot="tool"
                color="primary"
                style-type="air"
                class="m-btn
                m-btn--icon"
                @click.native="addManager"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                ref="table"
                :columns="columns"
                url="/admin/manager/listing"
                :fixed-columns-left="1"
                :fixed-columns-right="2"
                :actions="actions"
                :search-placeholder="$t('admin.manager.placeholder.search')"
                :order-column-index="4"
                :order-type="'desc'"
            >
            </data-table
        ></portlet>
        <manager-modal
            ref="addModal"
            :on-action-success="updateItemSuccess"
        ></manager-modal>
    </div>
</template>

<script>
import bootbox from "bootbox";
import axios from "axios";
import { formatDate } from "~/helpers/formats";
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
import i18n from "~/plugins/i18n";
import ManagerModal from "./partials/ManagerModal";
import Portlet from "../../../components/common/Portlet";
export default {
    name: "Managers",
    components: { Portlet, ManagerModal },
    middleware: "auth",
    data() {
        return {};
    },
    computed: {
        columns() {
            return [
                {
                    data: "name",
                    title: this.$t("admin.manager.name")
                },
                {
                    data: "description",
                    title: this.$t("admin.manager.description"),
                    orderable: false,
                    className: "wrap-text"
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
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "who_created",
                    title: this.$t("datatable.column.who_created"),
                    orderable: false
                },
                {
                    data: "created_at",
                    title: this.$t("datatable.column.when_created"),
                    orderable: false,
                    render(data) {
                        if (data != null) {
                            return data;
                        } else return "-";
                    }
                },
                {
                    data: "active",
                    title: this.$t("datatable.column.status"),
                    render(data) {
                        if (data != null) {
                            if (data == 1) {
                                return `<span class='text-success'>Kích hoạt</span>`;
                            } else {
                                return `<span class='text-danger'>Vô hiệu</span>`;
                            }
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: "active",
                    title: this.$t("common.action"),
                    orderable: false,
                    className: "text-center",
                    responsivePriority: 1,
                    render(data) {
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
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        addManager() {
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
                        let res = await axios.post("/admin/manager/active", {
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
                        let res = await axios.post("/admin/manager/disable", {
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
        }
    }
};
</script>

<style scoped></style>
