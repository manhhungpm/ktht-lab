<template>
    <div>
        <div class="row">
            <div class="col-12">
                <time-range-filter
                    :title="$t('label.search_information')"
                    :default-filter="defaultTime"
                    :exportable="false"
                    :export-url="'/brandname/report-day/alias/export'"
                    :rules-validate="''"
                    :name-shortcut="['last_7_days', 'last_30_days']"
                    :disabled-date="'greaterThanToday'"
                    @search="filterTime"
                >
                </time-range-filter>
            </div>
            <div class="col-12">
                <portlet title="Báo cáo chi tiết">
                    <data-table
                        :columns="columns"
                        url="/admin/user/listing"
                    ></data-table
                ></portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "../../components/common/Portlet";
import DataTable from "../../components/common/DataTable";
import moment from "moment";

export default {
    name: "SpamCallDetail",
    components: { DataTable, Portlet },
    data() {
        return {
            defaultTime: {
                time: [
                    moment()
                        .startOf("day")
                        .subtract(1, "days")
                        .format("YYYY-MM-DD HH:mm:ss"),
                    moment()
                        .startOf("day")
                        .format("YYYY-MM-DD HH:mm:ss")
                ]
            },
            tableFilter: {
                from: moment()
                    .startOf("day")
                    .subtract(1, "days")
                    .format("YYYY-MM-DD HH:mm:ss"),
                to: moment()
                    .startOf("day")
                    .format("YYYY-MM-DD HH:mm:ss")
            }
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "mobile_phone",
                    title: "Số điện thoại"
                },
                {
                    data: "active",
                    title: "Tổng số cuộc gọi ra"
                },
                {
                    data: "active",
                    title: "Tổng thời lượng cuộc gọi ra (phút)"
                },
                {
                    data: "active",
                    title: "Tổng số cuộc gọi được gán nhãn spam"
                },
                {
                    data: "active",
                    title: "Tổng số cuộc gọi gán nhãn không spam"
                },
                {
                    data: "active",
                    title: "Tổng số cuộc gọi không gán nhãn"
                }
            ];
        }
    },
    methods: {
        async filterTime(data) {
            this.tableFilter = data;
            await this.$nextTick();
            this.loadData();
        },
        async loadData() {
            this.$refs.table.reload();
        }
    }
};
</script>

<style scoped></style>
