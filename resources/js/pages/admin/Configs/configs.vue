<template>
    <div>
        <the-portlet :title="$t('admin.configs.portlet.title')">
            <v-button
                slot="tool"
                color="primary"
                style-type="air"
                class="m-btn m-btn--icon"
                @click.native="addConfigs"
            >
                <span>
                    <i class="la la-plus"></i>
                    <span>{{ $t("button.add") }}</span>
                </span>
            </v-button>
            <data-table
                ref="table"
                :columns="columns"
                url="/admin/configs/listing"
                :fixed-columns-left="3"
                :fixed-columns-right="1"
                :actions="actions"
                :search-placeholder="
                    $t('admin.configs.placeholder.search_placeholder')
                "
                :order-column-index="6"
                :order-type="'desc'"
            >
            </data-table
        ></the-portlet>
        <config-modal
            ref="modal"
            :on-action-success="updateItemSuccess"
        ></config-modal></div
></template>

<script>
import * as ROLE from "~/constants/roles";

import ThePortlet from "~/components/common/Portlet";
import { formatDate } from "~/helpers/formats";
import { generateTableAction } from "~/helpers/tableHelper";
import ConfigModal from "../Configs/partials/ConfigModal";

export default {
    components: { ConfigModal, ThePortlet },
    middleware: "auth",
    head() {
        return {
            title: this.$t("admin.configs.manage")
        };
    },
    meta: {
        title: "admin.configs.manage",
        roles: [ROLE.ROLE_ADMIN, ROLE.ROLE_ROOT]
    },
    data() {
        return {};
    },
    computed: {
        columns() {
            return [
                {
                    data: "group_name",
                    title: this.$t("admin.configs.datatable.column.group_name")
                },
                {
                    data: "name",
                    title: this.$t("admin.configs.datatable.column.name")
                },
                {
                    data: "value",
                    title: this.$t("admin.configs.datatable.column.value"),
                    orderable: false
                },
                {
                    data: "description",
                    title: this.$t(
                        "admin.configs.datatable.column.description"
                    ),
                    orderable: false
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
                        if (data != null) return formatDate(data);
                    }
                },
                {
                    title: this.$t("datatable.column.action"),
                    orderable: false,
                    class: "dt-center",
                    render() {
                        return generateTableAction("edit", "handleEdit");
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
                }
            ];
        }
    },
    methods: {
        updateItemSuccess() {
            this.$refs.table.reload();
        },
        addConfigs() {
            this.$refs.modal.show();
        },
        handleEdit(table, rowData) {
            this.$refs.modal.show(rowData);
        }
    }
};
</script>
