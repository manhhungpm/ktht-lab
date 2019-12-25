<template>
    <div>
        <div class="m-portlet " style="margin-bottom:1rem">
            <div class="m-portlet__body  m-portlet__body--no-padding">
                <div class="row m-row--no-padding m-row--col-separator-xl">
                    <widget
                            :value="dataWidget.total_device_group"
                            :title="'Tổng số nhóm thiết bị'"
                            :type-class-text="'m--font-info'"
                    >
                        <span
                                slot="description"
                                class="m-widget24__desc"
                        >Nhiều thiết bị nhất: Nhóm ABC</span
                        >
                    </widget>
                    <widget
                            :value="dataWidget.total_provider"
                            :title="'Tổng số nhà cung cấp'"
                            :type-class-progress-bar="'m--bg-danger'"
                            :type-class-text="'m--font-success'"
                    >
                        <span
                                slot="description"
                                class="m-widget24__desc"
                        >Cung cấp nhiều nhất: Đơn vị DEF</span
                        >
                    </widget>
                    <widget
                            :value="dataWidget.total_project"
                            :title="'Tổng số dự án'"
                            :type-class-progress-bar="'m--bg-success'"
                            :type-class-text="'m--font-danger'"
                    >
                        <span
                                slot="description"
                                class="m-widget24__desc"
                        >{{ formatNumber(dataWidget.has_doing_project) }} dự án đang thực hiện</span
                        >
                    </widget>
                    <widget
                            :value="dataWidget.total_device_type"
                            :title="'Tổng số thiết bị'"
                            :label="'+10% so với tháng trước'"
                            :type-class-progress-bar="'m--bg-brand'"
                            :type-class-text="'m--font-brand'"
                    >
                        <div slot="description" style="margin-left: 23px">
                            Trong đó:
                            <div>
                                <span>- Đang mượn:</span><b> {{ dataWidget.has_rent_device }}</b>
                            </div>
                            <div>
                                <span>- Qúa hạn:</span>
                                <b>{{ dataWidget.has_rent_device_warning }} </b>
                            </div>
                        </div>
                    </widget>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <portlet :title="'Biểu đồ số lượng thiết bị còn so với thiết bị đã mượn'">
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
                </portlet>
            </div>
            <div class="col-md-6">
                <portlet :title="'Biểu đồ số lượng thiết bị của các dự án'">
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
                </portlet>
            </div>
            <div class="col-md-12">
                <portlet :title="$t('dashboard.title_log')">
                    <data-table ref="table"
                                :columns="columns"
                                url="/admin/log/listing"
                                :fixed-columns-left="2"
                                :fixed-columns-right="1"
                                :search-placeholder="$t('dashboard.placeholder.search')"
                                :order-column-index="5"
                                :actions="actions"
                                :order-type="'desc'"></data-table>
                </portlet>
            </div>
            <detail-change-modal ref="modal"></detail-change-modal>
        </div>
    </div>
</template>

