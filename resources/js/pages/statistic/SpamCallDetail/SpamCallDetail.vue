<template>
    <div>
        <div class="row">
            <div
                class="col-5"
                align="right"
                style="    margin-bottom: 15px;
"
            >
                <label style="margin-top: 10px">Chọn khoảng thời gian</label>
            </div>
            <div
                class="col-2"
                style="    margin-bottom: 15px;
"
            >
                <form-control
                    v-model="timeFilter"
                    name="typeId"
                    :type="'select'"
                    :select-options="timeOptions"
                    :has-all-options="'true'"
                ></form-control>
            </div>
            <div class="col-12">
                <portlet title="Báo cáo chi tiết">
                    <data-table
                        ref="table"
                        :columns="columns"
                        url="/statistic/msisdn-summary-type/listing"
                        :post-data="tableFilter"
                    ></data-table>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
import Portlet from "~/components/common/Portlet";
import DataTable from "~/components/common/DataTable";
import { THREE_DAYS, SEVEN_DAYS, THIRTY_DAY } from "~/constants/constant";
import FormControl from "~/components/common/FormControl";

export default {
    name: "SpamCallDetail",
    components: { FormControl, DataTable, Portlet },
    data() {
        return {
            timeFilter: {},
            timeOptions: {
                placeholder: "Chọn thời gian",
                multiple: false,
                searchable: false,
                options: [
                    {
                        id: 1,
                        text: "3 ngày"
                    },
                    {
                        id: 2,
                        text: "7 ngày"
                    },
                    {
                        id: 3,
                        text: "30 ngày"
                    }
                ]
            },
            tableFilter: {
                duration_type_id: null
            }
        };
    },
    computed: {
        columns() {
            return [
                {
                    data: "msisdn",
                    title: "Số điện thoại"
                },
                {
                    data: "num_call_out",
                    title: "Tổng số cuộc gọi ra"
                },
                {
                    data: "duration_type.label",
                    title: "Khoảng thời gian",
                    render(data) {
                        if (data != null) {
                            var day = "";
                            switch (parseInt(data)) {
                                case THREE_DAYS:
                                    day = "3 ngày";
                                    break;
                                case SEVEN_DAYS:
                                    day = "7 ngày";
                                    break;
                                case THIRTY_DAY:
                                    day = "30 ngày";
                                    break;
                            }
                            return day;
                        } else return "-";
                    }
                },
                {
                    data: "sum_duration_call_out",
                    title: "Tổng thời lượng cuộc gọi ra (phút)"
                },
                {
                    data: "num_call_label_spam",
                    title: "Tổng số cuộc gọi được gán nhãn spam"
                },
                {
                    data: "num_call_label_not_spam",
                    title: "Tổng số cuộc gọi gán nhãn không spam"
                },
                {
                    data: "num_call_not_label",
                    title: "Tổng số cuộc gọi không gán nhãn"
                }
            ];
        }
    },
    watch: {
        timeFilter(data) {
            this.filterTime(data);
        }
    },
    methods: {
        async filterTime(data) {
            this.tableFilter.duration_type_id = data.id;
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
