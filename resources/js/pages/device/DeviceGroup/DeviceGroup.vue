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
                                <device-group-filter
                                        :is-required-to-export="
                                            isRequiredToExport
                                        "
                                        @search="search"
                                        @isExportFileSuccessfully="
                                            isExportFileSuccessfully
                                        "
                                ></device-group-filter>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <portlet :title="$t('device.device_group.title')">
                <v-button
                        slot="tool"
                        color="success"
                        style-type="air"
                        class="m-btn m-btn--icon"
                        @click.native="importAlias"
                        style="margin-right: 5px"
                >
                        <span>
                            <i class="la la-cloud-upload"></i>
                            <span>{{ $t("button.import") }}</span>
                        </span>
                </v-button>
                <v-button
                        slot="tool"
                        color="primary"
                        style-type="air"
                        class="m-btn m-btn--icon"
                        @click.native="addDeviceGroup"
                >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
                </v-button>
                <data-table
                        ref="table"
                        :columns="columns"
                        url="/device/device-group/listing"
                        :fixed-columns-left="1"
                        :fixed-columns-right="1"
                        :actions="actions"
                        :search-placeholder="$t('device.device_group.placeholder.search')"
                        :order-column-index="1"
                        :order-type="'desc'"
                        :post-data="tableFilter"
                >
                </data-table>
            </portlet>
            <device-group-modal
                    ref="addModal"
                    :on-action-success="updateItemSuccess"
            ></device-group-modal>
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
    import Portlet from "../../../components/common/Portlet";
    import DeviceGroupModal from "./partials/DeviceGroupModal";
    import DeviceGroupFilter from "./partials/DeviceGroupFilter";

    export default {
        name: "DeviceGroup",
        components: {DeviceGroupFilter, DeviceGroupModal, Portlet},
        middleware: "auth",
        data() {
            return {
                tableFilter: {
                    provider: null,
                    status: null
                },
                exportLoading: false,
                isRequiredToExport: false
            };
        },
        computed: {
            columns() {
                return [
                    {
                        data: "name",
                        title: this.$t("device.device_group.name")
                    },
                    {
                        data: "display_name",
                        title: this.$t("device.device_group.display_name")
                    },
                    {
                        data: "provider.name",
                        title: this.$t("device.device_group.provider")
                    },
                    {
                        data: "description",
                        title: this.$t("device.device_group.description"),
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
            addDeviceGroup() {
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
                            let res = await axios.post("/device/device-group/active", {
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
                            let res = await axios.post("/device/device-group/disable", {
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
            importAlias() {
                // this.$refs.importModal.show();
            },
        }
    }
</script>

<style scoped>

</style>