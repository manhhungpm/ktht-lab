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
            }
        ]
    },
    {
        title: "Báo cáo",
        icon: "flaticon-search",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        children: [
            {
                title: "Báo cáo tương quan các nhóm",
                route: "statistic/group"
            },
            {
                title: "Báo cáo cuộc gọi nghi ngờ spam",
                route: "statistic/spam"
            }
        ]
    }
];
