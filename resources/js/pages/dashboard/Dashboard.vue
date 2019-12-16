<template>
    <div>
        <div class="row">
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
        components: {DetailChangeModal, DataTable, Portlet},
        layout: "default",
        middleware: "auth",
        metaInfo() {
            return {title: "Dashboard"};
        },
        data() {
            return {}
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
            }
        },
        mounted() {
        },
        methods: {
            showDetailChange(table, rowData) {
                this.$refs.modal.show(rowData)
            },
        }
    };
</script>
