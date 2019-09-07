<template>
    <div class="row">
        <div class="col-md-12">
            <the-portlet :title="$t('admin.smstopics.chart')"> </the-portlet>
        </div>
        <div class="col-md-12">
            <the-portlet :title="$t('admin.smstopics.portlet.title')">
                <v-button
                    color="success"
                    style-type="air"
                    class="m-btn--custom m-btn--icon"
                    style="margin-bottom: 18px"
                >
                    <span>
                        <i class="la la-refresh"></i>
                        <span>{{ $t("button.restart") }}</span>
                    </span>
                </v-button>
                <v-button
                    color="primary"
                    style-type="air"
                    class="m-btn m-btn--icon"
                    style="margin-bottom: 18px"
                >
                    <span>
                        <i class="la la-upload"></i>
                        <span>{{ $t("button.update_code") }}</span>
                    </span>
                </v-button>
                <data-table
                    :columns="columns"
                    url="/admin/smstopics/listing"
                    :fixed-columns-left="2"
                    :fixed-columns-right="2"
                ></data-table>
            </the-portlet>
        </div></div
></template>

<script>
import * as ROLE from "../../../constants/roles";

import ThePortlet from "../../../components/common/Portlet";
import DataTable from "../../../components/common/DataTable";
import { generateTableAction } from "../../../helpers/tableHelper";
import { formatDate } from "../../../helpers/formats";

export default {
    components: { ThePortlet, DataTable },
    middleware: "auth",
    head() {
        return {
            title: this.$t("admin.smstopics.manage")
        };
    },
    meta: {
        title: "admin.smstopics.manage",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN]
    },
    data() {
        return {
            columns1: [
                {
                    data: "name",
                    title: "Name",
                    orderable: false,
                    width: "50px"
                },
                {
                    data: "ip",
                    title: "IP",
                    orderable: false,
                    width: "40px"
                },
                {
                    data: "master_id",
                    title: "Master PID",
                    orderable: false,
                    width: "10px"
                },
                {
                    data: "slave_id",
                    title: "Slave PID",
                    orderable: false,
                    width: "10px"
                },
                {
                    data: "",
                    title: "Lastest Event",
                    orderable: false,
                    width: "60px"
                },
                {
                    data: null,
                    title: this.$t("datatable.column.action"),
                    orderable: false,
                    width: "100px"
                }
            ]
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "name",
                    title: "Name",
                    orderable: false
                },
                {
                    data: "ip",
                    title: "IP",
                    orderable: false
                },
                {
                    data: "master_id",
                    title: "Master PID",
                    orderable: false
                },
                {
                    data: "slave_id",
                    title: "Slave PID",
                    orderable: false
                },
                {
                    data: "force_staff_event",
                    title: "Lastest Event",
                    orderable: false,
                    render(data) {
                        const last = data.length;
                        return (
                            data[last - 1].event +
                            " at " +
                            formatDate(data[last - 1].when_created)
                        );
                    }
                },
                {
                    data: null,
                    title: "Hành động",
                    orderable: false,
                    responsivePriority: 1,
                    className: "dt-center",
                    render() {
                        return generateTableAction("start", "handleStop");
                    }
                }
            ];
        }
    },
    methods: {}
};
</script>
