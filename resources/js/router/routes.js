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

const Configs = () =>
    import("~/pages/admin/Configs/configs.vue").then(m => m.default || m);

const SmsTopic = () =>
    import("~/pages/admin/SmsTopic/smstopics.vue").then(m => m.default || m);

const ManageApi = () =>
    import("~/pages/admin/ManageApi/manage-api.vue").then(m => m.default || m);

//Spam component
const SpamPatternsLabeled = () =>
    import("~/pages/spam/spam-patterns/labeled/labeled.vue").then(
        m => m.default || m
    );

const SpamStatistic = () =>
    import("~/pages/spam/statistic-review/statistic/statistic.vue").then(
        m => m.default || m
    );

const SpamPatternsWarning = () =>
    import("~/pages/spam/spam-patterns/warning/warning.vue").then(
        m => m.default || m
    );

//Brandname component
const BrandnameList = () =>
    import("~/pages/brandname/List/list.vue").then(m => m.default || m);

const BrandnameConfig = () =>
    import("~/pages/brandname/Config/config/config.vue").then(
        m => m.default || m
    );

const BrandnameConfigSmsGroups = () =>
    import("~/pages/brandname/Config/sms-groups/sms-groups.vue").then(
        m => m.default || m
    );

const BrandnameConfigSmsTypes = () =>
    import("~/pages/brandname/Config/sms-types/sms-types.vue").then(
        m => m.default || m
    );

const BrandnameReportDayAlias = () =>
    import("~/pages/brandname/statistic/day/alias.vue").then(
        m => m.default || m
    );
const BrandnameReportMonthAlias = () =>
    import("~/pages/brandname/statistic/month/alias.vue").then(
        m => m.default || m
    );

const BrandnameReportAccumulateAlias = () =>
    import("~/pages/brandname/statistic/accumulate-day/alias.vue").then(
        m => m.default || m
    );

const BrandnameReportMonthSegment = () =>
    import("~/pages/brandname/statistic/month/segment.vue").then(
        m => m.default || m
    );

const BrandnameReportDaySegment = () =>
    import("~/pages/brandname/statistic/day/segment.vue").then(
        m => m.default || m
    );

const BrandnameReportAccumulateDayAlias = () =>
    import("~/pages/brandname/statistic/accumulate-day/alias.vue").then(
        m => m.default || m
    );

const BrandnameReportAccumulateDaySegment = () =>
    import("~/pages/brandname/statistic/accumulate-day/segment.vue").then(
        m => m.default || m
    );

const BrandnameReportDuplicateDaySegment = () =>
    import("~/pages/brandname/statistic/duplicate/day/segment.vue").then(
        m => m.default || m
    );

const BrandnameReportDuplicateDayAlias = () =>
    import("~/pages/brandname/statistic/duplicate/day/alias.vue").then(
        m => m.default || m
    );

const BrandnameReportDuplicateMonthAlias = () =>
    import("~/pages/brandname/statistic/duplicate/month/alias.vue").then(
        m => m.default || m
    );

const BrandnameReportDuplicateMonthSegment = () =>
    import("~/pages/brandname/statistic/duplicate/month/segment.vue").then(
        m => m.default || m
    );

const BrandnameSmsSegmentConfig = () =>
    import("~/pages/brandname/Config/segment_config/segment-config.vue").then(
        m => m.default || m
    );

const BrandnameSmsDuplicateConfig = () =>
    import(
        "~/pages/brandname/Config/duplicate_config/duplicate-config.vue"
    ).then(m => m.default || m);

const BrandnameSmsSegmentWarningConfig = () =>
    import(
        "~/pages/brandname/Config/segment_warning_config/segment-warning-config.vue"
    ).then(m => m.default || m);

const BrandnameTimeframeConfig = () =>
    import(
        "~/pages/brandname/Config/timeframe_config/timeframe-config.vue"
    ).then(m => m.default || m);

const BrandnameReportDetailed = () =>
    import("~/pages/brandname/statistic/detailed/detailed.vue").then(
        m => m.default || m
    );

const BrandnameTimeWarningConfig = () =>
    import("~/pages/brandname/Config/time_warning/time_warning.vue").then(
        m => m.default || m
    );

const BrandnameReportOutlierDetail = () =>
    import(
        "~/pages/brandname/statistic/outlier_detail/outlier-detail.vue"
    ).then(m => m.default || m);

const BrandnameReportWrongTime = () =>
    import("~/pages/brandname/statistic/wrong_time/wrong-time.vue").then(
        m => m.default || m
    );

