import Vue from "vue";
import Child from "./Child";
import { HasError, AlertError, AlertSuccess } from "vform";

/*
 |--------------------------------------------------------------------------
 | Common
 |--------------------------------------------------------------------------
 */
import Dropdown from "./common/Dropdown";
import DynamicTag from "./common/DynamicTag";
import VButton from "./common/VButton";
import Alert from "./common/Alert";
import Scrollbar from "./common/Scrollbar";
import Portlet from "./common/Portlet";
import DataTable from "./common/DataTable";
import Modal from "./common/Modal";
import FormControl from "./common/FormControl";
import Select2 from "./common/Select2";
import DatePicker from "./common/DatePicker";
import FileUpload from "./common/FileUpload";
import TheHighcharts from "./common/TheHighcharts";
import AnotherHighcharts from "./common/AnotherHighcharts";
import DownloadButton from "./common/DownloadButton";
import TheDateRange from "./common/TheDateRange";
import WeekDayPicker from "./common/WeekDayPicker";
import PerfectScrollbar from "vue2-perfect-scrollbar";
import FormRangeControl from "./common/FormRangeControl";

/*
 |--------------------------------------------------------------------------
 | Elements
 |--------------------------------------------------------------------------
 */
import Notification from "./elements/Notification";
import LanguageChosen from "./elements/LanguageChosen";
import ProfileActions from "./elements/ProfileActions";
import TimeRangeFilter from "./elements/filter/TimeRangeFilter";
import DateFilterMonth from "./elements/filter/DateFilterMonth";

/*
 |--------------------------------------------------------------------------
 | Elements
 |--------------------------------------------------------------------------
 */
import SpamLabelChosen from "./elements/chosens/SpamLabelChosen";
import UserChosen from "./elements/chosens/UserChosen";

// Components that are registered global.
[
    Child,
    HasError,
    AlertError,
    AlertSuccess,
    Dropdown,
    DynamicTag,
    VButton,
    Alert,
    Scrollbar,
    Portlet,
    DataTable,
    Modal,
    FormControl,
    FormRangeControl,
    Select2,
    DatePicker,
    FileUpload,
    TheHighcharts,
    AnotherHighcharts,
    DownloadButton,
    TheDateRange,
    DateFilterMonth,
    WeekDayPicker,

    Notification,
    LanguageChosen,
    ProfileActions,

    SpamLabelChosen,
    UserChosen,
    TimeRangeFilter
].forEach(Component => {
    Vue.component(Component.name, Component);
});
Vue.use(PerfectScrollbar);
