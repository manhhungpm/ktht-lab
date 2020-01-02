<template>
    <div>
        <portlet :title="$t('device.provider.title')">
            <v-button
                    slot="tool"
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    @click.native="addProvider"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                    ref="table"
                    :columns="columns"
                    url="/device/provider/listing"
                    :fixed-columns-left="1"
                    :fixed-columns-right="1"
                    :actions="actions"
                    :search-placeholder="$t('device.provider.placeholder.search')"
                    :order-column-index="1"
                    :order-type="'desc'"
            >
            </data-table>
        </portlet>
        <provider-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
        ></provider-modal>
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
    import Portlet from "../../../components/common/Portlet";
    import ProviderModal from "./partials/ProviderModal";

    export default {
        name: "Provider",
        components: {ProviderModal, Portlet},
        middleware: "auth",
        data() {
            return {};
        },
        computed: {
            columns() {
                return [
                    {
                        data: "name",
                        title: this.$t("device.provider.name")
                    },
                    {
                        data: "address",
                        title: this.$t("device.provider.address")
                    },
                    {
                        data: "description",
                        title: this.$t("device.provider.description"),
                        orderable: false,
                        className: "wrap-text"
                    },
                    {
                        data: "status",
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
                        data: "status",
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
            addProvider() {
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
                        'Bạn chắc chắn muốn kích hoạt nhà cung cấp "<span class="text-danger">' +
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
                            let res = await axios.post("/device/provider/active", {
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
                        'Bạn chắc chắn muốn vô hiệu nhà cung cấp "<span class="text-danger">' +
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
                            let res = await axios.post("/device/provider/disable", {
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
            },
        }
    }
</script>

<style scoped>

</style>
