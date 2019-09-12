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

//Highchart
const StatisticGroup = () =>
    import("~/pages/statistic/Group.vue").then(m => m.default || m);

const StatisticSpam = () =>
    import("../pages/statistic/Spam.vue").then(m => m.default || m);

const StatisticSpamCallDetail = () =>
    import("../pages/statistic/SpamCallDetail.vue").then(m => m.default || m);

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

    //Statistic route
    statistic_group: {
        path: "/statistic/group",
        name: "statistic.group",
        component: StatisticGroup
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
    }
};
