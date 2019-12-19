<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <the-date-range></the-date-range>
            </div>
            <div class="col-md-6">
                <portlet :title="'Số lượng thiết bị mượn và còn lại trong khoảng thời gian'"></portlet>
            </div>
            <div class="col-md-6">
                <portlet :title="'Số lượng dự án hoàn thành và chưa hoàn thành trong khoảng thời gian'"></portlet>
            </div>
        </div>
    </div>
</template>

<script>
    import Portlet from "../../components/common/Portlet";
    import TheDateRange from "../../components/common/TheDateRange";

    export default {
        name: "statistic",
        components: {TheDateRange, Portlet}
    }
</script>

<style scoped>

</style>