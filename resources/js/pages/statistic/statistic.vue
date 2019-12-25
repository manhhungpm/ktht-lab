<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <the-date-range v-model="timeFilter"
                                :type="'datetimerange'"
                ></the-date-range>
            </div>
            <div class="col-md-6">
                <portlet :title="'Số lượng thiết bị quá hạn của người dùng mượn trong khoảng thời gian'">
                    <user-highchart :time-filter="timeFilter"></user-highchart>
                </portlet>
            </div>
            <div class="col-md-6">
                <portlet :title="'Số lượng dự án hoàn thành và chưa hoàn thành trong khoảng thời gian'">
                    <project-highchart :time-filter="timeFilter"></project-highchart>
                </portlet>
            </div>
        </div>
    </div>
</template>

<script>
    import Portlet from "../../components/common/Portlet";
    import TheDateRange from "../../components/common/TheDateRange";
    import ProjectHighchart from "./partials/ProjectHighchart";
    import moment from "moment";
    import axios from "axios";
    import UserHighchart from "./partials/UserHighchart";

    export default {
        name: "statistic",
        components: {UserHighchart, ProjectHighchart, TheDateRange, Portlet},
        data() {
            return {
                timeFilter: [moment().format("YYYY-MM-DD hh:mm:ss"), moment().format("YYYY-MM-DD hh:mm:ss")],
            }
        }
    }
</script>

<style scoped>

</style>