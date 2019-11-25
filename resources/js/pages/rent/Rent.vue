<template>
    <div>
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
                    :fixed-columns-right="1"
                    :actions="actions"
                    :search-placeholder="$t('rent.placeholder.search')"
                    :order-column-index="2"
                    :order-type="'desc'"
            >
            </data-table>
        </portlet>
        <rent-modal
                ref="addModal"
                :on-action-success="updateItemSuccess"
        ></rent-modal>
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
    import RentModal from "./partials/RentModal";

    export default {
        name: "Rent",
        components: {RentModal},
        middleware: "auth",
        data() {
            return {};
        },
        computed: {
            columns() {
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
                                } else {
                                    return `<span class='text-success'>Đã trả</span>`;
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
                        'Chuyển trạng thái mượn thiết bị ?',
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