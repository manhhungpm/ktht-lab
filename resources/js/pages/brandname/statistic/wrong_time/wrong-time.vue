<template>
    <div>
        <the-portlet :title="$t('brandname.report.wrong_time.list_title')">
            <data-table
                ref="table"
                :columns="columns"
                url="/brandname/report-wrong-time/listing"
                :fixed-columns-left="2"
                :fixed-columns-right="1"
                :searching="false"
                :order-column-index="1"
                :order-type="'desc'"
            >
            </data-table
        ></the-portlet></div
></template>

<script>
import ThePortlet from "~/components/common/Portlet";
import * as ROLE from "~/constants/roles";
import { toNormalDate } from "../../../../helpers/formats";

export default {
    name: "WrongTimeVue",
    components: { ThePortlet },
    middleware: "auth",
    head() {
        return {
            title: this.$t("brandname.report.wrong_time.title")
        };
    },
    meta: {
        title: "brandname.report.wrong_time.title",
        roles: [ROLE.ROLE_ADMIN, ROLE.ROLE_ROOT]
    },
    computed: {
        columns() {
            return [
                {
                    data: "date",
                    title: this.$t(
                        "brandname.report.wrong_time.datatable.column.date"
                    ),
                    render(data) {
                        if (data != null) {
                            return toNormalDate(data);
                        }
                        return "-";
                    }
                },
                {
                    data: null,
                    title: this.$t("datatable.column.action"),
                    orderable: false,
                    class: "dt-center",
                    className: "text-center tb-actions",
                    responsivePriority: 1,
                    render(data) {
                        return (
                            '<a title="Download" href="' +
                            "/storage" +
                            data.file_path +
                            '"><span class="la la-download"></span></a>'
                        );
                    }
                }
            ];
        }
    }
};
</script>

<style scoped></style>