<script>

    import AnotherHighcharts from "../../components/common/AnotherHighcharts";

    const defaultWidget = {
        total_device_type: null,
        total_project: null,
        total_device_group: null,
        total_provider: null,
        has_max_device_type: null,
        has_max_provider: null,
        has_doing_project: null,
        has_rent_device: null,
        has_rent_device_warning: null
    };

    import moment from "moment";
    import axios from "axios";
    import Portlet from "../../components/common/Portlet";
    import DataTable from "../../components/common/DataTable";
    import {
        generateTableAction,
        htmlEscapeEntities,
        reloadIntelligently
    } from "~/helpers/tableHelper";
    import DetailChangeModal from "./partials/DetailChangeModal";
    import Widget from "../../components/common/Widget";
    import {formatNumber} from "../../helpers/formats";

    function parserObjectName(class_name) {
        if (class_name != null && class_name != undefined) {
            switch (class_name) {
                case 'users':
                    return htmlEscapeEntities('Người dùng')
                    break
                case 'classes':
                    return htmlEscapeEntities('Lớp')
                    break
                case 'faculty':
                    return htmlEscapeEntities('Khoa')
                    break
                case 'roles':
                    return htmlEscapeEntities('Quyền')
                    break
                case 'group_devices':
                    return htmlEscapeEntities('Nhóm thiết bị')
                    break
                case 'devices':
                    return htmlEscapeEntities('Thiết bị')
                    break
                case 'providers':
                    return htmlEscapeEntities('Nhà cung cấp')
                    break
                case 'stores':
                    return htmlEscapeEntities('Nơi lưu trữ')
                    break
                case 'projects':
                    return htmlEscapeEntities('Dự án')
                    break
                case 'rent':
                    return htmlEscapeEntities('Mượn trả')
                    break
            }
        } else {
            return ''
        }
    }

    export default {
        components: {AnotherHighcharts, Widget, DetailChangeModal, DataTable, Portlet},
        layout: "default",
        middleware: "auth",
        metaInfo() {
            return {title: "Dashboard"};
        },
        data() {
            return {
                dataWidget: defaultWidget,
                //Pie
                pieSeries: [
                    {
                        data: [
                            {
                                name: "Số lượng thiết bị còn"
                            },
                            {
                                name: "Số lượng thiết bị mượn"
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
                //Column
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
        computed: {
            columns() {
                return [
                    {
                        data: 'username',
                        title: this.$t('dashboard.username'),
                    },
                    {
                        data: 'action_name',
                        title: this.$t('dashboard.action_name'),
                        render(data) {
                            switch (data) {
                                case 'Add':
                                    return htmlEscapeEntities('Thêm')
                                    break
                                case 'Update':
                                    return htmlEscapeEntities('Cập nhật')
                                    break
                                case 'Delete':
                                    return htmlEscapeEntities('Xóa')
                                    break
                                case 'Login':
                                    return htmlEscapeEntities('Đăng nhập')
                                    break
                                case 'Logout':
                                    return htmlEscapeEntities('Đăng xuất')
                                    break
                            }
                        }
                    },
                    {
                        data: 'class_name',
                        title: this.$t('dashboard.class_name'),
                        render(data) {
                            return parserObjectName(data)
                        }
                    },
                    {
                        data: 'object_name',
                        title: this.$t('dashboard.object_name'),
                    },
                    {
                        data: 'created_at',
                        title: this.$t('dashboard.created_at'),
                        render(data) {
                            return moment(data).format('DD/MM/YYYY HH:MM:ss')
                        }
                    },
                    {
                        data: null,
                        title: this.$t("datatable.column.action"),
                        orderable: false,
                        className: "text-center tb-actions",
                        render(data) {
                            return (
                                generateTableAction(
                                    "showHistory",
                                    "showDetailChange"
                                )
                            );
                        }
                    }
                ];
            },
            actions() {
                return [
                    {
                        type: 'click',
                        name: 'showDetailChange',
                        action: this.showDetailChange
                    },
                ]
            },
        },
        mounted() {
            this.getDataWidget()
            this.getDataHighchartPie()
            this.getDataHighchartColumn();
        },
        methods: {
            showDetailChange(table, rowData) {
                this.$refs.modal.show(rowData)
            },
            formatNumber(value) {
                return formatNumber(value);
            },
            async getDataWidget() {
                try {
                    let data = await axios.post("/widget/get-data");

                    let arr = data.data;

                    this.dataWidget.has_doing_project = arr.data['has_doing_project']
                    this.dataWidget.total_provider = arr.data['total_provider']
                    this.dataWidget.total_device_group = arr.data['total_device_group']
                    this.dataWidget.total_project = arr.data['total_project']
                    this.dataWidget.total_device_type = arr.data['total_device_type']
                    this.dataWidget.has_rent_device = arr.data['has_rent_device']
                    this.dataWidget.has_rent_device_warning = arr.data['has_rent_device_warning']
                }
                catch (e) {
                    console.log(e)
                }
            },
            async getDataHighchartPie() {
                try {
                    let data = await axios.post("/statistic/get-data-pie")

                    let arr = data.data

                    this.pieSeries = [
                        {
                            colorByPoint: true,
                            data: [
                                {
                                    name: "SL TB còn",
                                    y: arr.data['device_remain'],
                                    sliced: true,
                                    selected: true
                                },
                                {
                                    name:
                                        "SL TB mượn",
                                    y: arr.data['device_rent']
                                }
                            ]
                        }
                    ];
                }
                catch (e) {
                    console.log(e);
                }
            },
            async getDataHighchartColumn(){
                try {
                    let $this = this;

                    let data = await axios.post("/statistic/get-data-column")

                    let arr = data.data.data

                    arr.project_name.forEach(function (e)  {
                        $this.categories.push(e.name)
                    })

                    arr.project_device.forEach(function (e) {
                        $this.series[0].data.push(e);
                    })
                    console.log(arr.project_device)

                } catch (e) {
                    console.log(e);
                }
            }
        }
    };
</script>
