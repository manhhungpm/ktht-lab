import i18n from "~/plugins/i18n";

const NotFound = () =>
    import("~/pages/errors/404.vue").then(m => m.default || m);

const Login = () => import("~/pages/auth/Login.vue").then(m => m.default || m);

const SsoLogin = () =>
    import("~/pages/auth/SsoLogin.vue").then(m => m.default || m);

const Dashboard = () => import("~/pages/index.vue").then(m => m.default || m);

//Admin component
const User = () =>
    import("~/pages/admin/User/users.vue").then(m => m.default || m);
const Manager = () =>
    import("~/pages/admin/Manager/managers.vue").then(m => m.default || m);
const Role = () =>
    import("~/pages/admin/Role/roles.vue").then(m => m.default || m);

//Highchart
const StatisticTimeCall = () =>
    import("~/pages/statistic/TimeCall/TimeCall.vue").then(m => m.default || m);

const StatisticSumCall = () =>
    import("~/pages/statistic/SumCall/SumCall.vue").then(m => m.default || m);

const StatisticSpam = () =>
    import("~/pages/statistic/Spam/Spam.vue").then(m => m.default || m);

const StatisticSpamCallDetail = () =>
    import("~/pages/statistic/SpamCallDetail/SpamCallDetail.vue").then(
        m => m.default || m
    );

//Black white
const BlackWhiteList = () =>
    import("~/pages/black-white/BlackWhiteList.vue").then(m => m.default || m);

export default {
    dashboard: { path: "/", name: "dashboard", component: Dashboard },

    login: { path: "/login", name: "login", component: Login },

    sso_login: {
        path: "/sso-login",
        component: SsoLogin,
        props: route => ({ query: route.query.token })
    },

    not_found: { path: "*", name: "not_found", component: NotFound },

    //Admin route
    user: {
        path: "/admin/users",
        name: "admin.user",
        component: User,
        meta: { title: i18n.t("admin.users.manage") }
    },

    manager: {
        path: "/admin/managers",
        name: "admin.manager",
        component: Manager
    },

    role: {
        path: "/admin/roles",
        name: "admin.role",
        component: Role
    },

    //Statistic route
    statistic_time_call: {
        path: "/statistic/time-call",
        name: "statistic.time_call",
        component: StatisticTimeCall
    },
    statistic_sum_call: {
        path: "/statistic/sum-call",
        name: "statistic.sum_call",
        component: StatisticSumCall
    },
    statistic_spam: {
        path: "/statistic/spam",
        name: "statistic.spam",
        component: StatisticSpam
    },
    statistic_spam_call_detail: {
        path: "/statistic/spam-call-detail",
        name: "statistic.spam_call_detail",
        component: StatisticSpamCallDetail
    },

    //Black white route
    black_white_list: {
        path: "/black-white-list",
        name: "black_white.list.title",
        component: BlackWhiteList
    }
};
