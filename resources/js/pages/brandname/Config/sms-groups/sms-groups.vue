<template
    ><div>
        <the-portlet :title="$t('brandname.sms_groups.datatable.title')">
            <v-button
                slot="tool"
                color="primary"
                style-type="air"
                class="m-btn m-btn--icon"
                @click.native="addSmsGroups"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                ref="table"
                :columns="columns"
                url="/brandname/sms-groups/listing"
                :fixed-columns-left="2"
                :fixed-columns-right="2"
                :actions="actions"
                :search-placeholder="
                    $t('brandname.sms_groups.placeholder.search_placeholder')
                "
                :order-column-index="4"
                :order-type="'desc'"
            >
            </data-table
        ></the-portlet>
        <sms-groups-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></sms-groups-modal>
    </div>
</template>

<script>
import * as ROLE from "~/constants/roles";
import bootbox from "bootbox";
import axios from "axios";
import ThePortlet from "~/components/common/Portlet";
import { formatDate } from "~/helpers/formats";
import {
    generateTableAction,
    htmlEscapeEntities,
    reloadIntelligently
} from "~/helpers/tableHelper";
import {
    notifyTryAgain,
    notifyDeleteSuccess,
    notify,
    notifyActiveSuccess,
    notifyDisableSuccess
} from "~/helpers/bootstrap-notify";
import SmsGroupsModal from "./partials/SmsGroupsModal";

export default {
    name: "SmsGroups",
    components: { SmsGroupsModal, ThePortlet },
    middleware: "auth",
    head() {
        return {
            title: this.$t("brandname.sms_groups.title")
        };
    },
    meta: {
        title: "brandname.sms_groups.title",
        roles: [ROLE.ROLE_ADMIN, ROLE.ROLE_ROOT]
    },
    data() {
        return {};
    },
    computed: {
        columns() {
            return [
                {
                    data: "name",
                    title: this.$t(
                        "brandname.sms_groups.datatable.column.name"
                    ),
                    className: "wrap-text"
                },
                {
                    data: "description",
                    title: this.$t(
                        "brandname.sms_groups.datatable.column.description"
                    ),
                    orderable: false,
                    className: "wrap-text"
                },
                {
                    data: "who_updated",
                    title: this.$t("datatable.column.who_updated"),
                    orderable: false
                },
                {
                    data: "when_updated",
                    title: this.$t("datatable.column.when_updated"),
                    render(data) {
                        if (data != null) return data;
                    }
                },
                {
                    data: "who_created",
                    title: this.$t("datatable.column.who_created"),
                    orderable: false
                },
                {
                    data: "when_created",
                    title: this.$t("datatable.column.when_created"),
                    orderable: false,
                    render(data) {
                        if (data != null) return data;
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
                        if (data) {
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
        addSmsGroups() {
            this.$refs.modal.show();
        },
        handleEdit(table, rowData) {
            this.$refs.modal.show(rowData);
        },
        async handleDelete(table, rowData) {
            let $this = this;

            bootbox.confirm({
                title: this.$t("label.notification"),
                message:
                    'Bạn chắc chắn muốn xóa "<span class="text-danger">' +
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
                        let res = await axios.post(
                            "/brandname/sms-groups/delete",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDeleteSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else {
                            notify(
                                $this.$t("label.notification"),
                                $this.$t("notification.no_delete")
                            );
                        }
                    }
                }
            });
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
                        let res = await axios.post(
                            "/brandname/sms-groups/active",
                            {
                                id: rowData.id
                            }
                        );
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
                        let res = await axios.post(
                            "/brandname/sms-groups/disable",
                            {
                                id: rowData.id
                            }
                        );
                        const { data } = res;

                        if (data.code == 0) {
                            notifyDisableSuccess();
                            reloadIntelligently($this.$refs.table);
                        } else if (data.code == 4) {
                            notify(
                                $this.$t("label.notification"),
                                $this.$t(
                                    "notification.no_disable_because_sms_type_active"
                                )
                            );
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
