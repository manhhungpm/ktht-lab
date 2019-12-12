import i18n from "~/plugins/i18n";

const NotFound = () =>
    import("~/pages/errors/404.vue").then(m => m.default || m);

const Login = () => import("~/pages/auth/Login.vue").then(m => m.default || m);

const Dashboard = () => import("~/pages/index.vue").then(m => m.default || m);

//Admin component
const User = () =>
    import("~/pages/admin/User/users.vue").then(m => m.default || m);
const Class = () =>
    import("~/pages/admin/Class/class.vue").then(m => m.default || m);
const Role = () =>
    import("~/pages/admin/Role/roles.vue").then(m => m.default || m);
const Faculty = () =>
    import("~/pages/admin/Faculty/faculty.vue").then(m => m.default || m);

//Device
const DeviceGroup = () =>
    import("~/pages/device/DeviceGroup/DeviceGroup.vue").then(m=>m.default || m);
const DeviceType = () =>
    import("~/pages/device/DeviceType/DeviceType.vue").then(m=>m.default || m);
const Provider = () =>
    import("~/pages/device/Provider/Provider.vue").then(m=>m.default || m);
const Store = () =>
    import("~/pages/device/Store/Store.vue").then(m=>m.default || m);

//Project
const Project = () =>
    import("~/pages/project/Project.vue").then(m=>m.default || m);

//Rent
const Rent = () =>
    import("~/pages/rent/Rent.vue").then(m=>m.default || m);

//Highchart
const Statistic = () => import("~/pages/statistic/Statistic.vue").then(m=>m.default || m);

export default {
    dashboard: { path: "/", name: "dashboard", component: Dashboard },

    login: { path: "/login", name: "login", component: Login },

    not_found: { path: "*", name: "not_found", component: NotFound },

    //Admin route
    user: {
        path: "/admin/users",
        name: "admin.user",
        component: User,
        meta: { title: i18n.t("admin.users.manage") }
    },

    class: {
        path: "/admin/class",
        name: "admin.class",
        component: Class
    },

    role: {
        path: "/admin/roles",
        name: "admin.role",
        component: Role
    },

    faculty: {
        path: "/admin/faculty",
        name: "admin.faculty",
        component: Faculty
    },

    //Device route
    device_group: {
        path: "/device/group",
        name: "device.group",
        component: DeviceGroup
    },

    device_type: {
        path: "/device/type",
        name: "device.type",
        component: DeviceType
    },

    provider: {
        path: "/device/provider",
        name: "device.provider",
        component: Provider
    },

    store: {
        path: "/device/store",
        name: "device.store",
        component: Store
    },

    //Project
    project: {
        path: "/project",
        name: "project",
        component: Project
    },

    //Project
    rent: {
        path: "/rent",
        name: "rent",
        component: Rent
    },

    //Statistic route
    // statistic_time_call: {
    //     path: "/statistic/time-call",
    //     name: "statistic.time_call",
    //     component: StatisticTimeCall
    // },
    // statistic_sum_call: {
    //     path: "/statistic/sum-call",
    //     name: "statistic.sum_call",
    //     component: StatisticSumCall
    // },
    // statistic_spam: {
    //     path: "/statistic/spam",
    //     name: "statistic.spam",
    //     component: StatisticSpam
    // },
    // statistic_spam_call_detail: {
    //     path: "/spam-call-detail",
    //     name: "statistic.spam_call_detail",
    //     component: SpamCallDetail
    // },
    statistic: {
        path: "/statistic",
        name: "statistic.title",
        component: Statistic
    },
};