export default {
    dashboard: { path: "/", name: "dashboard", component: Dashboard },

    login: { path: "/login", name: "login", component: Login },

    sso_login: {
        path: "/sso-login",
        component: SsoLogin,
        props: route => ({ query: route.query.token })
    },

    not_found: { path: "*", name: "not_found", component: NotFound },

    //Spam route
    spam_patterns_labeled: {
        name: "spam.spam_patterns_labeled",
        path: "/spam/spam-patterns/labeled",
        component: SpamPatternsLabeled
    },

    spam_patterns_warning: {
        name: "spam.spam_patterns_warning",
        path: "/spam/spam-patterns/warning",
        component: SpamPatternsWarning
    },

    spam_statistic: {
        name: "spam.statistic",
        path: "/spam/statistic",
        component: SpamStatistic
    },

    //Brandname route

    brandname_list: {
        path: "/brandname/list",
        name: "brandname.list",
        component: BrandnameList,
        meta: {
            title: i18n.t("brandname.list.title")
        }
    },

    brandname_config: {
        path: "/brandname/config",
        name: "brandname.config",
        component: BrandnameConfig,
        meta: {
            title: i18n.t("brandname.config.title")
        }
    },

    brandname_sms_groups: {
        name: "brandname.sms_groups",
        path: "/brandname/config/sms-groups",
        component: BrandnameConfigSmsGroups
    },

    brandname_sms_types: {
        name: "brandname.sms_types",
        path: "/brandname/config/sms-types",
        component: BrandnameConfigSmsTypes
    },

    brandname_report_day_alias: {
        name: "brandname.report.day.alias",
        path: "/brandname/report/day/alias",
        component: BrandnameReportDayAlias
    },

    brandname_report_day_segment: {
        name: "brandname.report.day.segment",
        path: "/brandname/report/day/segment",
        component: BrandnameReportDaySegment
    },

    brandname_report_accumulate_day_alias: {
        name: "brandname.report.accumulate.alias",
        path: "/brandname/report/accumulate-day/alias",
        component: BrandnameReportAccumulateDayAlias
    },

    brandname_report_accumulate_day_segment: {
        name: "brandname.report.accumulate.segment",
        path: "/brandname/report/accumulate-day/segment",
        component: BrandnameReportAccumulateDaySegment
    },

    brandname_report_month_alias: {
        name: "brandname.report.month.alias",
        path: "/brandname/report/month/alias",
        component: BrandnameReportMonthAlias
    },

    brandname_report_accumulate_segment: {
        name: "brandname.report.month.segment",
        path: "/brandname/report/month/segment",
        component: BrandnameReportMonthSegment
    },

    brandname_report_accumulate_alias: {
        name: "brandname.report.accumulate.alias",
        path: "/brandname/report/accumulate/alias",
        component: BrandnameReportAccumulateAlias
    },

    brandname_report_duplicate_day_segment: {
        name: "brandname.report.duplicate.day.segment",
        path: "/brandname/report/duplicate/day/segment",
        component: BrandnameReportDuplicateDaySegment
    },

    brandname_report_duplicate_day_alias: {
        name: "brandname.report.duplicate.day.alias",
        path: "/brandname/report/duplicate/day/alias",
        component: BrandnameReportDuplicateDayAlias
    },

    brandname_report_duplicate_month_alias: {
        name: "brandname.report.duplicate.month.alias",
        path: "/brandname/report/duplicate/month/alias",
        component: BrandnameReportDuplicateMonthAlias
    },

    brandname_report_duplicate_month_segment: {
        name: "brandname.report.duplicate.month.segment",
        path: "/brandname/report/duplicate/month/segment",
        component: BrandnameReportDuplicateMonthSegment
    },

    brandname_report_outlier_detail: {
        name: "brandname.report.outlier_detail.title",
        path: "/brandname/report/outlier-detail",
        component: BrandnameReportOutlierDetail
    },

    brandname_report_wrong_time: {
        name: "brandname.report.wrong_time.title",
        path: "/brandname/report/wrong-time",
        component: BrandnameReportWrongTime
    },

    branchname_sms_segment_config: {
        path: "/brandname/sms/segment/config",
        name: "brandname.configs.segment_config.title",
        component: BrandnameSmsSegmentConfig,
        meta: { title: i18n.t("admin.configs.manage") }
    },

    branchname_sms_duplicate_config: {
        path: "/brandname/sms/duplicate/config",
        name: "brandname.configs.duplicate_config.title",
        component: BrandnameSmsDuplicateConfig
        // meta: { title: i18n.t("admin.configs.manage") }
    },

    branchname_sms_segment_warning_config: {
        path: "/brandname/sms/segment_warning/config",
        name: "brandname.configs.segment_warning_config.title",
        component: BrandnameSmsSegmentWarningConfig
        // meta: { title: i18n.t("admin.configs.manage") }
    },

    branchname_timeframe_config: {
        path: "/brandname/config/timeframe",
        name: "brandname.configs.timeframe.title",
        component: BrandnameTimeframeConfig
        // meta: { title: i18n.t("admin.configs.manage") }
    },

    brandname_report_detailed: {
        name: "brandname.report.detailed.title",
        path: "/brandname/report/detailed",
        component: BrandnameReportDetailed
    },

    brandname_time_warning_config: {
        name: "brandname.configs.time_warning.title",
        path: "/brandname/config/time-warning",
        component: BrandnameTimeWarningConfig
    },

    //Admin route
    user: {
        path: "/admin/users",
        name: "admin.user",
        component: User,
        meta: { title: i18n.t("admin.users.manage") }
    },

    config: {
        path: "/admin/configs",
        name: "admin.configs",
        component: Configs,
        meta: { title: i18n.t("admin.configs.manage") }
    },

    manage_api: {
        path: "/admin/manage-api",
        name: "admin.api",
        component: ManageApi,
        meta: { title: i18n.t("admin.api.manage") }
    },

    sms_topic: {
        path: "/admin/sms-topic",
        name: "admin.smstopics",
        component: SmsTopic
    }
};
