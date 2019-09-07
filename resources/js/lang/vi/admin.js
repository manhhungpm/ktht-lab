export default {
    users: {
        manage: "Quản lý tài khoản",
        role: "Vai trò",
        status: "Trạng thái",
        user_name: "Tên tài khoản",
        add_user: "Thêm tài khoản",
        edit_user: "Cập nhật tài khoản",
        manage1: "manage1",
        full_name: "Họ tên",
        email: "Email",
        phone: "Điện thoại",
        expired_at: "Thời điểm hết hạn",
        title: {
            search: "Thông tin tìm kiếm",
            datatable: "Danh sách tài khoản"
        },
        datatable: {
            column: {
                name: "Tài khoản",
                display_name: "Tên",
                active: "Trạng thái",
                role: "Vai trò",
                expired_at: "Thời điểm hết hạn"
            }
        },
        notification: {
            add_success: "Thêm người dùng thành công",
            edit_success: "Chỉnh sửa người dùng thành công",
            add_fail: "Thêm người dùng thất bại",
            edit_fail: "Chỉnh sửa người dùng thất bại",
            active_success: "Kích hoạt tài khoản {0} thành công",
            disable_success: "Vô hiệu hóa tài khoản {0} thành công",
            active_fail: "Kích hoạt tài khoản {0} thất bại",
            disable_fail: "Vô hiệu hóa tài khoản {0} thất bại",
            active:
                "Bạn có chắc chắn kích hoạt tài khoản <span class='text-danger'>{0}</span> này?",
            disable:
                "Bạn có chắc chắn vô hiệu tài khoản <span class='text-danger'>{0}</span> này?"
        },
        placeholder: {
            name: "Nhập tên tài khoản",
            select_role: "Chọn vai trò",
            select_status: "Chọn trạng thái",
            display_name: "Nhập tên họ tên đầy đủ",
            email: "Nhập email",
            phone: "Nhập số điện thoại",
            datetime: "Chọn thời gian"
        }
    },
    api: {
        manage: "Quản lý API",
        name: "Mã Api",
        display_name: "Tên Api",
        description: "Mô tả",
        add_api: "Thêm mới Api",
        edit_api: "Cập nhật Api",
        placeholder: {
            name: "Nhập mã Api",
            display_name: "Nhập tên Api",
            description: "Nhập mô tả",
            search_placeholder: "Nhập mã Api"
        },
        notification: {
            add_success: "Thêm Api thành công",
            edit_success: "Chỉnh sửa Api thành công",
            add_fail: "Thêm Api thất bại",
            edit_fail: "Chỉnh sửa Api thất bại",
            active_success: "Kích hoạt Api {0} thành công",
            disable_success: "Vô hiệu hóa Api {0} thành công",
            active_fail: "Kích hoạt Api {0} thất bại",
            disable_fail: "Vô hiệu hóa Api {0} thất bại",
            active:
                "Bạn có chắc chắn kích hoạt Api <span class='text-danger'>{0}</span> ?",
            disable:
                "Bạn có chắc chắn vô hiệu Api <span class='text-danger'>{0}</span> ?"
        },
        portlet: {
            title: "Danh sách Api"
        },
        datatable: {
            column: {
                name: "Mã Api",
                display_name: "Tên",
                description: "Mô tả",
                active: "Trạng thái"
            }
        },
        account_api: {
            edit: "Cập nhật tài khoản Api",
            add: "Thêm mới tài khoản Api",
            name: "Tên tài khoản",
            api: "Quyền",
            placeholder: {
                name: "Nhập tên tài khoản",
                api: "Chọn Api ",
                search_placeholder: "Nhập tên tài khoản"
            },
            notification: {
                add_success: "Thêm tài khoản Api thành công",
                edit_success: "Chỉnh tài khoản sửa Api thành công",
                add_fail: "Thêm tài khoản Api thất bại",
                edit_fail: "Chỉnh tài khoản sửa Api thất bại",
                active_success: "Kích hoạt tài khoản Api {0} thành công",
                disable_success: "Vô hiệu hóa tài khoản Api {0} thành công",
                active_fail: "Kích hoạt tài khoản Api {0} thất bại",
                disable_fail: "Vô hiệu hóa tài khoản Api {0} thất bại",
                active:
                    "Bạn có chắc chắn kích hoạt tài khoản Api <span class='text-danger'>{0}</span> ?",
                disable:
                    "Bạn có chắc chắn vô hiệu tài khoản Api <span class='text-danger'>{0}</span> ?",
                reset_key:
                    "Bạn có chắc chắn muốn thay đổi khóa bí mật của tài khoản này",
                reset_success: "Thay đổi thành công",
                reset_fail: "Thay đổi thất bại"
            },
            portlet: {
                title: "Danh sách tài khoản Api"
            },
            datatable: {
                column: {
                    name: "Tài khoản",
                    secret_key: "Khóa bí mật",
                    api_account_apis: "Api được truy cập",
                    active: "Trạng thái"
                }
            }
        }
    },
    configs: {
        manage: "Quản lý cấu hình",
        name: "Tên cấu hình",
        group_name: "Tên nhóm ",
        description: "Mô tả",
        value: "Giá trị",
        add: "Thêm mới cấu hình",
        edit: "Cập nhật cấu hình",
        placeholder: {
            name: "Nhập tên cấu hính",
            group_name: "Nhập nhóm cấu hình",
            description: "Nhập mô tả",
            value: "Nhập giá trị",
            search_placeholder: "Nhập tên cấu hình"
        },
        notification: {
            add_success: "Thêm cấu hình thành công",
            edit_success: "Chỉnh sửa cấu hình thành công",
            add_fail: "Thêm cấu hình thất bại",
            edit_fail: "Chỉnh sửa cấu hình thất bại"
        },
        datatable: {
            column: {
                group_name: "Nhóm cấu hình",
                name: "Tên cấu hình",
                value: "Giá trị",
                description: "Mô tả"
            }
        },
        portlet: {
            title: "Danh sách cấu hình"
        }
    },
    smstopics: {
        manage: "Quản lý Sms Topic",
        chart: "Biểu đồ topic",
        portlet: {
            title: "Danh sách Sms Topic"
        }
    }
};
