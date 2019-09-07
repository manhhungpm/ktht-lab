<template>
    <div>
        <the-portlet :title="$t('brandname.report.outlier_detail.list_title')">
            <data-table
                ref="table"
                :columns="columns"
                url="/brandname/report-outlier-detail/listing"
                :fixed-columns-left="2"
                :fixed-columns-right="1"
                :order-column-index="1"
                :order-type="'desc'"
                :searching="false"
            >
            </data-table
        ></the-portlet>
    </div>
</template>

<script>
import ThePortlet from "~/components/common/Portlet";
import * as ROLE from "~/constants/roles";

export default {
    name: "OutlierDetail",
    components: { ThePortlet },
    middleware: "auth",
    head() {
        return {
            title: this.$t("brandname.report.outlier_detail.title")
        };
    },
    meta: {
        title: "brandname.report.outlier_detail.title",
        roles: [ROLE.ROLE_ADMIN, ROLE.ROLE_ROOT]
    },
    computed: {
        columns() {
            return [
                {
                    data: "date",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.date"
                    )
                },
                {
                    data: "vip_outlier",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.vip_outlier"
                    )
                },
                {
                    data: "pot_outlier",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.pot_outlier"
                    )
                },
                {
                    data: "nor_outlier",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.nor_outlier"
                    )
                },
                {
                    data: "vip_month_outlier",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.vip_month_outlier"
                    )
                },
                {
                    data: "pot_month_outlier",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.pot_month_outlier"
                    )
                },
                {
                    data: "nor_month_outlier",
                    title: this.$t(
                        "brandname.report.outlier_detail.datatable.column.nor_month_outlier"
                    )
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
