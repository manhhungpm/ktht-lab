import * as ROLE from "~/constants/roles";
import routes from "~/router/routes";

export default [
    {
        title: "menu.dashboard",
        icon: "flaticon-line-graph",
        route: "/"
    },
    {
        title: "menu.spam.spam",
        icon: "flaticon-danger",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_CC, ROLE.ROLE_ROAMING],
        // roles: 'abc',
        children: [
            {
                title: "menu.spam.patterns",
                children: [
                    // {
                    //     // roles: 'demo',
                    //     // permissions: 'demo',
                    //     title: "menu.spam.spam_patterns_warning",
                    //     route: "/spam/spam-patterns/warning"
                    //     // roles: 'demo'
                    // }
                    // {
                    //     title: "menu.spam.spam_patterns_labeled",
                    //     route: "/spam/spam-patterns/labeled"
                    // }
                ]
            },
            {
                title: "menu.spam.statistic_review",
                children: [
                    {
                        title: "menu.spam.statistic",
                        route: "/spam/statistic"
                    }
                ]
            }
        ]
    },
    {
        title: "menu.brandname.brandname",
        icon: "flaticon-list",
        roles: [ROLE.ROLE_ROOT, ROLE.ROLE_CC],
        children: [
            {
                title: "menu.brandname.list",
                route: "/brandname/list"
            },
            // {
            //     title: "menu.brandname.config",
            //     route: "/brandname/config"
            // },
            {
                title: "menu.brandname.config",
                children: [
                    {
                        title: "menu.brandname.sms_groups",
                        route: "/brandname/config/sms-groups"
                    },
                    {
                        title: "menu.brandname.sms_types",
                        route: "/brandname/config/sms-types"
                    },
                    {
                        title: "menu.brandname.configs.segment_config.title",
                        route: "/brandname/sms/segment/config"
                    },
                    {
                        title: "menu.brandname.configs.duplicate_config.title",
                        route: "/brandname/sms/duplicate/config"
                    },
                    {
                        title:
                            "menu.brandname.configs.segment_warning_config.title",
                        route: "/brandname/sms/segment_warning/config"
                    },
                    {
                        title: "menu.brandname.configs.timeframe.title",
                        route: "/brandname/config/timeframe"
                    },
                    {
                        title: "menu.brandname.configs.time_warning.title",
                        route: "/brandname/config/time-warning"
                    }
                ]
            },
            {
                title: "menu.brandname.report.title",
                children: [
                    {
                        title: "menu.brandname.report.day.title",
                        children: [
                            {
                                title: "menu.brandname.report.day.alias",
                                route: "/brandname/report/day/alias"
                            },
                            {
                                title: "menu.brandname.report.day.segment",
                                route: "/brandname/report/day/segment"
                            }
                        ]
                    },
                    {
                        title: "menu.brandname.report.accumulate.title",
                        children: [
                            {
                                title: "menu.brandname.report.accumulate.alias",
                                route: "/brandname/report/accumulate-day/alias"
                            },
                            {
                                title:
                                    "menu.brandname.report.accumulate.segment",
                                route:
                                    "/brandname/report/accumulate-day/segment"
                            }
                        ]
                    },
                    {
                        title: "menu.brandname.report.duplicate.title",
                        children: [
                            {
                                title:
                                    "menu.brandname.report.duplicate.day.alias",
                                route: "/brandname/report/duplicate/day/alias"
                            },
                            {
                                title:
                                    "menu.brandname.report.duplicate.day.segment",
                                route: "/brandname/report/duplicate/day/segment"
                            },
                            {
                                title:
                                    "menu.brandname.report.duplicate.month.alias",
                                route: "/brandname/report/duplicate/month/alias"
                            },
                            {
                                title:
                                    "menu.brandname.report.duplicate.month.segment",
                                route:
                                    "/brandname/report/duplicate/month/segment"
                            }
                        ]
                    },
                    {
                        title: "menu.brandname.report.month.title",
                        children: [
                            {
                                title: "menu.brandname.report.month.alias",
                                route: "/brandname/report/month/alias"
                            },
                            {
                                title: "menu.brandname.report.month.segment",
                                route: "/brandname/report/month/segment"
                            }
                        ]
                    },
                    {
                        title: "menu.brandname.report.detailed.title",
                        route: "/brandname/report/detailed"
                    },
                    {
                        title: "menu.brandname.report.outlier_detail.title",
                        route: "/brandname/report/outlier-detail"
                    },
                    {
                        title: "menu.brandname.report.wrong_time.title",
                        route: "/brandname/report/wrong-time"
                    }
                ]
            }
        ]
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
                title: "menu.admin.api",
                route: "/admin/manage-api"
            },
            {
                title: "menu.admin.configs",
                route: "/admin/configs"
            },
            {
                title: "menu.admin.smstopics",
                route: "/admin/sms-topic"
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
