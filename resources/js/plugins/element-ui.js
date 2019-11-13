import Vue from "vue";
import i18n from "vue-i18n";
import "element-ui/lib/theme-chalk/index.css";
import {
    Pagination,
    DatePicker,
    Loading,
    Collapse,
    CollapseItem,
    TimePicker,
    Breadcrumb,
    BreadcrumbItem
} from "element-ui";

Vue.component(Pagination.name, Pagination);
Vue.component("loading", Loading);
Vue.use(Loading.directive);
Vue.component(DatePicker.name, DatePicker);
Vue.component(Collapse.name, Collapse);
Vue.component(CollapseItem.name, CollapseItem);
Vue.component(TimePicker.name, TimePicker);
Vue.component(Breadcrumb.name, Breadcrumb);
Vue.component(BreadcrumbItem.name, BreadcrumbItem);
// configure language
