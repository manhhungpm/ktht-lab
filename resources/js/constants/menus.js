import * as ROLE from "~/constants/roles";
import routes from "~/router/routes";

export default [
    {
        title: "menu.dashboard",
        icon: "flaticon-line-graph",
        route: "/",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
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
                title: "menu.admin.class",
                route: "/admin/class"
            },
            {
                title: "menu.admin.faculty",
                route: "/admin/faculty"
            }
        ]
    },
    {
        title: "menu.device.title",
        icon: "flaticon-technology-2",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN],
        children: [
            {
                title: "menu.device.group",
                route: "/device/group"
            },
            {
                title: "menu.device.type",
                route: "/device/type"
            },
            {
                title: "menu.device.store",
                route: "/device/store"
            },
            {
                title: "menu.device.provider",
                route: "/device/provider"
            }
        ]
    },
    {
        title: "menu.project",
        icon: "flaticon-list",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN, ROLE.ROLE_LEADER],
        route: "/project"
    },
    {
        title: "menu.statistic.title",
        icon: "flaticon-graphic",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN, ROLE.ROLE_LEADER],
        route: "/statistic"
    },
    {
        title: "menu.rent",
        icon: "flaticon-calendar-with-a-clock-time-tools",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_ADMIN, ROLE.ROLE_LEADER, ROLE.ROLE_USER, ROLE.ROLE_REVIEW],
        route: "/rent"
    }
];
