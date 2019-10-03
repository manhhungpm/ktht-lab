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
        icon: "flaticon-search",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        children: [
            {
                title: "Báo cáo theo thời gian gọi",
                route: "/statistic/time-call"
            },
            {
                title: "Báo cáo theo số lượng cuộc gọi",
                route: "/statistic/sum-call"
            },
            {
                title: "Báo cáo cuộc gọi nghi ngờ spam",
                route: "/statistic/spam"
            },
            {
                title: "Báo cáo chi tiết gán nhãn spam các cuộc gọi nghi ngờ",
                route: "/statistic/spam-call-detail"
            }
        ]
    },
    {
        title: "menu.black_white.list.title",
        icon: "flaticon-list",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        route: "/black-white-list"
    }
];
