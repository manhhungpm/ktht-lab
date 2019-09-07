export default {
    list: {
        title: "Danh sách Brandname",
        brandname: "Đầu số quảng cáo",
        br_type: "Loại",
        reason: "Lí do",
        active: "Trạng thái",
        placeholder: {
            brandname: "Nhập đầu số quảng cáo",
            br_type: "Chọn loại",
            reason: "Nhập lí do",
            active: "Chọn trạng thái"
        },
        search: "Thông tin tìm kiếm",
        notification: {
            active:
                'Bạn có muốn kích hoạt đầu số <span class="text-danger">{0}</span> này không ?',
            disable:
                'Bạn có muốn vô hiệu đầu số <span class="text-danger">{0}</span> này không ?',
            active_multi: "Bạn có muốn kích hoạt các đầu số đã chọn không ?",
            disable_multi: "Bạn có muốn vô hiệu các đầu số đã chọn không ?",
            active_multi_success: "Kích hoạt các đầu số thành công",
            active_multi_fail: "Kích hoạt các đầu số các đầu số thất bại",
            disable_multi_success: "Vô hiệu các đầu số thành công",
            disable_multi_fail: "Vô hiệu các đầu số thất bại",
            active_success: "Kích hoạt brandname {0} thành công",
            active_fail: "Kích hoạt brandname {0} thất bại",
            disable_success: "Vô hiệu brandname {0} thành công",
            disable_fail: "Vô hiệu brandname {0} thất bại",
            add_success: "Thêm mới brandname thành công",
            add_fail: "Thêm mới brandname thất bại",
            edit_success: "Chỉnh sửa brandname thành công",
            edit_fail: "Chỉnh sửa brandname thất bại",
            must_select_at_least_one_record: "Phải chọn 1 bản ghi",
            export_error: "Export thất bại",
            no_record: "Không có bản ghi"
        },
        datatable: {
            column: {
                brandname: "Đầu số quảng cáo",
                br_type: "Loại",
                reason: "Lí do",
                active: "Trạng thái"
            }
        }
    },
    config: {
        title: "Cấu hình",
        information: "Thông tin cấu hình",
        date_threshold: "Ngưỡng ngày",
        month_threshold: "Ngưỡng tháng",
        br_type: "Loại",
        placeholder: {
            date_threshold: "Nhập ngưỡng ngày",
            br_type: "Chọn loại",
            month_threshold: "Nhập ngưỡng tháng"
        },
        notification: {
            active: "Bạn có muốn kích hoạt cấu hình này không ?",
            disable: "Bạn có muốn vô hiệu cấu hình này không ?",
            active_success: "Kích hoạt cấu hình thành công",
            active_fail: "Kích hoạt cấu hình thất bại",
            disable_success: "Vô hiệu cấu hình thành công",
            disable_fail: "Vô hiệu cấu hình thất bại",
            add_success: "Thêm mới cấu hình thành công",
            add_fail: "Thêm mới cấu hình thất bại",
            edit_success: "Chỉnh sửa cấu hình thành công",
            edit_fail: "Chỉnh sửa cấu hình thất bại"
        },
        datatable: {
            column: {
                date_threshold: "Ngưỡng ngày",
                month_threshold: "Ngưỡng tháng",
                br_type: "Loại",
                active: "Trạng thái"
            }
        },
        segment: {
            segment: "Phân khúc",
            modal_edit_title: "Cập nhật cấu hình tin nhắn theo phân khúc",
            modal_add_title: "Thêm cấu hình tin nhắn theo phân khúc",
            name: "Tên",
            per_day: "Số TN tối đa ngày",
            per_month: "Số TN tối đa tháng",
            sms_type: "Loại tin",
            sms_group: "Nhóm tin",
            apply_time: "Khoảng thời gian áp dụng",
            who_created: "Người tạo",
            who_updated: "Người cập nhật",
            when_created: "Thời gian tạo",
            when_updated: "Thời gian cập nhật",
            ip: "IP máy",
            action: "Thao tác",
            status: "Trạng thái",
            alert_add_confirm: "Bạn có chắc chắn muốn thêm cấu hình này không?",
            alert_edit_confirm:
                "Bạn có chắc chắn muốn cập nhật cấu hình này không?",
            alert_active_confirm:
                "Bạn có chắc chắn muốn kích hoạt cấu hình này",
            alert_active_suffixes_confirm: " không?",
            alert_inactive_confirm:
                "Bạn có chắc chắn muốn vô hiệu cấu hình này",
            alert_inactive_suffixes_confirm: " không?",
            alert_active_successfully: "Bạn đã kích hoạt cấu hình thành công",
            alert_inactive_successfully: "Bạn đã vô hiệu cấu hình thành công",
            alert_edit_successfully: "Cập nhật cấu hình thành công",
            alert_add_successfully: "Thêm cấu hình thành công",
            alert_change_status_confirm:
                "Bạn có chắc chắn thay đổi trạng thái cấu hình không?",
            alert_change_status_successfully:
                "Thay đội trạng thái cấu hình thành công",
            smsTypePlaceholder: "Chọn loại tin",
            segmentPlaceholder: "Chọn loại phân khúc",
            to: "đến",
            show_history: "Xem lịch sử"
        },
        duplicate: {
            modal_edit_title: "Cập nhật cấu hình tin nhắn trùng",
            modal_add_title: "Thêm cấu hình tin nhắn trùng",
            title: "Cấu hình số lượng tin nhắn trùng nội dung",
            high_warning: "Mức cảnh báo cao/ 24h",
            danger_warning: "Mức cảnh báo nguy hiểm/ 24h",
            crisis_warning: "Mức cảnh báo khủng hoảng/ 24h",
            high_warning_from: "Từ",
            danger_warning_from: "Từ",
            crisis_warning_from: "Từ",
            high_warning_to: "Đến",
            danger_warning_to: "Đến",
            crisis_warning_to: "Đến",
            sms_type: "Loại tin",
            sms_group: "Nhóm tin",
            apply_time: "Khoảng thời gian áp dụng",
            who_created: "Người tạo",
            who_updated: "Người cập nhật",
            when_created: "Thời gian tạo",
            when_updated: "Thời gian cập nhật",
            ip: "IP máy",
            action: "Thao tác",
            status: "Trạng thái",
            alert_add_confirm: "Bạn có chắc chắn muốn thêm cấu hình này không?",
            alert_edit_confirm:
                "Bạn có chắc chắn muốn cập nhật cấu hình này không?",
            alert_active_confirm: "Bạn có chắc chắn muốn kích hoạt cấu hình ",
            alert_active_suffixes_confirm: " không?",
            alert_inactive_confirm:
                "Bạn có chắc chắn muốn vô hiệu cấu hình này",
            alert_inactive_suffixes_confirm: " không?",
            alert_active_successfully: "Bạn đã kích hoạt cấu hình thành công",
            alert_inactive_successfully: "Bạn đã vô hiệu cấu hình thành công",
            alert_edit_successfully: "Cập nhật cấu hình thành công",
            alert_add_successfully: "Thêm cấu hình thành công",
            alert_change_status_confirm:
                "Bạn có chắc chắn thay đổi trạng thái cấu hình không?",
            alert_change_status_successfully:
                "Thay đội trạng thái cấu hình thành công",
            smsTypePlaceholder: "Chọn loại tin",
            segmentPlaceholder: "Chọn loại phân khúc",
            to: "đến",
            show_history: "Xem lịch sử"
        },
        segment_warning: {
            modal_edit_title: "Cập nhật cấu hình cảnh báo SL tương tác đến KH",
            modal_add_title: "Thêm mới cấu hình cảnh báo SL tương tác đến KH",
            title: "Cấu hình số lượng tin nhắn trùng nội dung",
            high_warning: "Mức cảnh báo cao/ ngày",
            danger_warning: "Mức cảnh báo nguy hiểm/ ngày",
            crisis_warning: "Mức cảnh báo khủng hoảng/ ngày",
            high_warning_month: "Mức cảnh báo cao/ tháng",
            danger_warning_month: "Mức cảnh báo nguy hiểm/ tháng",
            crisis_warning_month: "Mức cảnh báo khủng hoảng/ tháng",
            high_warning_title: "Mức cảnh báo cao",
            danger_warning_title: "Mức cảnh báo nguy hiểm",
            crisis_warning_title: "Mức cảnh báo khủng hoảng",
            high_warning_from: "Từ",
            danger_warning_from: "Từ",
            crisis_warning_from: "Từ",
            high_warning_to: "Đến",
            danger_warning_to: "Đến",
            crisis_warning_to: "Đến",
            sms_type: "Loại tin",
            sms_group: "Nhóm tin",
            apply_time: "Khoảng thời gian áp dụng",
            who_created: "Người tạo",
            who_updated: "Người cập nhật",
            when_created: "Thời gian tạo",
            when_updated: "Thời gian cập nhật",
            ip: "IP máy",
            action: "Thao tác",
            status: "Trạng thái",
            alert_add_confirm: "Bạn có chắc chắn muốn thêm cấu hình này không?",
            alert_edit_confirm:
                "Bạn có chắc chắn muốn cập nhật cấu hình này không?",
            alert_active_confirm: "Bạn có chắc chắn muốn kích hoạt cấu hình ",
            alert_active_suffixes_confirm: " không?",
            alert_inactive_confirm:
                "Bạn có chắc chắn muốn vô hiệu cấu hình này",
            alert_inactive_suffixes_confirm: " không?",
            alert_active_successfully: "Bạn đã kích hoạt cấu hình thành công",
            alert_inactive_successfully: "Bạn đã vô hiệu cấu hình thành công",
            alert_edit_successfully: "Cập nhật cấu hình thành công",
            alert_add_successfully: "Thêm cấu hình thành công",
            alert_change_status_confirm:
                "Bạn có chắc chắn thay đổi trạng thái cấu hình không?",
            alert_change_status_successfully:
                "Thay đội trạng thái cấu hình thành công",
            smsTypePlaceholder: "Chọn loại tin",
            segmentPlaceholder: "Chọn loại phân khúc",
            month: "Tháng",
            day: "Ngày",
            segment: "Phân khúc",
            to: "đến",
            show_history: "Xem lịch sử"
        },
        timeframe: {
            list: "Danh sách cấu hình thời gian nhắn tin",
            add: "Thêm mới",
            edit: "Chỉnh sửa",
            alert_add: "Bạn có muốn thêm mới cấu hình này không ?",
            alert_edit: "Bạn có muốn chỉnh sửa cấu hình này không ?",
            apply_time: "Thời gian áp dụng",
            approve_time: "Khung thời gian cho phép gửi SMS",
            time_range: "Khoảng thời gian",
            table: {
                days_of_week: "Ngày áp dụng",
                time_frame: "Khung thời gian được phép gửi SMS",
                active: "Trạng thái",
                sms_group: "Nhóm tin",
                sms_type: "Loại tin",
                apply_time: "Thời gian áp dụng"
            },
            status: {
                active: "Đang hoạt động",
                disabled: "Vô hiệu"
            },
            notification: {
                active:
                    "Bạn có muốn kích hoạt cấu hình thời gian nhắn tin này không? ",
                disable:
                    "Bạn có muốn vô hiệu cấu hình thời gian nhắn tin này không?"
            },
            time_frame_required: "Khung thời gian không được bỏ trống",
            show_history: "Xem lịch sử"
        },
        time_warning: {
            list: "Danh sách cấu hình tương tác ngoài khung giờ",
            alert_add: "Bạn có muốn thêm mới cấu hình này không ?",
            alert_edit: "Bạn có muốn chỉnh sửa cấu hình này không ?",
            add: "Thêm mới",
            edit: "Chỉnh sửa",
            from: "Từ",
            to: "Đến",
            minute: "phút",
            apply_time: "Thời gian áp dụng",
            approve_time: "Khung thời gian cho phép gửi SMS",
            time_range: "Khoảng thời gian",
            high_warning: "Cảnh báo cao /24h",
            danger_warning: "Cảnh báo nguy hiểm /24h",
            crisis_warning: "Cảnh báo khung hoảng /24h",
            table: {
                days_of_week: "Ngày áp dụng",
                time_frame: "Khung giờ",
                active: "Trạng thái",
                sms_group: "Nhóm tin",
                sms_type: "Loại tin",
                apply_time: "Thời gian áp dụng",
                high_warning: "Cảnh báo cao /24h",
                danger_warning: "Cảnh báo nguy hiểm /24h",
                crisis_warning: "Cảnh báo khung hoảng /24h"
            },
            status: {
                active: "Đang hoạt động",
                disabled: "Vô hiệu"
            },
            notification: {
                active: "Bạn có muốn kích hoạt cấu hình tương tác này không? ",
                disable: "Bạn có muốn vô hiệu cấu hình tương tác này không?"
            },
            time_frame_required: "Khung thời gian không được bỏ trống",
            show_history: "Xem lịch sử"
        }
    },
    report: {
        report: "Báo cáo",
        day: {
            alias: {
                table: {
                    date: "Ngày",
                    type: "Loại",
                    alias: "Alias",
                    msg_count: "Số TN/ngày/TB",
                    msg_total: "Tổng số TN",
                    sub_count: "Số TB"
                }
            },
            segment: {
                table: {
                    date: "Ngày",
                    type: "Kiểu tin",
                    amount: "Số tin nhắn nhận được",
                    vip: "Số tin nhắn KH VIP nhận được",
                    potential: "Số tin nhắn KH tiềm năng nhận được",
                    normal: "Số tin nhắn KH đại trà nhận được",
                    total: "Tổng"
                }
            }
        },
        accumulate: {
            segment: {
                table: {
                    date: "Ngày",
                    type: "Loại",
                    amount: "Số tin nhắn nhận được",
                    vip: "Số tin nhắn KH VIP nhận được",
                    potential: "Số tin nhắn KH tiềm năng nhận được",
                    normal: "Số tin nhắn KH đại trà nhận được",
                    total: "Tổng"
                }
            },
            alias: {
                table: {
                    date: "Ngày",
                    type: "Loại",
                    alias: "Alias",
                    msg_count: "Số TN/ngày/TB",
                    msg_total: "Tổng số TN",
                    sub_count: "Số TB"
                }
            }
        },
        duplicate: {
            day: {
                alias: {
                    table: {
                        date: "Ngày",
                        type: "Loại",
                        alias: "Alias",
                        msg_count: "Số TN/ngày/TB",
                        msg_total: "Tổng số TN",
                        sub_count: "Số TB"
                    }
                },
                segment: {
                    table: {
                        date: "Ngày",
                        type: "Kiểu tin",
                        amount: "Số tin nhắn nhận được",
                        vip: "Số tin nhắn KH VIP nhận được",
                        potential: "Số tin nhắn KH tiềm năng nhận được",
                        normal: "Số tin nhắn KH đại trà nhận được",
                        total: "Tổng"
                    }
                }
            },
            month: {
                alias: {
                    table: {
                        date: "Ngày",
                        type: "Loại",
                        alias: "Alias",
                        msg_count: "Số TN/ngày/TB",
                        msg_total: "Tổng số TN",
                        sub_count: "Số TB"
                    }
                },
                segment: {
                    table: {
                        date: "Ngày",
                        type: "Kiểu tin",
                        amount: "Số tin nhắn nhận được",
                        vip: "Số tin nhắn KH VIP nhận được",
                        potential: "Số tin nhắn KH tiềm năng nhận được",
                        normal: "Số tin nhắn KH đại trà nhận được",
                        total: "Tổng"
                    }
                }
            }
        },
        month: {
            alias: {
                table: {
                    date: "Tháng",
                    type: "Loại",
                    alias: "Alias",
                    msg_count: "Số TN/ngày/TB",
                    msg_total: "Tổng số TN",
                    sub_count: "Số TB"
                }
            },
            segment: {
                table: {
                    date: "Tháng",
                    type: "Kiểu tin",
                    amount: "Số tin nhắn nhận được",
                    vip: "Số tin nhắn KH VIP nhận được",
                    potential: "Số tin nhắn KH tiềm năng nhận được",
                    normal: "Số tin nhắn KH đại trà nhận được",
                    total: "Tổng"
                }
            }
        },
        detailed: {
            title: "Báo cáo chi tiết",
            add: "Tạo yêu cầu mới",
            status: {
                pending: "Chưa xử lý",
                processing: "Đang xử lý",
                done: "Đã xử lý",
                error: "Có lỗi"
            },
            phone: "Số điện thoại",
            placeholder: {
                phone:
                    "Định dạng: 9xxxxxxxx. Không quá 10 số điện thoại. Cách nhau bởi dấu ';'"
            },
            form: {
                title: "Detailed Report Request"
            },
            datatable: {
                column: {
                    phone: "Số điện thoại yêu cầu",
                    from: "Từ",
                    to: "Đến"
                }
            }
        },
        outlier_detail: {
            title: "Tin nhắn bất thường",
            list_title: "Danh sách tin nhắn bất thường",
            datatable: {
                column: {
                    date: "Ngày",
                    vip_outlier: "Số tin vip/ngày",
                    pot_outlier: "Số tin tiềm năng/ngày",
                    nor_outlier: "Số tin đại trà/ngày",
                    vip_month_outlier: "Số tin vip/tháng",
                    pot_month_outlier: "Số tin tiềm năng/tháng",
                    nor_month_outlier: "Số tin đại trà/tháng"
                }
            }
        },
        wrong_time: {
            title: "Gửi sai thời gian",
            list_title: "Danh sách gửi sai thời gian",
            datatable: {
                column: {
                    date: "Ngày"
                }
            }
        }
    },
    sms_groups: {
        title: "Quản lý nhóm tin",
        name: "Tên nhóm tin",
        description: "Mô tả",
        add: "Thêm mới nhóm tin",
        edit: "Chỉnh sửa nhóm tin",
        placeholder: {
            name: "Nhập nhóm tin",
            description: "Nhập mô tả",
            search_placeholder: "Nhập tên nhóm tin"
        },
        datatable: {
            title: "Danh sách nhóm tin",
            column: {
                name: "Tên nhóm tin",
                description: "Mô tả"
            }
        }
    },
    sms_types: {
        title: "Quản lý loại tin",
        name: "Tên loại tin",
        description: "Mô tả",
        group: "Nhóm tin",
        alias: "Alias",
        prefix: "Bắt đầu bằng",
        priority: "Độ ưu tiên",
        add: "Thêm mới loại tin",
        edit: "Chỉnh sửa loại tin",
        placeholder: {
            search_placeholder: "Nhập tên loại tin",
            name: "Nhập loại tin",
            description: "Nhập mô tả",
            group: "Chọn nhóm tin",
            alias: "Nhập alias",
            prefix: "Bắt đầu bằng ",
            priority: "Độ ưu tiên"
        },
        datatable: {
            title: "Danh sách loại tin",
            column: {
                name: "Loại tin",
                description: "Mô tả",
                group: "Nhóm tin",
                alias: "Alias",
                prefix: "Bắt đầu bằng",
                priority: "Độ ưu tiên"
            }
        }
    }
};
