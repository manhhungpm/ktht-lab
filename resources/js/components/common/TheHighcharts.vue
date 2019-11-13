<template>
    <div class="highcharts"></div>
</template>

<script>
    import Highcharts from 'highcharts'

    export default {
        name: "TheHighcharts",
        props: {
            title: {
                type: String,
                default: ''
            },
            series: {
                type: Array,
                default: () => []
            },
            categories: {
                type: Array,
                default: () => []
            },
            type: 'column'
        },
        data() {
            return {
                chart: null
            }
        },
        mounted() {
            this.initChart()
        },
        methods: {
            initChart() {
                this.chart = Highcharts.chart(this.$el, {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: ''
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        }
                    },
                    exporting: {
                        enabled: false
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        pointFormat: 'Population in 2017: <b>{point.y:.1f} millions</b>'
                    },
                    series: this.series
                });
            }
        },
        watch: {
            series(val) {
                this.chart.update({
                    series: val
                })
            }
        }
    }
</script>

