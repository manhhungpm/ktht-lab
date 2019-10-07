import * as ROLE from "~/constants/roles";
import routes from "~/router/routes";

export default [
    {
        title: "menu.dashboard",
        icon: "flaticon-line-graph",
        route: "/"
    },
    {
        title: "menu.management",
        icon: "flaticon-cogwheel",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        children: [
            {
                title: "menu.admin.user",
                route: "/admin/users"
            },
            {
                title: "menu.admin.role",
                route: "/admin/roles"
            },
            {
                title: "menu.admin.manager",
                route: "/admin/managers"
            }
        ]
    },
    {
        title: "menu.statistic.title",
        icon: "flaticon-graphic",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        children: [
            {
                title: "menu.statistic.sum_call",
                route: "/statistic/sum-call"
            },
            {
                title: "menu.statistic.time_call",
                route: "/statistic/time-call"
            },
            {
                title: "menu.statistic.spam",
                route: "/statistic/spam"
            },
            {
                title: "menu.statistic.spam_call_detail",
                route: "/statistic/spam-call-detail"
            }
        ]
    },
    {
        title: "menu.spam_call_detail",
        icon: "flaticon-exclamation",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        route: "/spam-call-detail"
    },
    {
        title: "menu.black_white.list.title",
        icon: "flaticon-list",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        route: "/black-white-list"
    },
    {
        title: "menu.alias_block_spam",
        icon: "flaticon-search",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        route: "/alias-block-spam"
    }
];
