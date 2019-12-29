<template>
    <another-highcharts
            :series="series"
            :has-legend="false"
            :categories="categories"
            :chart-type="'column'"
            :tooltip-format="'Số thiết bị:<b> {point.y}</b>'"
            :plot-options="plotOptions"
            :exporting="true"
            :colors="['#34bfa3']"
            :chart-height="450"
    ></another-highcharts>
</template>

<script>
    import AnotherHighcharts from "../../../components/common/AnotherHighcharts";
    import moment from "moment";
    import axios from "axios";

    export default {
        name: "UserHighchart",
        components: {AnotherHighcharts},
        props: {
            timeFilter: {
                type: Array,
                default: () => {
                    return [moment().format("YYYY-MM-DD hh:mm:ss"), moment().format("YYYY-MM-DD hh:mm:ss")]
                }
            }
        },
        watch: {
            timeFilter(data) {
                if (data != null) {
                    this.getDataHighchart(data);
                }
            }
        },
        data() {
            return {
                series: [
                    {
                        data: [],
                    }
                ],
                categories: [],
                plotOptions: {
                    column: {
                        minPointLength: 3
                    }
                },
            }
        },
        mounted() {
            this.getDataHighchart(this.timeFilter);
        },
        methods: {
            async getDataHighchart(time) {

                try {
                    let $this = this;
                    this.categories = [];
                    this.series[0].data = [];
                    let data = await axios.post("/statistic/get-data-column-user-device", {
                        time_filter: time
                    })

                    let arr = data.data.data;

                    console.log(arr.name)
                    if(arr.name.length != 0 ) {
                        arr.name.forEach(function (e)  {
                            $this.categories.push(e)
                        })


                        arr.countDevice.forEach(function (e)  {
                            $this.series[0].data.push(e)
                        })
                    } else {
                        $this.categories.push("Không có")
                        $this.series[0].data.push(0)
                    }

                }
                catch (e) {
                    console.log(e)
                }
            }
        },
    }
</script>

<style scoped>

</style>
