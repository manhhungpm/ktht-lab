export default {
    users: {
        manage: "User Management",
        role: "Role",
        status: "Status",
        user_name: "User name",
        add_user: "Add user",
        edit_user: "Edit user",
        full_name: "Full name",
        password: "Password",
        repassword: "Password Confirmation",
        renew_password: "Renew Password",
        email: "Email",
        phone: "Phone number",
        expired_at: "Epired at",
        title: {
            search: "Seach",
            datatable: "List user"
        },
        datatable: {
            column: {
                name: "Name",
                display_name: "Display name",
                active: "Status",
                role: "Role",
                expired_at: "Expired at"
            }
        },
        notification: {
            add_success: "Add user success",
            edit_success: "Edit user success",
            add_fail: "Add user fail",
            edit_fail: "Edit user fail",
            active_success: "Active user {0} success",
            disable_success: "Disable user {0} success",
            active_fail: "Active user {0} fail",
            disable_fail: "Disable user {0} fail",
            active:
                "Do you want to active this user <span class='text-danger'>{0}</span>?",
            disable:
                "Do you want to disable this user <span class='text-danger'>{0}</span>?"
        },
        placeholder: {
            name: "Enter name",
            select_role: "Select role",
            select_status: "Select a status",
            display_name: "Enter full name",
            email: "Enter email",
            phone: "Enter phone number",
            datetime: "Select a datetime",
            password: "Enter password"
        }
    },
    api: {
        manage: "Api Management",
        name: "Name",
        display_name: "Display name",
        description: "Description",
        add_api: "Add Api",
        edit_api: "Edit Api",
        placeholder: {
            name: "Enter name",
            display_name: "Enter display name",
            description: "Enter description",
            search_placeholder: "Enter api"
        },
        notification: {
            add_success: "Add Api success",
            edit_success: "Edit Api success",
            add_fail: "Add Api fail",
            edit_fail: "Edit Api fail",
            active_success: "Active Api success",
            disable_success: "Disable Api success",
            active_fail: "Active Api fail",
            disable_fail: "Disable Api fail",
            active: "Do you want to active this Api?",
            disable: "Do you want to disable this Api?"
        },
        portlet: {
            title: "List Api"
        },
        datatable: {
            column: {
                name: "Name",
                display_name: "Display name",
                description: "Description",
                active: "Status"
            }
        },
        account_api: {
            edit: "Update Api Account ",
            add: "Add Api Account",
            name: "Account name",
            api: "Api",
            placeholder: {
                name: "Enter name",
                api: "Select api ",
                search_placeholder: "Enter name"
            },
            notification: {
                add_success: "Add Api Account success",
                edit_success: "Edit Api Account success",
                add_fail: "Add Api Account fail",
                edit_fail: "Edit Api Account fail",
                active_success: "Active Api Account success",
                disable_success: "Disable Api Account success",
                active_fail: "Active Api Account fail",
                disable_fail: "Disable Api Account fail",
                active: "Do you want to active this Api Account?",
                disable: "Do you want to disable this Api Account?",
                reset_key: "Do you want to change secret key this Api Account?",
                reset_success: "Change Api Account success",
                reset_fail: "Change Api Account fail"
            },
            portlet: {
                title: "List Api Account"
            },
            datatable: {
                column: {
                    name: "Name",
                    secret_key: "Secret key",
                    api_account_apis: "List Api",
                    active: "Status"
                }
            }
        }
    },
    configs: {
        manage: "Config Management",
        name: "Name",
        group_name: "Group name",
        description: "Description",
        value: "Value",
        add: "Add config",
        edit: "Edit config",
        placeholder: {
            name: "Enter name",
            group_name: "Enter group name",
            description: "Enter description",
            value: "Enter value",
            search_placeholder: "Enter name"
        },
        notification: {
            add_success: "Add config success",
            edit_success: "Edit config success",
            add_fail: "Add config fail",
            edit_fail: "Edit config fail"
        },
        datatable: {
            column: {
                group_name: "Group name",
                name: "Name",
                value: "Value",
                description: "Description"
            }
        },
        portlet: {
            title: "List Config"
        }
    },
    smstopics: {
        manage: "Sms Topic Management",
        chart: "Chart topic",
        portlet: {
            title: "List sms topic"
        }
    },
    manager: {
        add: "Add manager",
        edit: "Edit manager",
        title: "List manager",
        name: "Name",
        description: "Description",
        placeholder: {
            search: "Enter name",
            name: "Enter name",
            description: "Enter description"
        }
    },
    role: {
        add: "Add role",
        edit: "Edit role",
        title: "List role",
        name: "Name",
        description: "Description",
        placeholder: {
            search: "Enter role name",
            name: "Enter name",
            description: "Enter description"
        }
    }
};
