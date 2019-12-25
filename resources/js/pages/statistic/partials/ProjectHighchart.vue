<template>
    <another-highcharts
            :series="pieSeries"
            :plot-options="plotOptionPie"
            :tooltip-format="'<b>{point.percentage:,.2f}%</b>'"
            :chart-height="450"
            :margin-top="15"
            :horizontal-margin="30"
            :exporting="true"
            :has-legend="true">
    </another-highcharts>
</template>

<script>
    import AnotherHighcharts from "../../../components/common/AnotherHighcharts";
    import moment from "moment";
    import axios from "axios";

    export default {
        name: "ProjectHighchart",
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
                pieSeries: [
                    {
                        data: [
                            {
                                name: "Dự án đã hoàn thành"
                            },
                            {
                                name: "Dự án chưa hoàn thành"
                            },
                        ]
                    }
                ],
                plotOptionPie: {
                    pie: {
                        allowPointSelect: true,
                        cursor: "pointer",
                        dataLabels: {
                            distance: 20,
                            format: "{point.name}: {point.percentage:.2f} %",
                            enabled: true,
                            style: {
                                color: "#fff"
                            }
                        },
                        showInLegend: true,
                        size: 250,
                        minSize: 200
                    },
                    series: {
                        animation: {
                            duration: 1000
                        }
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
                    let data = await axios.post("/statistic/get-data-pie-project", {
                        time_filter: time
                    })

                    let arr = data.data.data;

                    this.pieSeries = [
                        {
                            colorByPoint: true,
                            data: [
                                {
                                    name: "Dự án đã hoàn thành",
                                    y: arr['projectDone'],
                                    sliced: true,
                                    selected: true
                                },
                                {
                                    name:
                                        "Dự án chưa hoàn thành",
                                    y: arr['projectNotDone']
                                }
                            ]
                        }
                    ];
                }
                catch (e) {
                    console.log(e)
                }
            }
        },
    }
</script>